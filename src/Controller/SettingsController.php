<?php
/**
 * Created by PhpStorm.
 * User: maxime
 * Date: 29/04/2019
 * Time: 00:14
 */

namespace App\Controller;


use App\Form\UserSettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{


    /**
     * @Route("/settings", name="app_settings_index")
     */
    public function index()
    {
        return $this->render('settingsIndex.html.twig', [
            'current_user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/settings/edit", name="app_settings_edit", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(UserSettingsType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_settings_index');
        }

        return $this->render('settingsEdit.html.twig', [
            'current_user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

}