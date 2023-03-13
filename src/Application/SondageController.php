<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class SondageController extends Controller {

    public function getCreateSondage()
    {
        return $this->render('sondages/create.html.twig', []);
    }
    public function submitSondage(Request $request)
    {
        $titre = $request->get('titre');
        try {
            $this->containerInterface->get('sondage_service')->createNewSondage($titre, $this->containerInterface->get('sondage_service')->getUserId());

            return $this->redirectToRoute('app_feed');
        } catch(ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_feed');
        }
    }

    public function listSondages()
    {
        $sondages = $this->containerInterface->get('sondage_service')->getAllSondages();

        return $this->render('sondages/list.html.twig', [
            'sondages' => $sondages
        ]);
    }

    public function voteOui(int $idSondage, int $idUtilisateur)
    {

        $this->containerInterface->get('sondage_service')->voteOui($idSondage, $idUtilisateur);
        return $this->redirectToRoute('app_list_sondages');
    }

    public function voteNon(int $idSondage, int $idUtilisateur)
    {
        $this->containerInterface->get('sondage_service')->voteNon($idSondage, $idUtilisateur);
        return $this->redirectToRoute('app_list_sondages');
    }

}
