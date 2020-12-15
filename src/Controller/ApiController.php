<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ad;
use App\Entity\Calendar;
use App\Entity\Reservation;
use App\Repository\CalendarRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    /**
     * @Route("/docTimes", name="doctimes")
     */
    public function docTimes(CalendarRepository $calendar){
        $events = $calendar->findAll();
        $rdvs = [];
        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i'),
                'end' => $event->getEnd()->format('Y-m-d H:i'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }
        $data = json_encode($rdvs);
        return $this->render('calendar/doctimes.html.twig', compact('data'));
    }


     /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(Calendar $calendar, Request $request)
    {

        // On récupere les données
        $donnees = json_decode($request->getContent());
        if(
            isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
        ){
            // Les données sont complètes
            $code = 200;
            // On vérifier si l'id existe
            if(!$calendar){
                // On instancie un rendez-vous
                $calendar = new Calendar;
                //On change le code;
                $code = 201; 
            }
            // On hydrate l'objet avec les données
            $calendar->setTitle($donnees->title);
            $calendar->setDescription($donnees->description);
            $calendar->setStart(new DateTime($donnees->start));
            if($donnees->allDay){
                $calendar->setEnd(new DateTime($donnees->start));
            }else{
                $calendar->setEnd(new DateTime($donnees->end));
            }
            $calendar->setAllDay($donnees->allDay);
            $calendar->setBackgroundColor($donnees->backgroundColor);
            $calendar->setBorderColor($donnees->borderColor);
            $calendar->setTextColor($donnees->textColor);

            $em = $this->getDoctrine()->getManager();
            $em->persist($calendar);
            $em->flush();

            return new Response('Ok', $code);
        }else{
            // Les connées sont incomplètes
            return new Response('Données incomplètes');
        } 

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }


    /**
     * @Route("/ajax/showTimes", name="show_times_ajax", methods={"GET"})
     */
    public function showTimes(Request $request,CalendarRepository $calendarrepo,ReservationRepository $reporeservation): Response
    {
        $dater = $request->query->get('date');
        $id = $request->query->get('id');
        //dd($date);
        $date = \DateTime::createFromFormat('Y-m-d', $dater);
        $times = $calendarrepo->findBy(["date"=>$date,"ad"=>(int)$id]);
        //dd($times);
        $reservations = $reporeservation->findBy(["ad"=>(int)$id]);
        //dd($reservations);

        
        $array = [];
        $dateD = [];
        $disponible = "";
        foreach ($times as $time)
        {
            $disponible = "";
            
            foreach($reservations as $reservation)
            {
                if($time->getStart() == $reservation->getStartFrom())
                {

                    $disponible = "disabled";
                
                }
            }
            $dateD = [
                "date"=>[
                    "time"=>$time->getStart()->format('H:i'),
                    "year"=>$time->getStart()->format('Y'),
                    "month"=>$time->getStart()->format('m'),
                    "day"=>$time->getStart()->format('d'),
                ],
                "disponible"=>$disponible
                ];



            array_push($array,$dateD);
        }
        //dd($array);

        $response = new Response();
        $response->setContent(json_encode($array));

$response->headers->set('Content-Type', 'application/json');



        return $response;

    }

     /**
      * @Route("/safoine/safoine-reservation/{slug}", name="safoine-reservation", methods={"GET","POST"})
      * @return Response
      */
     public function safoineReservation(Request $request, Ad $ad)
     {

         $date = $request->query->get('date');
        
        
         $debut = $request->query->get('debut');
         //dd($date .":". $debut);
         //$endtime = $request->query->get('endtime');
        
                $em = $this->getDoctrine()->getManager();
               
               
                $date = date_create_from_format('Y-m-d', $date);
                $debut = date_create_from_format('H:i', $debut);
                //$endtime = date_create_from_format('H:i', $endtime);
               
                //   if($this->getUser() == null){
                //      $this->addFlash("warning","Vous devez s'authentifier pour avoir réserver !");
                //      return $this->redirectToRoute('ads_show',["slug"=>$ad->getSlug()]);
                //  }
               
                 $makepro = new Reservation();
                 $user = $this->getUser();
                 $makepro->setUser($user);
                 $makepro->setStartFrom($debut);
                 $makepro->setDate($date);
    
                 $makepro->setAd($ad);
                 $em->persist($makepro);
                 $em->flush();
            
                 return $this->redirectToRoute('ads_show',["slug"=>$ad->getSlug()]);
     }

}