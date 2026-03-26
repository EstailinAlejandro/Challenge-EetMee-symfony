<?php

namespace App\Controller;

use App\Entity\Box;
use App\Entity\Food;
use App\Entity\Temperature;
use App\Entity\User;
use App\Form\FoodType;
use App\Form\RegistrationFormType;
use App\Repository\BoxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(EntityManagerInterface $em): Response
    {
        $temperature = $em->getRepository(Temperature::class)->findBy([], ['dateTime' => 'DESC'], 1);
        $temperature = $temperature ? $temperature[0] : null;

        return $this->render('user/index.html.twig', [
            'temperature' => $temperature,
        ]);
    }

    #[Route('/user/deliver', name: 'app_deliver')]
    public function deliver(Request $request, EntityManagerInterface $entityManager, BoxRepository $boxRepository): Response {
        $box = $boxRepository->find(1);
        $isFull = false;

        // ❌ Box is vol → geen form
        if ($box->isFull()) {
            $isFull = true;
        }

        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $food->setUserDeliver($this->getUser());
            $food->setBox($box);

            $box->setPickupRequested(true);
            $box->setIsOpen(true);

            $entityManager->persist($food);
            $entityManager->flush();

            $this->addFlash('success', 'Eten is toegevoegd!');

            return $this->redirectToRoute('index');
        }

        return $this->render('user/deliver.html.twig', [
            'form' => $form,
            'boxFull' => $isFull,
        ]);
    }

    #[Route('/user/pickup', name: 'app_pickup')]
    public function pickupPage(): Response
    {
        return $this->render('user/pickup.html.twig');
    }

    #[Route('/user/pickup/open', name: 'app_pickup_open')]
    public function pickupOpen(EntityManagerInterface $em): Response
    {
        $box = $em->getRepository(Box::class)->find(1);
        $lastFood = $em->getRepository(Food::class)
            ->findOneBy([], ['id' => 'DESC']);


        $box->setPickupRequested(false);
        $box->setIsOpen(true);

        $lastFood->setPickupUser($this->getUser());
        $em->flush();

        $this->addFlash('success', 'Eten is meegenomen!');

        return $this->redirectToRoute('app_user');
    }


}
