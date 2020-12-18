<?php

namespace App\Controller;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
    /**
     * @Route("/", name="calendar_index", methods={"GET"})
     */
    public function index(CalendarRepository $calendarRepository): Response
    {
        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendarRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="calendar_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $date = $calendar->getStart()->format('Y-m-d');

            $calendar->setDate(\DateTime::createFromFormat('Y-m-d', $date));
            /////////////////////
             $dateStart = $calendar->getStart();
             $dateEnd = $calendar->getEnd();
             //$userId = $calendar->getAd();
             $nbr = $calendar->getDecalageHoraire();

             while($dateStart < $dateEnd)
             {
                $calendar1 = new Calendar();

                $calendar1->setTitle($calendar->getTitle());
                $calendar1->setDescription($calendar->getDescription());
                $calendar1->setAllDay($calendar->getAllDay());
                $calendar1->setBackgroundColor($calendar->getBackgroundColor());
                $calendar1->setBorderColor($calendar->getBorderColor());
                $calendar1->setTextColor($calendar->getTextColor());
                $calendar1->setAd($calendar->getAd());
                $calendar1->setDate($calendar->getDate());
                $calendar1->setEnd($calendar->getEnd());
                $calendar1->setDecalageHoraire($calendar->getDecalageHoraire());

                    $calendar1->setStart($dateStart);

                    $entityManager->persist($calendar1);

                    $entityManager->flush();
                    
                    $calendar1->setStart($dateStart->modify("+$nbr minutes"));
                    
                    
            }
             
            return $this->redirectToRoute('doctimes');
        }

        return $this->render('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_show", methods={"GET"})
     */
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="calendar_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendar $calendar): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('calendar_index');
        }

        return $this->render('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="calendar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Calendar $calendar): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calendar->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($calendar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('calendar_index');
    }

    
}