<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Form\BasketType;
use App\Form\SearchProductType;
use App\Form\SearchUserType;
use App\Repository\BasketRepository;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_ADMIN')]

#[Route('/basket/admin')]
class BasketAdminController extends AbstractController
{
    #[Route('/', name: 'app_basket_admin_index', methods: ['GET'])]
    public function index(Request $request, BasketRepository $basketRepository): Response
    {

        $formSearch = $this->createForm(SearchUserType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $search = $formSearch->getData()['search'];
            $baskets = $basketRepository->findLikeName($search);
        } else {
            $baskets = $basketRepository->findAll();
        }

        $user = $this->getUser();

        $basketIds = [];
        foreach ($baskets as $basket) {
            $basketIds[] = $basket->getId();
        }


        $user = $this->getUser();
        return $this->render('basket_admin/index.html.twig', [
            'baskets' => $baskets,
            'user' => $user,
            'formSearch' => $formSearch,
            'basketIds' => $basketIds,
            'basket' => $basket,
        ]);
    }

    #[Route('/new', name: 'app_basket_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $basket = new Basket();
        $form = $this->createForm(BasketType::class, $basket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($basket);
            $entityManager->flush();

            return $this->redirectToRoute('app_basket_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('basket_admin/new.html.twig', [
            'basket' => $basket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_basket_admin_show', methods: ['GET'])]
    public function show(Basket $basket): Response
    {
        return $this->render('basket_admin/show.html.twig', [
            'basket' => $basket,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_basket_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Basket $basket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BasketType::class, $basket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_basket_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('basket_admin/edit.html.twig', [
            'basket' => $basket,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_basket_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Basket $basket, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $basket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($basket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_basket_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
