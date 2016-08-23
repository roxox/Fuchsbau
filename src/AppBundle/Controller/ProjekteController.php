<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firma;
use AppBundle\Entity\Grundstueck;
use AppBundle\Entity\Haus;
use AppBundle\Entity\Person;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\Rolle;
use AppBundle\Entity\User;
use AppBundle\Form\GrundstueckType;
use AppBundle\Form\HausType;
use AppBundle\Form\ProjektType;
use AppBundle\Repository\ProjektRepository;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjekteController extends Controller
{
    /**
     */
    public function displayAllProjectsForUserAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var Person $person */
        $person = $user->getPerson();

        return $this->render(
            'Projekte/display_all_projects.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );
    }

    public function displayProjectByIdAction(Request $request, $projektId)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var ProjektRepository $emailRepo */
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $currentProjekt */
        $currentProjekt = $projektRepo->find($projektId);

        /** @var Person $person */
        $person = $user->getPerson();
        foreach ($user->getProjekte() as $projekt) {
            /** @var Projekt $projekt */
            $projekt->setLastOpened(false);
        }
        $currentProjekt->setLastOpened(true);


        return $this->render(
            'Projekte/display_project.html.twig',
            array('adressen' => $person->getAdressen(),
                'projekt' => $currentProjekt,
                'meineRollen' => $person->getPersonenRollenByProjekt($currentProjekt),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );

    }


    /**
     */
    public function addGrundstueckAction(Request $request, $projektId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $projekt */
        $projekt = $projektRepo->find($projektId);
        /** @var Person $person */
        $person = $user->getPerson();
        $grundstueck = new Grundstueck();
        $form = $this->createForm(GrundstueckType::class, $grundstueck);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projekt->setGrundstueck($grundstueck);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($projekt);
            $em->flush();


            return $this->redirectToRoute(
                'display_project_by_project_id',
                array('projektId' => $projektId)
            );
        }

        return $this->render(
            'Projekte/neues_grundstueck_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'projekt' => $projekt,
                'projektId' => $projektId,
                'user' => $user)
        );
    }

    public function addProjektAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $projekt = new Projekt();

        /** @var Person $person */
        $person = $user->getPerson();

        $firmaRepo = $this->getDoctrine()->getRepository('AppBundle:Firma');
        /** @var Firma $privateFirma */
        $privateFirma = $firmaRepo->findOneByName('NoCompnay_' . $person->getId());

        $form = $this->createForm(ProjektType::class, $projekt);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($user->getProjekte() as $otherprojekt) {
                /** @var Projekt $otherprojekt */
                $otherprojekt->setLastOpened(false);
            }

            $projekt->addUser($user);
            $projekt->setOwner($user);
            $projekt->setEinladungscode('xxx');
            $projekt->setLastOpened(true);
            $projekt->addRolle(new Rolle('Bauherr'));
            $projekt->addRolle(new Rolle('Bauherrin'));
            $rolleBesitzer = new Rolle('Besitzer');
            $rolleBesitzer->addFirma($privateFirma);
            $projekt->addRolle($rolleBesitzer);
            $projekt->addRolle(new Rolle('Beobachter'));

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($projekt);
            $em->flush();


            return $this->redirectToRoute(
                'display_project_by_project_id',
                array('projektId' => $projekt->getId())
            );
        }

        return $this->render(
            'Projekte/neues_projekt_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'headline' => 'Personendaten bearbeiten',
                'projekt' => $projekt,
                'user' => $user)
        );
    }


    /**
     */
    public function addHausAction(Request $request, $projektId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $projekt */
        $projekt = $projektRepo->find($projektId);
        /** @var Person $person */
        $person = $user->getPerson();
        $haus = new Haus();
        $form = $this->createForm(HausType::class, $haus);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projekt->setHaus($haus);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($projekt);
            $em->flush();


            return $this->redirectToRoute(
                'display_project_by_project_id',
                array('projektId' => $projektId)
            );
        }

        return $this->render(
            'Projekte/neues_haus_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'projekt' => $projekt,
                'projektId' => $projektId,
                'user' => $user)
        );
    }

    public function displayInternExtrasByProjectIdAction(Request $request, $projektId)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var ProjektRepository $emailRepo */
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $currentProjekt */
        $currentProjekt = $projektRepo->find($projektId);

        /** @var Person $person */
        $person = $user->getPerson();

        $internExtras = $currentProjekt->getRolleByTypKurzname('IE');


        return $this->render(
            'Projekte/display_details_extras_intern.html.twig',
            array('adressen' => $person->getAdressen(),
                'projekt' => $currentProjekt,
                'interneExtras' => $internExtras,
                'meineRollen' => $person->getPersonenRollenByProjekt($currentProjekt),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );

    }
}
