<?php
namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MessageController extends AbstractController
{
    #[Route('/message', name: 'message')]
    public function number(): Response
    {
        return $this->render('message.html.twig', [

        ]);
    }
}