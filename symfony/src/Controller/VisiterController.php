<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VisiterController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        //wanneer je de rol 'ROLE_STUDENT' hebt wordt de student pagina getoond.
        if ($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('app_user');
        }
        //wanneer je de rol 'ROLE_TEACHER' hebt wordt de teacher pagina getoond.
        if ($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin');
        }

        return $this->render('visiter/index.html.twig', [
            'controller_name' => 'VisiterController',
        ]);
    }

    #[Route('/overOns', name: 'app_over_ons')]
    public function overOns(): Response
    {
        return $this->render('visiter/overons.html.twig', [
            'controller_name' => 'VisiterController',
        ]);
    }

}
