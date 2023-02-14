<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TheFeed\Business\Exception\ServiceException;

class UtilisateurController extends Controller {

    public function getInscription()
    {
        return $this->render('utilisateurs/inscription.html.twig', []);
    }

    public function submitInscription(Request $request)
    {
        $login = $request->get('login');
        $password = $request->get('password');
        $adresseMail = $request->get('adresseMail');
        $profilePicture = $request->files->get('profilePicture');

        try {
            $this->containerInterface->get('utilisateur_service')->createUtilisateur($login, $password, $adresseMail, $profilePicture);
            $this->addFlash('success', 'Inscription réussie !');

            return $this->redirectToRoute('app_feed');
        } catch (ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->render('utilisateurs/inscription.html.twig', [
                'login'    => $login,
                'adresseMail' => $adresseMail
            ]);
        }

    }

    public function getConnexion()
    {
        return $this->render('utilisateurs/connexion.html.twig', []);
    }

    public function submitConnexion(Request $request)
    {
        $login = $request->get('login');
        $password = $request->get('password');

        try {
            $utilisateurService = $this->containerInterface->get('utilisateur_service');
            $utilisateurService->connexion($login, $password);

            return $this->redirectToRoute('app_feed');
        } catch(ServiceException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('app_connexion');
        }
    }

    public function submitDeconnexion(Request $request)
    {
        $this->containerInterface->get('utilisateur_service')->deconnexion();

        return $this->redirectToRoute('app_feed');
    }

    public function getPagePerso(int $idUtilisateur)
    {   
        try {
            $publications = $this->containerInterface->get('publication_service')->getPublicationsFrom($idUtilisateur);
            $utilisateur = $this->containerInterface->get('utilisateur_service')->getUtilisateur($idUtilisateur);
            $sondages = $this->containerInterface->get('sondage_service')->getSondagesFrom($idUtilisateur);

            return $this->render('utilisateurs/page_perso.html.twig', [
                'publications' => $publications,
                'utilisateur'  => $utilisateur,
                'sondages' => $sondages
            ]);
        } catch (ServiceException $exception) {
            throw new ResourceNotFoundException();
        }
    }
}