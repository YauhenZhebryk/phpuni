<?php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function number(): Response
    {

        $pages = [
            'category' => $this->generateUrl('category'),
            'product' => $this->generateUrl('product'),
            'brand' => $this->generateUrl('brand'),
            'product_show' => $this->generateUrl('product_show'),
            'register' => $this->generateUrl('app_register'),
            'login' => $this->generateUrl('app_login'),
            'logout' => $this->generateUrl('app_logout'),
        ];

        return $this->render('default.html.twig', [
            'pages' => $pages,
            'user' => $this->getUser(),
        ]);
    }
}