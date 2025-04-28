<?php
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\Type\BrandType;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BrandController extends AbstractController
{
    #[\Symfony\Component\Routing\Annotation\Route('/brand', name: 'brand')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $brand = new Brand();
    $form = $this->createForm(BrandType::class, $brand);

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