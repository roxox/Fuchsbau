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
    private $save = false;
    const FORM_NAMESPACE = 'AppBundle\Form\\';


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

    public function updateCurrentPersonAction(Request $request)
    {
        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
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
            'Personen/update_person.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    public function updateCurrentUserAction(Request $request)
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
            return $this->redirectToRoute("display_current_person");
        }

        return $this->render(
            'Personen/update_user.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    // TELEFON
    public function addPhoneAction(Request $request)
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
            'Personen/new_phone.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    public function editPhoneAction(Request $request, $phoneNumberId)
    {

        // 1) build the form
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $telefonRepo = $this->getDoctrine()->getRepository('AppBundle:Telefonnummer');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Telefonnummer $phoneNumber */
        $phoneNumber = $telefonRepo->find($phoneNumberId);
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
            'Personen/update_phone.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    public function removePhoneAction(Request $request, $phoneNumberId)
    {

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $telefonRepo = $this->getDoctrine()->getRepository('AppBundle:Telefonnummer');
        /** @var Person $person */
        $person = $user->getPerson();
        /** @var Telefonnummer $phoneNumber */
        $phoneNumber = $telefonRepo->find($phoneNumberId);
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

    // ADRESSEN
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
            'Personen/new_address.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

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
            'Personen/update_address.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

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


    // EMAIL
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
//
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
            'Personen/new_email.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    // MODAL POPUPS

    public function addEmailModalAction(Request $request)
    {

        // 1) build the form
        $user = $this->getUser();
//        if (!is_object($user) || !$user instanceof UserInterface) {
//            throw new AccessDeniedException('This user does not have access to this section.');
//        }
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
            'Personen/new_email_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }
    public function saveMe(){
        echo 'abc';
    }



    // ADRESSEN
    public function addAddressModalAction(Request $request)
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
            'Personen/new_address_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    public function editAddressModalAction(Request $request, $addressId)
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
            'Personen/update_address_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'adresse' => $address,
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }

    public function addEmailPornoModalAction(Request $request, string $emailaddress = null)
    {

        // 1) build the form
        $user = $this->getUser();
//        if (!is_object($user) || !$user instanceof UserInterface) {
//            throw new AccessDeniedException('This user does not have access to this section.');
//        }
        /** @var Person $person */
        $person = $user->getPerson();
        $email = new EmailCompany();
        $email->setEmailadresse($emailaddress);
        $form = $this->createForm(EmailCompanyType::class, $email);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $mail = new Email();
//            $mail->setEmailadresse($emailaddress);

//            $person->addEmailadresse($email);
            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute("display_current_person");
        }
        return $this->render(
            'Personen/new_email_content_modal.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'headline' => 'Personendaten bearbeiten',
                'user' => $user)
        );
    }
}