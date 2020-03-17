<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AdRepository $repo, Request $request)
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $ads = $repo->findSearch($data);


        if($form->isSubmitted()){
            
            return $this->render('ad.html.twig', [
                'ads' => $ads
            ]);
            }
        return $this->render('home.html.twig', [
            'ads' => $ads,
            'form' => $form->createView()
        ]);
    }
}
