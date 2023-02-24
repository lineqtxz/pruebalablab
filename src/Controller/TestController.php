<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Region;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TestController extends AbstractController
{
    /**
     * @Route("/index")
     */
    public function index(): Response
    {
       return $this->render('Test/TestIndex.html.twig', [
            'titulo' => 'TEST'
       ]);
    }
}