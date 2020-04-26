<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        return $this->render('dashboard/login.html.twig', [
            'hasError' => $error !== null
        ]);
    }

    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout(){

    }
    
/**
 * permet d'afficher le formulaire d'inscription
 * 
 * @Route("/register", name="account_register")
 *
 * @return Response
 */
public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder,AuthenticationUtils $utils)
{
    $user = new User();
    $error = $utils->getLastAuthenticationError();
    $form = $this->createForm(RegistrationType::class, $user);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid() ){
        $hash = $encoder->encodePassword($user, $user->getHash());
        $user->setHash($hash);
        $manager->persist($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "Votre compte a bien été créé !"
        );
        return $this->redirectToRoute('account_login');
    }

    return $this->render('dashboard/account/registration.html.twig',[
        'form' => $form->createView(),
        'hasError' => $error !== null
    ]);
    }
}
