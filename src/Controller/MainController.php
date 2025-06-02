<?php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;


class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function number(CategoryRepository $categoryRepository, BrandRepository $brandRepository, ProductRepository $productRepository, ReviewRepository $reviewRepository,TranslatorInterface $translator,Request $request): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $brands = $brandRepository->findAll();
        $mainPage = $this->generateUrl('main');
        $randomProducts = $productRepository->findRandomProducts(4);
        $randomReviews = $reviewRepository->findRandomReviews(3);
        $accountPage = $this->generateUrl('account_page');
        $musebox = 'MUSEBOX';
        $translatedHelloMessage = $translator->trans('welcome_message', ['%musebox%' => $musebox]);
        $categoriestrans = $translator->trans('categories');
        $brandstrans = $translator->trans('brands');
        $intereststrans = $translator->trans('interested');
        $reviewstrans = $translator->trans('reviewscheck');
        $allproductstrans = $translator->trans('allproducts');
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
            'allproducts' => $allproductstrans,
            'reviewstrans' => $reviewstrans,
            'categoriestrans' => $categoriestrans,
            'brandstrans' => $brandstrans,
            'intereststrans' => $intereststrans,
            'translatedHelloMessage' => $translatedHelloMessage,
            'randomProducts' => $randomProducts,
            'brands' => $brands,
            'mainPage' => $mainPage,
            'accountPage' => $accountPage,
            'pages' => $pages,
            'user' => $user,
            'categories' => $categories,
            'reviews' => $randomReviews,
        ]);
    }
}