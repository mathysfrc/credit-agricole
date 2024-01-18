<?php

namespace App\Controller;

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('{id}/order/submit', name: 'order_submit_route')]
    public function submitOrder(Request $request, EntityManagerInterface $entityManager, CardRepository $cardRepository, int $id): Response
    {
        // Récupérer les données du formulaire

        $user = $this->getUser();

        $card = $cardRepository->find($id);


        $order = new Order();
        $order->setUser($user);
        $order->setCard($card);

        $order->setDate(new \DateTime());
        

        // Créer le formulaire et le lier à l'entité Order
        $form = $this->createForm(OrderType::class, $order);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez accéder aux données du formulaire à partir de l'entité
            $userId = $order->getUser()->getId();
            $cardId = $order->getCard()->getId();
            $quantity = $order->getQuantity();

            // Vous pouvez également accéder à d'autres données de l'entité Order
            $date = $order->getDate();

            // Enregistrez votre entité Order dans la base de données
            $entityManager->persist($order);
            $entityManager->flush();

            // Rediriger ou effectuer d'autres actions en conséquence
            return $this->redirectToRoute('app_home');
        }

        // Si le formulaire n'est pas valide, affichez-le à nouveau
        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'cards' => $card,
            'card' => $card,
            'order' => $order,
        ]);
    }
}
