<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Card;
use App\Repository\CardRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CardRepository $cardRepository): Response
    {
                /** @var \App\Entity\User $user */

        $user = $this->getUser();

        $cards = $cardRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'cards' => $cards,
            'isVerified' => $user ? $user->isVerified() : false, // Adjust accordingly based on your user entity
        ]);
    }
}
