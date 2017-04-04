<?php

namespace AppBundle\Controller;

use AppBundle\Entity\jsonContact;
use AppBundle\Entity\Person;
use AppBundle\Entity\Projekt;
use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use JsonMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TestDefaultController extends Controller
{
    /**
     * @Route("/testuser", name="testuser")
     */
    public function loginAction(Request $request)
    {
        $echo = "test";
        /** @var Response $response */


        $user_manager = $this->get('fos_user.user_manager');
                    if (!$request->request->has('username') || !$request->request->has('password')) {
            return  new Response("hallo1");
        }
        
        /** @var User $user */
        $user = $user_manager->findUserByUsernameOrEmail($request->request->get("username"));
        if ($user == null) {
            return  new Response("hallo2");
        }

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        $bool = ($encoder->isPasswordValid($user->getPassword(), $request->request->get("password"), $user->getSalt())) ? true : false;
        if ($bool !== true) {
            return  new Response("hallo3");
        }

        return  new Response("hallo");
    }
}
