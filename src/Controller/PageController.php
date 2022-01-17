<?php

namespace App\Controller;

use App\Repository\SkateparkRepository;
use App\Repository\ClubRepository;
use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function home(ClubRepository $clubRepository, SkateparkRepository $skateparkRepository, ShopRepository $shopRepository)
    {

        $lastSkateparks = $skateparkRepository->findBy([], ['id' => 'DESC'], 3);
        $lastClubs = $clubRepository->findBy([], ['id' => 'DESC'], 3);
        $lastShops = $shopRepository->findBy([], ['id' => 'DESC'], 3);


        return $this->render("home.html.twig", [

            'lastSkateparks' => $lastSkateparks,
            'lastClubs' => $lastClubs,
            'lastShops' => $lastShops
        ]);
    }
}
