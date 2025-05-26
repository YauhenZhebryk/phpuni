<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class BrandShowController extends AbstractController
{
    #[Route('/brand/{id}', name: 'brand_show')]
    public function show(int $id, BrandRepository $brandRepository): Response
    {
        $brand = $brandRepository->find($id);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        $products = $brand->getProducts();

        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }
}