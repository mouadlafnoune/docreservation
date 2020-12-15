<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_index")
     */
    public function UserAll(UserRepository $repo)
    {
        return $this->render('admin/ad/user.html.twig', [
            'users' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/ads", name="admin_ad_index")
     */
    public function AdsAll(AdRepository $repoad)
    {
        return $this->render('admin/ad/adsall.html.twig', [
            'ads' => $repoad->findAll()
        ]);
    }
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        return $this->render('admin/ad/login.html.twig', [
            'hasError' => $error !== null
        ]);
    }
    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logout(){

    }
}