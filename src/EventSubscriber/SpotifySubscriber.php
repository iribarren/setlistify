<?php

namespace App\EventSubscriber;

use App\Controller\UseSpotifyInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use SpotifyWebAPI\Session as SpotifySession;

/**
 * Description of SpotifySubscriber
 *
 * @author aritz
 */
class SpotifySubscriber implements EventSubscriberInterface
{
    private $session;
    private $spotifySession;

    public function __construct(SessionInterface $session, SpotifySession $spotifySession)
    {
        $this->session = $session;
        $this->spotifySession = $spotifySession;
    }
    
    public function onKernelController(FilterControllerEvent $event) {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof UseSpotifyInterface) {
            if (!$this->session->has('spotify_token')) {
                    $options = [
                    'scope' => [
                        'user-read-email',
                        'user-library-modify',
                        'playlist-modify-public'
                    ],
                ];

                header('Location: ' . $this->spotifySession->getAuthorizeUrl($options));
                die();
            }
        }
    }

    public static function getSubscribedEvents() {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

}
