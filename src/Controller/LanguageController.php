<?php

namespace TwinElements\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends AbstractController
{
    public function renderAction(Request $request)
    {
        $languages = $this->getParameter('app_locales');

        return $this->render('@TwinElementsAdmin/language-switcher.html.twig', [
            'current' => $request->getLocale(),
            'all_languages' => explode('|', $languages)
        ]);
    }

    /**
     * @Route("/switch_language", name="switch_language")
     */
    public function switchAction(Request $request)
    {
        $request->getSession()->set('_locale', $request->get('language'));
        $referer = $request->server->get('HTTP_REFERER');

        return new RedirectResponse($referer);
    }
}
