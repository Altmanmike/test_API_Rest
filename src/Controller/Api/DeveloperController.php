<?php

namespace App\Controller\Api;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DeveloperController extends AbstractController
{
    #[Route('/api/developer', name: 'app_api_developer_list', methods: ['GET'])]
    public function list(DeveloperRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $allDev = $repo->findAll();        
        $jsonDev = $serializer->serialize($allDev, "json");
        
        return JsonResponse::fromJsonString($jsonDev);
    }

    #[Route('/api/developer/{id}', name: 'app_api_developer_show', methods: ['GET'])]
    public function show($id, DeveloperRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $oneDev = $repo->find($id);           
        $jsonDev = $serializer->serialize($oneDev, "json");
        
        return JsonResponse::fromJsonString($jsonDev);
    }

    #[Route('/api/developer/{id}', name: 'app_api_developer_delete', methods: ['DELETE'])]
    public function delete(Developer $developer, EntityManagerInterface $em): JsonResponse
    {                   
        $em->remove($developer);
        $em->flush();
        
        return $this->json('', 200);
    }

    #[Route('/api/developer', name: 'app_api_developer_create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/DeveloperController.php',
        ]);
    }

    #[Route('/api/developer', name: 'app_api_developer', methods: ['PUT', 'PATCH'])]
    public function update(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/DeveloperController.php',
        ]);
    }
}