<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\CardRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/basket')]
class BasketController extends AbstractController
{

    #[Route('/', name: 'app_basket')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        CardRepository $cardRepository,
    ): Response {

        // Récupérez les données stockées dans la session
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $items = array();
        $cards = $cardRepository->findAll();
        $baskets = $user->getBaskets();
        foreach ($baskets as $basket) {
            array_push($items, $basket->getItem());
            dump($basket->getItem());
        }

        return $this->render('basket/index.html.twig', [
            'controller_name' => 'BasketController',
            'user' => $user,
            'items' => $items,
            'cards' => $cards,
            'basket' => $baskets
        ]);
    }

    #[Route('/confirmation', name: 'app_basket_confirmation')]
    public function confirmation(
        Request $request,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        CardRepository $cardRepository,
    ): Response {

        // Récupérez les données stockées dans la session
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $items = array();
        $cards = $cardRepository->findAll();
        $baskets = $user->getBaskets();
        foreach ($baskets as $basket) {
            array_push($items, $basket->getItem());
        }





        return $this->render('basket/confirmation.html.twig', [
            'controller_name' => 'BasketController',
            'user' => $user,
            'items' => $items,
            'cards' => $cards,
        ]);
    }

    #[Route('/clear', name: 'app_basket_clear')]
    public function clear(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        // Récupérez l'utilisateur actuel
                /** @var \App\Entity\User $user */

        $user = $this->getUser();
    
        // Vérifiez si l'utilisateur est connecté
        if ($user) {
            // Récupérez les paniers associés à l'utilisateur
            $baskets = $user->getBaskets();
    
            // Supprimez les paniers de l'utilisateur
            foreach ($baskets as $basket) {
                $entityManager->remove($basket);
            }
    
            // Exécutez les suppressions
            $entityManager->flush();
        }
    
        // Videz également le panier dans la session
        $session->set('basket', []);
    
        // Redirigez l'utilisateur vers la page du panier
        $redirectUrl = $this->generateUrl('app_basket');
        return new RedirectResponse($redirectUrl);
    }


    #[Route('/delete/{id}', name: 'app_basket_delete')]
    public function delete($id, EntityManagerInterface $entityManager, ItemRepository $itemRepository)
    {
        // Récupérez l'objet Item à partir de l'identifiant
        $item = $itemRepository->find($id);

        // Vérifiez si l'objet Item existe
        if (!$item) {
            throw $this->createNotFoundException('Item not found');
        }

        // Supprimez l'objet Item
        $entityManager->remove($item);
        $entityManager->flush();

        // Redirigez l'utilisateur vers la page du panier
        $redirectUrl = $this->generateUrl('app_basket');

        return new RedirectResponse($redirectUrl);
    }
}
