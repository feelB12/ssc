<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Repository\SkateparkRepository;
use App\Repository\ClubRepository;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @Route("/admin", name="dashboard")
     */
    public function dashboard(ClubRepository $clubRepository, SkateparkRepository $skateparkRepository, ShopRepository $shopRepository, SessionRepository $sessionRepository)
    {
        $lastSessions = $sessionRepository->findBy([], ['id' => 'DESC'], 3);
        $lastSkateparks = $skateparkRepository->findBy([], ['id' => 'DESC'], 3);
        $lastClubs = $clubRepository->findBy([], ['id' => 'DESC'], 3);
        $lastShops = $shopRepository->findBy([], ['id' => 'DESC'], 3);


        return $this->render("admin/dashboard.html.twig", [
            'lastSessions' => $lastSessions,
            'lastSkateparks' => $lastSkateparks,
            'lastClubs' => $lastClubs,
            'lastShops' => $lastShops
        ]);
    }

}