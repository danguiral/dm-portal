<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class SecurityController extends Controller
{
    /**
     * @Route("/sign-up", name="sign_up")
     */
    public function SignUpAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password)
                ->eraseCredentials();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('sign_in');
        }

        return $this->render('AppBundle:Security:sign_up.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sign-in", name="sign_in")
     * @Method("GET")
     */
    public function SignInAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:Security:sign_in.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/sign-out", name="sign_out")
     * @Method("GET")
     */
    public function SignOutAction()
    {

    }

    /**
     * @Route("/sign-in-check", name="sign_in_check")
     * @Method("POST")
     */
    public function SignInCheckAction()
    {

    }
}
