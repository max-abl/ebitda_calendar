<?php
/**
 * Created by PhpStorm.
 * User: maxime
 * Date: 27/04/2019
 * Time: 22:51
 */

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     *
     * @Route("/", name="app_index")
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EventRepository $eventRepository, UserRepository $userRepository)
    {
        return $this->render('index.html.twig', [
            'current_user' => $this->getUser(),
            'future_events' => $eventRepository->findFutureEvent(),
            'past_events' => $eventRepository->findPassedEvent(),
            'users' => $userRepository->findAll()
        ]);
    }

}