<?php

namespace App\Controller\Api;

use App\Entity\Developer;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
        
        return $this->json('resource deleted', 200);
    }

    #[Route('/api/developer', name: 'app_api_developer_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $addJsonDev = $request->getContent();
        $objectDev = $serializer->deserialize($addJsonDev, Developer::class, 'json');
                
        $errors = $validator->validate($objectDev);
        
        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }
        
        $em->persist($objectDev);
        $em->flush();  
        
        return $this->json('resource added', 204);
    }

    #[Route('/api/developer/{id}', name: 'app_api_developer_update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, Developer $developer, ValidatorInterface $validator): JsonResponse
    {
        $updateJsonDev = $request->getContent();
        $objectDev = $serializer->deserialize($updateJsonDev, Developer::class, 'json',
            ["object_to_populate" => $developer ]
        );
        
        $errors = $validator->validate($objectDev);
        
        if (count($errors) > 0) {
            return $this->json($errors, 422);
        }
        
        $em->persist($objectDev);
        $em->flush();  
        
        return $this->json('resource updated', 204);
    }
}