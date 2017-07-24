<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TestDefaultController extends Controller
{
    /**
     * @param Request $request
     * @Route("/testuser", name="testuser")
     * @return Response
     */
    public function loginAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());


        $serializer = new Serializer($normalizers, $encoders);

        echo $request->getContent();
        $decoded = json_decode($request->getContent(), true);
        $username =  $decoded["username"];
        $password =  $decoded["password"];



        if (!$username || !$password) {
            return 'login.input_missing';
        }

        $user_manager = $this->get('fos_user.user_manager');

        /** @var User $user */
        $user = $user_manager->findUserByUsernameOrEmail($username);
        if ($user == null) {
            return new Response("Wrong credentials");
        }

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        $bool = ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) ? true : false;
        if ($bool !== true) {
            return 'login.invalid';
        }

        echo $serializer->serialize($user->getPerson(), 'json');

        return  new Response($user->getEmail());
    }
}
