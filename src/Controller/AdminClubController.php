<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminClubController extends AbstractController
{

    /**
     * @Route("/admin/clubs", name="admin_clubs")
     */
    public function clubs(ClubRepository $clubRepository)
    {
        $clubs = $clubRepository->findAll();

        return $this->render("admin/clubs.html.twig", ['clubs' => $clubs]);
    }


    /**
     * @Route("/admin/club/delete/{id}", name="admin_club_delete")
     */
    public function clubDelete($id, ClubRepository $clubRepository, EntityManagerInterface $entityManager)
    {
        $club = $clubRepository->find($id);

        $entityManager->remove($club);
        $entityManager->flush();

        return $this->redirectToRoute("clubs");
    }


    /**
     * @Route("/admin/club/create", name="admin_club_create")
     */
    public function createClub(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $club = new Club();
        $clubForm = $this->createForm(ClubType::class, $club);
        $clubForm->handleRequest($request);

        if ($clubForm->isSubmitted() && $clubForm->isValid()) {
            // gestion de l'upload d'image
            // 1) récupérer le fichier uploadé
            $coverFile = $clubForm->get('coverFilename')->getData();

            if ($coverFile) {
                // 2) récupérer le nom du fichier uploadé
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);

                // 3) renommer le fichier avec un nom unique
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $coverFile->guessExtension();

                // 4) déplacer le fichier dans le dossier publique
                $coverFile->move(
                    $this->getParameter('cover_directory'),
                    $newFilename
                );

                // 5) enregistrer le nom du fichier dans la colonne coverFilename
                $club->setCoverFilename($newFilename);
            }


            $entityManager->persist($club);
            $entityManager->flush();

            $this->addFlash('success', "Le Club a bien été enregistré !");
            return $this->redirectToRoute('admin_clubs');
        }

        return $this->render("admin/club_create.html.twig", [
            'clubForm' => $clubForm->createView()
        ]);
    }

    /**
     * @Route("/admin/club/update/{id}", name="admin_club_update")
     */
    public function updateClub($id, Request $request, ClubRepository $clubRepository, EntityManagerInterface $entityManager)
    {
        // je récupère un livre en bdd pour le mettre à jour
        $club = $clubRepository->find($id);

        // j'utilise la méthode createForm (d'AbstractController) qui va me permettre de créer un
        // formulaire en utilisant le gabarit généré (BookType) en lignes de commandes
        // et je lui associe l'instance de l'entité Book
        $clubForm = $this->createForm(ClubType::class, $club);

        // Asssocier le formulaire à la classe Request (le formulaire
        // lui est associé à l'instance de l'entité Book)
        $clubForm->handleRequest($request);

        // Vérifier que le formulaire a été envoyé
        // le isValid empeche que des données invalides par rapports aux types de colonnes
        // soient insérées + prévient les injections SQL
        if ($clubForm->isSubmitted() && $clubForm->isValid()) {
            // On enregistre l'entité en bdd avec l'entité manager (vu que l'instance de l'entité est reliée
            // au form et que le formulaire est reliée à la classe Request), Symfony va
            // automatiquement mettre les données du form dans l'instance de l'entité
            $entityManager->persist($club);
            $entityManager->flush();
        }

        // j'envoie à mon twig la variable contenant le formulaire
        // préparé pour l'affichage (avec la méthode createView())
        return $this->render("admin/club_update.html.twig", [
            'clubForm' => $clubForm->createView()
        ]);
    }

    /**
     * @Route("/admin/club/{id}", name="admin_club")
     */
    public function club($id, ClubRepository $clubRepository)
    {
        $club = $clubRepository->find($id);

        return $this->render("admin/club.html.twig", ['club' => $club]);
    }


    /**
     * @Route("/admin/search", name="admin_search_clubs")
     */
    public function searchClubs(ClubRepository $clubRepository, Request $request)
    {

        // je récupère ce que tu l'utilisateur a recherché grâce à la classe Request
        $word = $request->query->get('q');

        // je fais ma requête en BDD grâce à la méthode que j'ai créée searchByTitle
        $clubs = $clubRepository->searchByTitle($word);

        return $this->render('admin/clubs_search.html.twig', [
            'clubs' => $clubs
        ]);
    }

}