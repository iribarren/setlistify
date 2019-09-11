<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SpotifyWebAPI\Session as SpotifySession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SpotifyController extends AbstractController
{    
    /**
     * @Route("/spotify/get_access", name="spotify_get_access")
     */
    public function getAccess(Request $request, SessionInterface $session, SpotifySession $spotifySession)
    {
        if (isset($_GET['code'])) {
            $spotifySession->requestAccessToken($_GET['code']);
            $session->set('spotify_token', $spotifySession->getAccessToken());

            $this->redirectToRoute('setlists');
        } else {
            $options = [
                'scope' => [
                    'user-read-email',
                    'user-library-modify',
                    'playlist-modify-public'
                ],
            ];
        
            header('Location: ' . $spotifySession->getAuthorizeUrl($options));
            die();
        }
    }
}
