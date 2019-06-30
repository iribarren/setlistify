<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SpotifyWebAPI\Session as SpotifySession;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SpotifyController extends AbstractController
{
    /**
     * @Route("/spotify/get_access", name="spotify_get_access")
     */
    public function getAccess(SessionInterface $session, SpotifySession $spotifySession)
    {
        if (isset($_GET['code'])) {
            $spotifySession->requestAccessToken($_GET['code']);
            $session->set('spotify_token', $spotifySession->getAccessToken());

            return $this->redirectToRoute('dashboard');
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
