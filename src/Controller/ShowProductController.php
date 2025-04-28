<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// ...

class ShowProductController extends AbstractController
{
    #[NoReturn] #[Route('/product/show', name: 'product_show')]
    public function show(ProductRepository $productRepository): Response
    {
        $allProducts = $productRepository->findAll();
        $mainPage = $this->generateUrl('main');

        return $this->render('show.html.twig', [
            'products' => $allProducts,
            'mainPage' => $mainPage,
        ]);
    }
}