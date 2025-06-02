<?php
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\Type\BrandType;
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


class BrandController extends AbstractController
{
    #[\Symfony\Component\Routing\Annotation\Route('/admin/brand', name: 'brand')]
public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
{
    $brand = new Brand();
    $form = $this->createForm(BrandType::class, $brand);

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
        $brand->setImage($newFilename);
        $em->persist($brand);
        $em->flush();


        return $this->redirectToRoute('brand');
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}
    #[Route('/admin/brand/{id}/delete', name: 'brand_delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Brand $brand, EntityManagerInterface $entityManager): Response
    {
        if ($brand->getImage()) {
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $brand->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $entityManager->remove($brand);
        $entityManager->flush();

        return $this->redirectToRoute('main');
    }

    #[NoReturn] #[Route('/brand/all', name: 'brand_show_all')]
    public function showAll(BrandRepository $brandRepository): Response
    {
        $allBrands = $brandRepository->findAll();
        $mainPage = $this->generateUrl('main');

        return $this->render('brand/showAll.html.twig', [
            'brands' => $allBrands,
            'mainPage' => $mainPage,
        ]);
    }

    #[Route('/brand/{id}', name: 'brand_show')]
    public function show(int $id, BrandRepository $brandRepository): Response
    {
        $brand = $brandRepository->find($id);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        $products = $brand->getProducts();

        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }

}