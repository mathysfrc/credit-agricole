<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\SearchProductType;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/card')]
class CardController extends AbstractController
{
    #[Route('/', name: 'app_card_index')]
    public function index(Request $request, CardRepository $cardRepository): Response
    {
        $formSearch = $this->createForm(SearchProductType::class);
        $formSearch->handleRequest($request);
    
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $cards = $cardRepository->findLikeName($search);
        } else {
            $cards = $cardRepository->findAll();  // Utilisez $cards au lieu de $card
        }

        $user = $this->getUser();
    
        // Utilisez une boucle pour obtenir les IDs de toutes les cartes
        $cardIds = [];
        foreach ($cards as $card) {
            $cardIds[] = $card->getId();
        }
        

        return $this->render('card/index.html.twig', [
            'cards' => $cards,  // Utilisez $cards au lieu de $card
            'user' => $user,
            'cardIds' => $cardIds,
            'formSearch' => $formSearch,
        ]);
    }



    #[Route('/new', name: 'app_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureProduct = $form->get('pictureProduct')->getData();

            if ($pictureProduct) {
                $originalFilename = pathinfo($pictureProduct->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureProduct->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictureProduct->move(
                        $this->getParameter('pictureProduct_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $card->setPictureProduct($newFilename);
            }

            $entityManager->persist($card);
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_card_show', methods: ['GET'])]
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'app_card_delete', methods: ['POST'])]
    public function delete(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$card->getId(), $request->request->get('_token'))) {
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
    }
}
