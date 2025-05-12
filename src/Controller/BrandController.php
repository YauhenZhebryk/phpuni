<?php
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Product;
use App\Form\Type\BrandType;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    }
    return $this->render('product.html.twig', [
        'form' => $form,
    ]);
}
}