<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Repository\BrandRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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

        return $this->redirectToRoute('product');
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}

    #[Route('/admin/product/{id}/delete', name: 'product_delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getImage()) {
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $product->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('main');
    }

    #[NoReturn] #[Route('/product/show', name: 'product_show')]
    public function show(ProductRepository $productRepository): Response
    {
        $allProducts = $productRepository->findAll();
        $mainPage = $this->generateUrl('main');

        return $this->render('show.html.twig', [
            'products' => $allProducts,
            'mainPage' => $mainPage,
        ]);
    }

    #[\Symfony\Component\Routing\Annotation\Route('/product/{id}', name: 'product_show_one')]
    public function oneProductShow(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        $brand = $product->getBrand();
        $category = $product->getCategory()[0];

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/showOne.html.twig', [
            'product' => $product,
            'brand' => $brand,
            'category' => $category,
        ]);
    }
}