<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Person;
use AppBundle\Entity\User;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use AppBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class AdressenController extends Controller

{


    const FORM_NAMESPACE = 'AppBundle\Form\\';
//    /**
//     * @Route("/listByCurrentPerson", name="list_adressen_by_current_person")
//     */
    public function currentPersonListAdressenAction(Request $request)
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
            'Adressen/list.html.twig',
            array('adressen' => $person->getAdressen(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );

    }

    public function updateCurrentPersonAction(Request $request)
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
        $form = $this->createForm(static::FORM_NAMESPACE . 'PersonType');

        $form->handleRequest($request);

        /** @var Person $person */
        $person = $user->getPerson();
        $form->setData($person);

        return $this->render(
            'Adressen/update_user.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );
    }

    public function updateCurrentUserAction(Request $request)
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
        $form = $this->createForm(static::FORM_NAMESPACE . 'ProfileFormType');

        $form->handleRequest($request);

        /** @var Person $person */
        $person = $user->getPerson();
        $form->setData($user);

        return $this->render(
            'Adressen/update_user.html.twig',
            array('adressen' => $person->getAdressen(),
                'form' => $form->createView(),
                'telefonnummern' => $person->getTelefonnummern(),
                'emails' => $person->getEmailadressen(),
                'person' => $person,
                'user' => $user)
        );
    }
}