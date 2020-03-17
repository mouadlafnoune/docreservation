<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo, Request $request)
    {
        /** 
         * extraire tous les donnée de la DB et stocké dans $ads
         * ads utilisé dans twig
         */
      /*  $ads = $repo->findAll();*/

      $data = new SearchData();
      $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
      $ads = $repo->findSearch($data);
       

        return $this->render('ad.html.twig', [
            'ads' => $ads,
            
        ]);
    }
   

/**
 * @Route("/ads/new", name="ads_create")
 */
public function createad(Request $request, EntityManagerInterface  $manager){
    $ad = new Ad(); 
    $form = $this->createForm(AdType::class, $ad);
    $form->handleRequest($request);

   

    if($form->isSubmitted() && $form->isValid()){
        $manager->persist($ad);
        $manager->flush();

 
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getName()}</strong> a bien été ajouté !"
        );

        return $this->redirectToRoute('ads_show',[
            'slug' => $ad->getSlug()
        ]);
    }

    return $this->render('dashboard/newad.html.twig',[
        'form' => $form->createView()
    ]);
}

/**
 * @Route("/ads/{slug}/edit", name="edit_ad")
 */
public function editad(Ad $ad, Request $request, EntityManagerInterface  $manager){

    $form = $this->createForm(AdType::class,$ad);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $manager->persist($ad);
        $manager->flush();

 
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getName()}</strong> a bien été modifiée !"
        );

        return $this->redirectToRoute('ads_show',[
            'slug' => $ad->getSlug()
        ]);
    }

   return $this->render('dashboard/editad.html.twig',[
       'form' => $form->createView(),
       'ad' => $ad
   ]); 
}



/**
 * @Route("/ads/{slug}", name="ads_show")
 */
    public function show(Ad $ad){
        return $this->render('show.html.twig',[
            "ad" => $ad
        ]);
    }


}


