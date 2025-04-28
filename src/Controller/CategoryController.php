<?php
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\BrandType;
use App\Form\Type\CategoryType;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{
    #[\Symfony\Component\Routing\Annotation\Route('/category', name: 'category')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);

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