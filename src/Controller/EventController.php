<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\AvailabilityRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event")
 */
class EventController extends AbstractController
{

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'current_user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     * @param Event $event
     * @param UserRepository $userRepository
     * @return Response
     */
    public function show(Event $event, UserRepository $userRepository, AvailabilityRepository $availabilityRepository): Response
    {

        // Build the list of players not available
        $users = $userRepository->findAll();
        $usersNotAvailable = array();
        $event->getAvailabilities();
        foreach ($users as $user) {
            $available = false;
            foreach ($user->getAvailabilities() as $availability) {
                if ($availability->getEvent()->getId() == $event->getId()) {
                    $available = true;
                }
            }
            if (!$available) {
                array_push($usersNotAvailable, $user);
            }
        }

        // Get current availability
        $current_availability = null;
        foreach ($event->getAvailabilities() as $availability) {
            if ($availability->getPlayer()->getId() == $this->getUser()->getId()) {
                $current_availability = $availability;
            }
        }

        // Rendering
        return $this->render('event/show.html.twig', [
            'event' => $event, // Current event and current players available by nested
            'current_user' => $this->getUser(), // Current user
            'current_user_available' => $current_availability,
            'users_not_available' => $usersNotAvailable // List of players not available
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANAGER');
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_index', [
                'id' => $event->getId(),
            ]);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'current_user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_index');
    }


}
