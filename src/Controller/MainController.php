<?php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function number(CategoryRepository $categoryRepository, BrandRepository $brandRepository, ProductRepository $productRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $brands = $brandRepository->findAll();
        $mainPage = $this->generateUrl('main');
        $randomProducts = $productRepository->findRandomProducts(4);
        $pages = [
            'category' => $this->generateUrl('category'),
            'product' => $this->generateUrl('product'),
            'brand' => $this->generateUrl('brand'),
            'product_show' => $this->generateUrl('product_show'),
            'register' => $this->generateUrl('app_register'),
            'login' => $this->generateUrl('app_login'),
            'logout' => $this->generateUrl('app_logout'),
            'password_change' => $this->generateUrl('app_password_change'),
        ];


        return $this->render('mainpage.html.twig', [
            'randomProducts' => $randomProducts,
            'brands' => $brands,
            'mainPage' => $mainPage,
            'pages' => $pages,
            'user' => $this->getUser(),
            'categories' => $categories,
        ]);
    }
}