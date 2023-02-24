<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Region;
use App\Form\RegionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegionController extends AbstractController
{
    #[Route('/region', name: 'app_region')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $region = $doctrine->getRepository(Region::class)->findAll();
        $province = $doctrine->getRepository(Province::class)->findAll();
        $commune = $doctrine->getRepository(Commune::class)->findAll();

        $arrayComplete = array_merge($region, $province, $commune);

        $form = $this->createForm(RegionType::class, null, [
            'arregloRegiones' => $arrayComplete,
        ]);

        return $this->render('region/index.html.twig', [
            'controller_name' => 'RegionController',
            'form' => $form,
        ]);
    }

    #[Route('/datosJson', name: 'datos_json')]
    public function datosJson(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $formData = $request->request->all();
        $formData = $formData['region']['select'];

        $resultados = [];

        foreach ($formData as $valueArray){
            $values = explode(",", $valueArray);
            $id = $values[0];
            $name = $values[1];

            $region = $doctrine->getRepository(Region::class)->findBy(
                ['id' => $id, 'name' => $name]
            );

            if(!empty($region)){
                $province = $doctrine->getRepository(Province::class)->findBy(
                    ['region' => $region[0]->getId()]
                );
                $commune = $doctrine->getRepository(Commune::class)->findBy(
                    ['region' => $region[0]->getId()]
                );
                $arrayComplete = array_merge($region, $province, $commune);
            }else{
                $region = [];
                $province = $doctrine->getRepository(Province::class)->findBy(
                    ['id' => $id, 'name' => $name]
                );
                if(!empty($province)){
                    $commune = $doctrine->getRepository(Commune::class)->findBy(
                        ['province' => $province[0]->getId()]
                    );
                    $arrayComplete = array_merge($region, $province, $commune);
                }else{
                    $province = [];
                    $commune = $doctrine->getRepository(Commune::class)->findBy(
                        ['id' => $id, 'name' => $name]
                    );
                    $arrayComplete = array_merge($region, $province, $commune);
                    if(empty($commune)){
                        $commune = [];
                        $arrayComplete = [];
                    }
                }
            }
            array_push($resultados, $arrayComplete);
        }

        $arrayFinal = [];

        foreach($resultados as $resultado){
            foreach($resultado as $Af){
                array_push($arrayFinal, $Af->getName());
            }
        }

        return new JsonResponse($arrayFinal);
    }

}
