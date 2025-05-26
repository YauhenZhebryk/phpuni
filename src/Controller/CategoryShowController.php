<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class CategoryShowController extends AbstractController
{
    #[Route('/category/{id}', name: 'category_show')]
    public function show(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $products = $category->getProducts();

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}