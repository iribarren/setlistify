<?php

namespace App\Controller;

use App\Entity\Setlist;
use App\Service\SetlistClientFacade;
use App\Service\SpotifyApiFacade;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SetlistController extends AbstractController implements UseSpotifyInterface
{
    /**
     * @Route("/setlists/{_locale}", name="setlists", locale="en", requirements={"_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        $page = $request->query->get('page') ?? 1;
        $itemsPerPage = 4;//FIXME move to conf file
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Setlist::class);
        $setlists = $repository->getAllSetlistsUser($user, $page, $itemsPerPage);
        
        return $this->render('setlist/index.html.twig', [
            'controller_name' => 'SetlistController',
            'setlists' => $setlists,
            'itemsPerPage' => $itemsPerPage,
            'page' => $page,
            'total' => $setlists->count(),
        ]);
    }
    
    /**
     * @Route("/setlists/{_locale}/see/{id}", name="setlists_see",  requirements={"id":"\d+","_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function see(Setlist $setlist)
    {
        $repository = $this->getDoctrine()->getRepository(Setlist::class);
        $setlists = $repository->findAll();
        
        return $this->render('setlist/see.html.twig', [
            'controller_name' => 'SetlistController',
            'setlist' => $setlist,
        ]);
    }
    
    /**
     * @Route("/setlists/{_locale}/new", name="setlists_new", requirements={"_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function new()
    {
        return $this->render('setlist/new.html.twig', [
            'controller_name' => 'SetlistController',
        ]);
    }
    
    /**
     * @Route("/setlists/add-lucky", name="setlists_add_lucky")
     * @IsGranted("ROLE_USER")
     */
    public function createLucky(Request $request, SessionInterface $session, SpotifyApiFacade $spotifyClient, SetlistClientFacade $setlistClient, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $artist = $request->request->get('artist');
        $playlistName = $request->request->get('name');

        try {
            list($setlistfm, $songs) = $setlistClient->searchLastSetlistForArtist($artist);
        } catch (Exception $e) {
            $this->addFlash('notice', $translator->trans('We could not find the artist you were looking for'));
            return $this->redirectToRoute('setlists_new');
        }

        $spotifyClient->setAccessToken($session->get('spotify_token'));

        $playlist = $spotifyClient->addPlaylistLucky($playlistName, $songs, $artist);

        $setlist = new Setlist();
        $setlist->setName($playlistName);
        $setlist->setCity($setlistfm['venue']['city']['name']);
        $setlist->setDate(new DateTime($setlistfm['eventDate']));
        $setlist->setSetlistfmId($setlistfm['id']);
        $setlist->setSpotifyId($playlist->id);

        $user = $this->getUser();

        $setlist->setUser($user);
        $setlist->setVenue($setlistfm['venue']['name']);

        $entityManager->persist($setlist);
        $entityManager->flush();

        return $this->redirectToRoute('setlists');
    }
    
    /**
     * @Route("/setlists/{_locale}/search_artist", name="setlists_search_artist", requirements={"_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function search(Request $request, SessionInterface $session, SetlistClientFacade $setlistClient, SpotifyApiFacade $spotifyClient, TranslatorInterface $translator)
    {
        $page = $request->query->get('page') ?? 1;

        if ($request->query->has('artistID')) {
            $artistMbid = $request->query->get('artistID');
            $artistsSetlistFM = $setlistClient->searchArtists($artistMbid);

        } else {
            if ($request->query->has('artist')) {
                $artistSearch = $request->query->get('artist');                
            } else {
                $artistSearch = $request->request->get('artist');                
            }
            $artistsSetlistFM = $setlistClient->searchArtists("",$artistSearch);
        }


        if (!isset($artistsSetlistFM['artist'])) {
            $this->addFlash('notice', $translator->trans('We could not find the artist you were looking for'));
            return $this->redirectToRoute('setlists_new');
        }

        $artistSetlistFM =  $artistsSetlistFM['artist'][0];
        //little trick, we put the spotify API call between the setlistfm API calls to avoid triggering too many requests
        $spotifyClient->setAccessToken($session->get('spotify_token'));
        $artistsSpotify = $spotifyClient->getArtistInfo($artistSetlistFM['name']);
        $artistInfo = $artistsSpotify->artists->items[0];
        //we wait a secont to avoid hitting the setlist.fm limit
        sleep(1);
        $setlists = $setlistClient->searchSetlistsForArtist($artistSetlistFM, $page);
        $allArtists = $this->parseOtherArtists($artistsSetlistFM['artist']);

        return $this->render('setlist/event_list.html.twig', [
            'controller_name' => 'SetlistController',
            'artist' => $artistInfo,
            'allArtists' => $allArtists,
            'setlists' => $setlists,
        ]);
    }

    /**
     * @Route("/setlists/{_locale}/preview", name="setlist_preview", requirements={"_locale":"en|es"})
     * @IsGranted("ROLE_USER")
     */
    public function preview(Request $request, SessionInterface $session, SetlistClientFacade $setlistClient, SpotifyApiFacade $spotifyClient)
    {
        $spotifyClient->setAccessToken($session->get('spotify_token'));
        $setlistId = $request->query->get('setlistId');            
        $artist = $request->query->get('artist');            
        $setlist = $setlistClient->setlist->getById($setlistId);
        $setlistSongs = $setlistClient->getSetlistSongs($setlist);
        $songsSpotifyInfo = $spotifyClient->searchSongsFullInfo($setlistSongs, $setlist['artist']['name']);
        $previewSongs = $this->parsePreviewSongs($setlistSongs, $songsSpotifyInfo);

        return $this->render('setlist/preview.html.twig', [
            'controller_name' => 'SetlistController',
            'setlist' => $setlist,
            'setlistSongs' => $setlistSongs,
            'songsSpotifyInfo' => $songsSpotifyInfo,
            'songs' => $previewSongs,

        ]);
    }

    /**
     * @Route("/setlists/create", name="setlists_create")
     * @IsGranted("ROLE_USER")
     */
    public function createPlaylist(Request $request, SessionInterface $session, SpotifyApiFacade $spotifyClient, SetlistClientFacade $setlistClient, EntityManagerInterface $entityManager)
    {
        $spotifyClient->setAccessToken($session->get('spotify_token'));


        $songs = $request->request->get('songs');
        $setlistId = $request->request->get('setlistId');
        $playlistName = $request->request->get('playlistName');   

        $playlist = $spotifyClient->addPlaylist($playlistName, $songs);

        $setlistfm = $setlistClient->setlist->getById($setlistId);
        $setlist = new Setlist();
        $setlist->setName($playlistName);
        $setlist->setCity($setlistfm['venue']['city']['name']);
        $setlist->setDate(new DateTime($setlistfm['eventDate']));
        $setlist->setSetlistfmId($setlistfm['id']);
        $setlist->setSpotifyId($playlist->id);

        $user = $this->getUser();

        $setlist->setUser($user);
        $setlist->setVenue($setlistfm['venue']['name']);

        $entityManager->persist($setlist);
        $entityManager->flush();

        return $this->redirectToRoute('setlists');
    }
    
    protected function parseOtherArtists($artists)
    {
        $parsedArtists = [];
        foreach ($artists as $artist) {
            $parsedArtists[] = [
                'name' => $artist['name'], 
                'url' => $this->generateUrl('setlists_search_artist', ['artistID' => $artist['mbid']]),
            ];
        }
        
        return $parsedArtists;
    }

    protected function parsePreviewSongs($setlistSongs, $songsSpotifyInfo)
    {
        $parsedSongs = [];
        foreach ($songsSpotifyInfo as $i => $songs) {
            $variations = [];
            foreach ($songs as $j =>  $song) {
                $variations[] = [
                    'id' => $i + 1,
                    'songNameSetlist' => $setlistSongs[$i]['name'],
                    'songName' => $song['name'],
                    'album' => $song['album'],
                    'cover' =>  $song['album_cover'],
                    'spotify_id' =>  $song['id'],
                ];
            }
            $parsedSongs[$i] = $variations;
        }
        
        return $parsedSongs;
    }
}
