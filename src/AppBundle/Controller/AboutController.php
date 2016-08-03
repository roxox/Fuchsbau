<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AboutController extends Controller
{
    /**
     */
    public function showImpressumAction(Request $request)
    {

//        $user = $this->getUser();
//        if (!is_object($user) || !$user instanceof UserInterface) {
//            throw new AccessDeniedException('This user does not have access to this section.');
//        }
//
//        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
//        $dispatcher = $this->get('event_dispatcher');
//
////        $event = new GetResponseUserEvent($user, $request);
////        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);
////
////        if (null !== $event->getResponse()) {
////            return $event->getResponse();
////        }
//
//        /** @var Person $person */
//        $person = $user->getPerson();

        return $this->render(
            'About/display_impressum.html.twig'
//            ,array(
//                'adressen' => $person->getAdressen(),
//                'telefonnummern' => $person->getTelefonnummern(),
//                'emails' => $person->getEmailadressen(),
//                'person' => $person,
//                'user' => $user)
        );

    }
}
