<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // 1) build the form
        /** @var User $user */
        $user = $this->getUser();


        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $projekt */
        $sampleprojekt = $projektRepo->find(1);

        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->render('default/display_index.html.twig',
                array('sampleproject' =>$sampleprojekt ));
        }

        /** @var Person $person */
        if ($user->getPerson()) {
            $person = $user->getPerson();
            $projekte = $user->getProjekte();
            $numberOfProjects = count($projekte);

            /** @var Projekt $projekt */
            $lastOpenedProject = $user->getLastOpenedProject();

            return $this->render('default/display_index.html.twig',
                array('person' => $person,
                    'projekte' => $projekte,
                    'numberOfProjects' => $numberOfProjects,
                    'lastOpenedProject' => $lastOpenedProject,
                    'sampleproject' =>$sampleprojekt,
                    'headline' => '# Projekte mit meiner Beteiligung',
                    'user' => $user));
        } else {
            return $this->render('default/display_index.html.twig',
                array('user' => $user));
        }
    }

    /**
     * @Route("/projekte", name="projekte")
     */
    public function showProjekteAction(Request $request)
    {
        // 1) build the form
        /** @var User $user */
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            return $this->render('default/display_projekte.html.twig');
        }

        /** @var Person $person */
        if ($user->getPerson()) {
            $person = $user->getPerson();
            $projekte = $user->getProjekte();
            $numberOfProjects = count($projekte);

            /** @var Projekt $projekt */
            $lastOpenedProject = $user->getLastOpenedProject();


            return $this->render('default/display_projekte.html.twig',
                array('person' => $person,
                    'projekte' => $projekte,
                    'numberOfProjects' => $numberOfProjects,
                    'lastOpenedProject' => $lastOpenedProject,
                    //                   'meineRollen' => $person->getPersonenRollen(),
//                    'headline' => 'Willkommen (zurück) ' . $person->getVorname() . '!',
                    'headline' => '# Projekte mit meiner Beteiligung',
                    'user' => $user));
        } else {
            return $this->render('default/default_display_projekteprojekte.html.twig',
                array('user' => $user));
        }
    }
}
