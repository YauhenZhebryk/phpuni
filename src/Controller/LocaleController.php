<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'app_change_locale')]
    public function changeLocale(Request $request, string $locale): Response
    {
        $supportedLocales = ['en', 'pl'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = $this->getParameter('kernel.default_locale');
        }

        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('main'));
    }
}