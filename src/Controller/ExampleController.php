<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    #[Route('/example/form', name: 'app_example_form')]
    public function exampleForm(): Response
    {
        // Composant Request de Symfony
        $request = Request::createFromGlobals();

        if ($request->getMethod() === 'GET') {
            return $this->render('example/form.html.twig');
        } elseif ($request->getMethod() === 'POST') {
            // Si on est en post → le formulaire a été soumis
            // → Récupération des informations
            $title = $request->request->get('title');
            $content = $request->request->get('content');

            // Utiliser ces informations comme on le souhaite
            // ...

            // Rediriger vers une autre page
            return $this->redirectToRoute('app_homepage');
        }
    }
}