<?php

namespace App\Controller;

use App\Repository\SkateparkRepository;
use App\Repository\ClubRepository;
use App\Repository\ShopRepository;
use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(ClubRepository $clubRepository, SkateparkRepository $skateparkRepository, ShopRepository $shopRepository, SessionRepository $sessionRepository)
    {
        $lastSessions = $sessionRepository->findBy([], ['id' => 'DESC'], 3);
        $lastSkateparks = $skateparkRepository->findBy([], ['id' => 'DESC'], 3);
        $lastClubs = $clubRepository->findBy([], ['id' => 'DESC'], 3);
        $lastShops = $shopRepository->findBy([], ['id' => 'DESC'], 3);


        return $this->render("home.html.twig", [
            'lastSessions' => $lastSessions,
            'lastSkateparks' => $lastSkateparks,
            'lastClubs' => $lastClubs,
            'lastShops' => $lastShops
        ]);
    }
}
