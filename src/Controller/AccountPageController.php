<?php

declare(strict_types=1);

// src/Controller/AccountController.php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountPageController extends AbstractController
{
    #[Route('/account', name: 'account_page')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $loginPage = $this->generateUrl('app_login');
        $registerPage = $this->generateUrl('app_register');
        $logoutPage =$this->generateUrl('app_logout');

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setUser($this->getUser());

            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Success');
            return $this->redirectToRoute('account_page');
        }

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'loginPage' => $loginPage,
            'registerPage' => $registerPage,
            'logoutPage' => $logoutPage,
        ]);
    }
}
