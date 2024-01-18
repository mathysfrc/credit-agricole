<?php

namespace App\Controller;

use App\Entity\Basket;
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
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;
use TCPDF;

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
        ItemRepository $itemRepository
    ): Response {
    
        // Récupérez les données stockées dans la session
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $items = array();
        $cards = $cardRepository->findAll();
        $baskets = $user->getBaskets();
    
        // ...
    
        // Handle the "Ajouter au panier" button click
        if ($request->isMethod('POST')) {
            // Récupérez les données du formulaire
            $formData = $request->request->get('item_form');
    
            // Créez une nouvelle instance de Basket
            $basket = new Basket();
            $basket->setUser($user);
            // Ajoutez d'autres informations nécessaires à la basket

    
            // Persistez la basket en base de données
            $entityManager->persist($basket);
            $entityManager->flush();
    
            // Redirigez l'utilisateur vers la page de confirmation
            return $this->redirectToRoute('app_basket_confirmation');
        }
    }

    #[Route('/clear', name: 'app_basket_clear')]
    public function clear(EntityManagerInterface $entityManager)
    {
        // Récupérez l'utilisateur actuel
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est connecté
        if ($user) {
            // Mise à jour : supprimez le lien entre l'utilisateur et les paniers sans les supprimer réellement
            $user->setBaskets([]);

            // Exécutez la mise à jour
        }

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

    #[Route('/save-image', name: 'save_image')]
    public function savePdf(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!empty($data['imageData'])) {
            $imageData = $data['imageData'];

            $image_parts = explode(";base64", $imageData);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode( $image_parts[1] );

            $pdfFilePath = $this->getParameter('kernel.project_dir') . '/public/pdf/' . uniqid('canvas_', true) . '.png';

            file_put_contents($pdfFilePath, $image_base64);
            
            return new JsonResponse(['message' => 'PDF saved successfully', 'pdf_path' => $pdfFilePath]);
        }

        return new JsonResponse(['error' => 'Invalid data'], 400);
    }
    

}
