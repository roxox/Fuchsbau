<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Email;
use AppBundle\Entity\Person;
use AppBundle\Entity\Telefonnummer;
use AppBundle\Entity\User;
use AppBundle\Form\AdresseType;
use AppBundle\Form\EmailCompanyType;
use AppBundle\Form\EmailType;
use AppBundle\Form\PersonType;
use AppBundle\Form\ProfileFormType;
use AppBundle\Form\TelefonnummerType;
use AppBundle\PlainClasses\EmailCompany;
use AppBundle\Repository\EmailRepository;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class PersonenController extends Controller
{
    const FORM_NAMESPACE = 'AppBundle\Form\\';

    /**
     * Overview
     */

    /**
     * displayCurrentPersonAction
     *
     * display the user data overview
     *
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function displayCurrentPersonAction(Request $request)
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var Person $person */
        $person = $user->getPerson();

        return $this->render(
            'Personen/display.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten anzeigen',
                'user' => $user)
        );

    }

    /**
     * User
     */

    /**
     * editCurrentUserAction
     *
     * edit current user
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCurrentUserAction(Request $request)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        // 1) build the form
        /** @var User $user */
        $user = $this->getUser();
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var EmailRepository $emailRepo */
        $emailRepo = $this->getDoctrine()->getRepository('AppBundle:Email');
        /** @var Email $email */
        $email = $emailRepo->findOneByEmailadresse($user->getEmail());
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $form = $this->createForm(ProfileFormType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) save the User!
            $email->setEmailadresse($user->getEmail());
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);

            $userManager->updateUser($user);
            return $this->redirectToRoute("edit_current_user");
        }

        return $this->render(
            'Personen/display_parts/left/popups/user/popup_user_edit_content.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    /**
     * Person
     */

    /**
     * editCurrentPersonAction
     *
     * edit current person
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCurrentPersonAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var Person $person */
        $person = $user->getPerson();
        $form = $this->createForm(PersonType::class, $person);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();


            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/left/popups/person/popup_person_edit_content.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    /**
     * Addresses
     */

    /**
     * addAddressAction
     *
     * add a new address
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAddressAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var Person $person */
        $person = $user->getPerson();
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person->addAdresse($adresse);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/center/popups/address/popup_address_new_content.html.twig',
            array('form' => $form->createView(),
                'headline' => 'Personendaten bearbeiten')
        );
    }

    /**
     * editAddressAction
     *
     * edits selected address
     *
     * @param Request $request
     * @param $addressId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAddressAction(Request $request, $addressId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $addressRepo = $this->getDoctrine()->getRepository('AppBundle:Adresse');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Adresse $adress */
        $address = $addressRepo->find($addressId);
        $form = $this->createForm(AdresseType::class, $address);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();


            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/center/popups/address/popup_address_edit_content.html.twig',
            array('adresse' => $address,
                'form' => $form->createView(),
                'headline' => 'Personendaten bearbeiten',)
        );
    }

    /**
     * removeAddressAction
     *
     * removes selected address
     *
     * @param Request $request
     * @param $addressId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeAddressAction(Request $request, $addressId)
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $addressRepo = $this->getDoctrine()->getRepository('AppBundle:Adresse');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Adresse $address */
        $address = $addressRepo->find($addressId);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        $this->redirectToRoute("display_current_person");

        return $this->render(
            'Personen/display.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    /**
     * Telephone
     */

    /**
     * addTelephoneAction
     *
     * add a new telephone
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addTelephoneAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var Person $person */
        $person = $user->getPerson();
        $telefonnummer = new Telefonnummer();
        $form = $this->createForm(TelefonnummerType::class, $telefonnummer);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person->addTelefonnummer($telefonnummer);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();


            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/right/popups/telephone/popup_telephone_new_content.html.twig',
            array('form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'headline' => 'Personendaten bearbeiten')
        );
    }

    /**
     * editTelephoneAction
     *
     * edits selected telephone
     *
     * @param Request $request
     * @param $telephoneId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editTelephoneAction(Request $request, $telephoneId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $telefonRepo = $this->getDoctrine()->getRepository('AppBundle:Telefonnummer');
        /** @var Telefonnummer $phoneNumber */
        $phoneNumber = $telefonRepo->find($telephoneId);
        $form = $this->createForm(TelefonnummerType::class, $phoneNumber);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($phoneNumber);
            $em->flush();

            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/right/popups/telephone/popup_telephone_edit_content.html.twig',
            array('telephone' => $phoneNumber,
                'form' => $form->createView(),
                'headline' => 'Personendaten bearbeiten')
        );
    }

    /**
     * removeTelephoneAction
     *
     * removes selected telephone
     *
     * @param Request $request
     * @param $telephoneId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeTelephoneAction(Request $request, $telephoneId)
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $telefonRepo = $this->getDoctrine()->getRepository('AppBundle:Telefonnummer');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Telefonnummer $phoneNumber */
        $phoneNumber = $telefonRepo->find($telephoneId);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($phoneNumber);
        $em->flush();

        return $this->render(
            'Personen/display.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    /**
     * Email
     */

    /**
     * addEmailAction
     *
     * add a new email
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addEmailAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var Person $person */
        $person = $user->getPerson();
        $email = new Email();
        $form = $this->createForm(EmailType::class, $email);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $person->addEmailadresse($email);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();


            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/right/popups/email/popup_email_new_content.html.twig',
            array('form' => $form->createView(),
                'emails' => $person->getEmailadressen(),
                'headline' => 'Personendaten bearbeiten')
        );
    }

    /**
     * editEmailAction
     *
     * edits selected email
     *
     * @param Request $request
     * @param $emailId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editEmailAction(Request $request, $emailId)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $emailRepo = $this->getDoctrine()->getRepository('AppBundle:Email');
        /** @var Email $email */
        $email = $emailRepo->find($emailId);
        $form = $this->createForm(EmailType::class, $email);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/display_parts/right/popups/email/popup_email_edit_content.html.twig',
            array('email' => $email,
                'form' => $form->createView(),
                'headline' => 'Personendaten bearbeiten')
        );
    }

    /**
     * removeEmailAction
     *
     * removes selected email
     *
     * @param Request $request
     * @param $emailId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeEmailAction(Request $request, $emailId)
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $emailRepo = $this->getDoctrine()->getRepository('AppBundle:Email');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Email $email */
        $email = $emailRepo->find($emailId);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();

        return $this->render(
            'Personen/display.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }
}