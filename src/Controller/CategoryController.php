<?php
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\BrandType;
use App\Form\Type\CategoryType;
use App\Form\Type\ProductType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;


class CategoryController extends AbstractController
{
    #[\Symfony\Component\Routing\Annotation\Route('/admin/category', name: 'category')]
public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
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
        $category->setImage($newFilename);
        $em->persist($category);
        $em->flush();


        return $this->redirectToRoute('category');
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}

    #[Route('/admin/category/{id}/delete', name: 'category_delete', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($category->getImage()) {
            $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $category->getImage();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $entityManager->remove($category);
        $entityManager->flush();


        return $this->redirectToRoute('main');
    }

    #[\Symfony\Component\Routing\Annotation\Route('/category/{id}', name: 'category_show')]
    public function show(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $products = $category->getProducts();

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}