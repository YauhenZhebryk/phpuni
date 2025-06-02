<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductViewListener
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->get('_route') !== 'product_show_one') {
            return;
        }

        $product = $request->attributes->get('product');

        // Если в Route передаётся только id, достаём вручную:
        if (!$product && $request->attributes->get('id')) {
            $product = $this->em->getRepository(Product::class)->find($request->attributes->get('id'));
        }

        if ($product instanceof Product) {
            $product->incrementViews();
            $this->em->flush();
        }
    }
}