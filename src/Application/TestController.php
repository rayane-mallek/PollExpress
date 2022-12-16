<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller {

    public function bonjour()
    {
        return $this->render('test.html.twig', [ 'message' => 'bonjour' ]);
    }

    public function additionneur(int $a, int $b)
    {
        return $this->render('test.html.twig', [ 'message' => 'Résultat = ' . ($a + $b) ]);
    }

    public function afficheInfos(Request $request)
    {
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');

        return $this->render('test.html.twig', [ 'message' => "Vous vous appelez {$nom} {$prenom}" ]);
    }

    public function testRediriger()
    {
        return $this->redirectToRoute('app_bonjour', [ 'message' => 'Redirection' ]);
    }
}