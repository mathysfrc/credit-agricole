<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsController extends AbstractController
{

    #[Route('{id}/details', name: 'app_details')]
    public function index(Request $request, CardRepository $cardRepository, int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $card = $cardRepository->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement des données du formulaire ici
            $formData = $form->getData();


            // Ajoutez les données au panier ou effectuez toute autre action nécessaire

            // Redirigez ou affichez une page de confirmation
            return $this->redirectToRoute('#');
        }

        return $this->render('details/index.html.twig', [
            'controller_name' => 'DetailsController',
            'card' => $card,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
