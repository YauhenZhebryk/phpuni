<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductController extends AbstractController
{
    #[\Symfony\Component\Routing\Annotation\Route('/admin/product', name: 'product')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $product = $form->getData();
        $em->persist($product);
        $em->flush();
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}
}