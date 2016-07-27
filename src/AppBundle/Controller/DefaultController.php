<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var Person $person */
        if ($user->getPerson()) {
            $person = $user->getPerson();
            return $this->render('default/display_index.html.twig',
                array('person' => $person,
//                    'headline' => 'Willkommen (zurück) ' . $person->getVorname() . '!',
                    'headline' => 'Übersicht für ' . $person->getVorname() . ' ' . $person->getNachname() . '!',
                    'user' => $user));
        } else {
            return $this->render('default/display_index.html.twig',
                array('user' => $user));
        }
    }
}
