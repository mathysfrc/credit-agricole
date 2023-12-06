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
    public function index(Request $request, UserRepository $userRepository, CardRepository $cardRepository): Response
    {
        $id = $request->request->get('id');
        $users = $userRepository->findOneBy(['id' => $id]);

        $cards = $cardRepository->findAll();


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'users' => $users,
            'cards' => $cards,
        ]);
    }
}
