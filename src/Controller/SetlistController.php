<?php

namespace App\Controller;

use App\Entity\Setlist;
use App\Entity\User;
use App\Service\SetlistClientFacade;
use App\Service\SpotifyApiFacade;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SetlistController extends AbstractController
{
    /**
     * @Route("/setlists", name="setlists")
     * @IsGranted("ROLE_USER")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Setlist::class);
        $setlists = $repository->findAll();
        
        return $this->render('setlist/index.html.twig', [
            'controller_name' => 'SetlistController',
            'setlists' => $setlists,
        ]);
    }
    
    /**
     * @Route("/setlists/{id}", name="setlists_see")
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
     * @Route("/setlists/new", name="setlists_new")
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
    public function createLucky(Request $request, SessionInterface $session, SpotifyApiFacade $spotifyClient, SetlistClientFacade $setlistClient, EntityManagerInterface $entityManager)
    {
        if ($session->has('spotify_token'))
        {
            $artist = $request->request->get('artist');
            $playlistName = $request->request->get('name');

            list($setlistfm, $songs) = $setlistClient->searchLastSetlistForArtist($artist);
        
            $spotifyClient->setAccessToken($session->get('spotify_token'));
                        
            $playlist = $spotifyClient->addPlaylistLucky($playlistName, $songs, $artist);
            
            $setlist = new Setlist();
            $setlist->setName($playlistName);
            $setlist->setCity($setlistfm['venue']['city']['name']);
            $setlist->setDate(new \DateTime($setlistfm['eventDate']));
            $setlist->setSetlistfmId($setlistfm['id']);
            $setlist->setSpotifyId($playlist->id);
            
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->find(2);
            
            $setlist->setUser($user);
            $setlist->setVenue($setlistfm['venue']['name']);
            
            $entityManager->persist($setlist);
            $entityManager->flush();
            
            return $this->redirectToRoute('setlists');
            
        } else {
            return $this->redirectToRoute('spotify_get_access');
        }
    }
    
    /**
     * @Route("/setlists/search_artist", name="setlists_search_artist")
     * @IsGranted("ROLE_USER")
     */
    public function search(Request $request, SessionInterface $session, SetlistClientFacade $setlistClient, SpotifyApiFacade $spotifyClient)
    {
        if ($session->has('spotify_token'))
        {
            $spotifyClient->setAccessToken($session->get('spotify_token'));
            $artist = $request->request->get('artist');
            $artistInfo = $spotifyClient->getArtistInfo($artist);

            $setlists = $setlistClient->searchSetlistsForArtist($artist);
        
            return $this->render('setlist/search_results.html.twig', [
                'controller_name' => 'SetlistController',
                'artist' => $artistInfo,
                'setlists' => $setlists,
            ]);
        } else {
            return $this->redirectToRoute('spotify_get_access');
        }
    }
}
