<?php

namespace App\Controller;

use App\Entity\Availability;
use App\Entity\Event;
use App\Form\AvailabilityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/availability", name="app_availability_")
 */
class AvailableController extends AbstractController
{

    /**
     * @Route("/set/{id}", name="set", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function set(Request $request, Event $event): Response
    {
        $availability = new Availability();
        $availability->setEvent($event);
        $availability->setPlayer($this->getUser());
        $form = $this->createForm(AvailabilityType::class, $availability);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si NO! alors on fait rien
            if ($availability->getStatus()->getId() != 4) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($availability);
                $entityManager->flush();
            }

            return $this->redirectToRoute('event_show', [
                'id' => $event->getId()
            ]);
        }

        return $this->render('availabilitySet.html.twig', [
            'event' => $event,
            'current_user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Availability $availability
     * @return Response
     */
    public function edit(Request $request, Availability $availability): Response
    {
        if ($availability->getPlayer()->getId() == $this->getUser()->getId()) {

            $form = $this->createForm(AvailabilityType::class, $availability);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                if ($availability->getStatus()->getId() == 4) { // If NO! => delete
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($availability);
                    $entityManager->flush();
                } else { // else => save
                    $entityManager->flush();
                }

                return $this->redirectToRoute('event_show', [
                    'id' => $availability->getEvent()->getId(),
                ]);
            }

            return $this->render('availabilityEdit.html.twig', [
                'availability' => $availability,
                'current_user' => $this->getUser(),
                'form' => $form->createView(),
            ]);

        } else {

            return $this->redirectToRoute('event_show', [
                'id' => $availability->getEvent()->getId(),
            ]);
        }
    }
}
