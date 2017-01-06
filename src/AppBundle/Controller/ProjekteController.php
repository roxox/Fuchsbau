<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Firma;
use AppBundle\Entity\Grundstueck;
use AppBundle\Entity\Haus;
use AppBundle\Entity\Person;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\Rolle;
use AppBundle\Entity\Rolletyp;
use AppBundle\Entity\User;
use AppBundle\Repository\RolletypRepository;
use AppBundle\Services\KostenService;
use AppBundle\Form\GrundstueckType;
use AppBundle\Form\HausType;
use AppBundle\Form\NewInternalExtraType;
use AppBundle\Form\ProjektType;
use AppBundle\Repository\ProjektRepository;
use AppBundle\Repository\RolleRepository;
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

        /** @var ProjektRepository $projektRepo */
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $currentProjekt */
        $currentProjekt = $projektRepo->find($projektId);

        /** @var KostenService $kostenService */
        $kostenService = $this->get('fuchsbau.kosten');
        $gesamtkosten = $kostenService->kostenBerechnenGesamt($currentProjekt);

        /** @var RolletypRepository $rolletypRepo */
        $rolletypRepo = $this->getDoctrine()->getRepository('AppBundle:Rolletyp');

        /** @var Rolletyp $rolletyp */
        $rolletypInternesExtra = $rolletypRepo->findOneByKurzname('IE');
        $kostenInterneExtras = $kostenService->kostenBerechnenByType($currentProjekt, [$rolletypInternesExtra]);
        $hauskosten = $kostenInterneExtras + $currentProjekt->getHauskaufpreis();
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
                'headline' => "# Projektinformationen",
                'gesamtkosten' => $gesamtkosten,
                'interneKosten' => $kostenInterneExtras,
                'hauskosten' => $hauskosten,
                'boldheadline' => "für: " . $currentProjekt->getName(),
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
        $firmaRepo = $this->getDoctrine()->getRepository('AppBundle:Firma');
        $rolleTypRepo = $this->getDoctrine()->getRepository('AppBundle:Rolletyp');

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $projekt = new Projekt();

        /** @var Person $person */
        $person = $user->getPerson();

        /** @var Firma $privateFirma */
        $privateFirma = $firmaRepo->findOneByName('NoCompnay_' . $person->getId());
        /** @var Rolletyp $rolleTyp */
        $rolleTyp = $rolleTypRepo->findOneBy(array('name' => 'Person'));

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

            $bauherr = new Rolle();
            $bauherr->setName('Bauherr');
            $bauherr->setRolletyp($rolleTyp);
            $projekt->addRolle($bauherr);

            $bauherrin = new Rolle();
            $bauherrin->setName('Bauherrin');
            $bauherrin->setRolletyp($rolleTyp);
            $projekt->addRolle($bauherrin);

            $besitzer = new Rolle();
            $besitzer->setName('Besitzer');
            $besitzer->setRolletyp($rolleTyp);
            $besitzer->addFirma($privateFirma);
            $projekt->addRolle($besitzer);

            $beobachter = new Rolle();
            $beobachter->setName('Beobachter');
            $beobachter->setRolletyp($rolleTyp);
            $projekt->addRolle($beobachter);


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


    /**
     */
    public function editHausAction(Request $request, $projektId, $hausId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $hausRepo = $this->getDoctrine()->getRepository('AppBundle:Haus');

        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $projekt */
        $projekt = $projektRepo->find($projektId);
        /** @var Person $person */
        $person = $user->getPerson();

        $haus = $hausRepo->find($hausId);
        $form = $this->createForm(HausType::class, $haus);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($haus);
            $em->flush();


            return $this->redirectToRoute(
                'display_project_by_project_id',
                array('projektId' => $projektId)
            );
        }

        return $this->render(
            'Projekte/edit_haus_content_modal.html.twig',
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



    /**
     */
    public function addInternalExtraAction(Request $request, $projektId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $rolleTypRepo = $this->getDoctrine()->getRepository('AppBundle:Rolletyp');
        /** @var Rolletyp $rolleTyp */
        $rolleTyp = $rolleTypRepo->findOneBy(array('name' => 'InternExtra'));
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $projekt */
        $projekt = $projektRepo->find($projektId);
        /** @var Person $person */
        $person = $user->getPerson();
        $internalExtra = new Rolle();
        $internalExtra->setRolletyp($rolleTyp);
        $form = $this->createForm(NewInternalExtraType::class, $internalExtra);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projekt->addRolle($internalExtra);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($projekt);
            $em->flush();


            return $this->redirectToRoute(
                'display_internal_extra',
                array('projektId' => $projektId, 'internalExtraId' => $internalExtra->getId())
            );
        }

        return $this->render(
            'Projekte/display_parts/house/internal_extras/popups/popup_internal_extra_new_content.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'projekt' => $projekt,
                'projektId' => $projektId,
                'headline' => "# Projektinfos",
                'boldheadline' => "\tfür: " . $internalExtra->getName(),
                'user' => $user)
        );
    }

    public function displayInternalExtraAction(Request $request, $projektId, $internalExtraId)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var RolleRepository $rolleRepo */
        $rolleRepo = $this->getDoctrine()->getRepository('AppBundle:Rolle');
        /** @var Rolle $currentInternalExtra */
        $currentInternalExtra = $rolleRepo->find($internalExtraId);


        /** @var ProjektRepository $projektRepo */
        $projektRepo = $this->getDoctrine()->getRepository('AppBundle:Projekt');
        /** @var Projekt $currentProjekt */
        $currentProjekt = $projektRepo->find($projektId);

        /** @var Person $person */
        $person = $user->getPerson();

        $internExtras = $currentProjekt->getRolleByTypKurzname('IE');

        return $this->render(
            'Projekte/display_parts/house/internal_extras/details/display.html.twig',
            array('adressen' => $person->getAdressen(),
                'projekt' => $currentProjekt,
                'internalExtra' => $currentInternalExtra,
                'interneExtras' => $internExtras,
                'meineRollen' => $person->getPersonenRollenByProjekt($currentProjekt),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => "# Detailansicht für",
                'boldheadline' => $currentInternalExtra->getName(),
                'user' => $user)
        );

    }
}
