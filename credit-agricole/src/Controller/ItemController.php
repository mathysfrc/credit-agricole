<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Item;
use App\Form\CardType;
use App\Form\ItemType;
use App\Form\UserType;
use App\Repository\CardRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index')]
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    
    #[Route('/{cardId}/create', name: 'app_item_create')]
    public function create(
        Request $request,
        CardRepository $cardRepository,
        int $cardId,
        ItemRepository $itemRepository,
        EntityManagerInterface $entityManager,
    ): Response {

        // On check avant la création de la carte
        $user = $this->getUser();
        $card = $cardRepository->find($cardId);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        if (!$card) {
            throw $this->createNotFoundException('Carte non trouvé');
        }

        
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // Traitement des données du formulaire ici
            $item = $form->getData();
            $item->setCard($card);
            $entityManager->persist($item);
            $entityManager->flush();

            // Désormais, on fait le lien avec le panier
            $basket = new Basket();
            $basket->setUser($user);
            $basket->setItem($item);
            $basket->setLastUpdate(new DateTime);
            $entityManager->persist($basket);
            $entityManager->flush();
            
            $this->addFlash('success', 'Vous avez ajouté un article dans votre panier');


            // Redirigez vers le contrôleur suivant
            return $this->redirectToRoute('app_basket');
        }

    
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
            'card' => $card,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
 
    
}
