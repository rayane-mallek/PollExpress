<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class PublicationController extends Controller {

    public function feed()
    {   
        $publications = $this->containerInterface->get('publication_service')->getAllPublications();
        $sondages = $this->containerInterface->get('sondage_service')->getAllSondages();

        return $this->render('publications/feed.html.twig', [
            'publications' => $publications,
            'sondages' => $sondages
        ]);
    }

    public function submitFeedy(Request $request) 
    {
        $message = $request->get('message');
        try {
            $this->containerInterface->get('publication_service')->createNewPublication($message, $this->containerInterface->get('utilisateur_service')->getUserId());

            return $this->redirectToRoute('app_feed');
        } catch(ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_feed');
        }
    }
}
