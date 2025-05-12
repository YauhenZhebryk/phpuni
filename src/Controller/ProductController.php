<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductController extends AbstractController
{
    #[Route('/admin/product', name: 'product')]
public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
{
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            }
            catch (FileException $e) {
                $this->addFlash('error', 'Image could not be uploaded');}
        }
        $product->setImage($newFilename);
        $em->persist($product);
        $em->flush();
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}
}