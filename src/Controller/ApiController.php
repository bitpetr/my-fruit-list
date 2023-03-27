<?php

namespace App\Controller;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/fruits/{id}', name: 'app_api_fruits_favorite', methods: ['PATCH'], format: 'application/json')]
    #[ParamConverter('fruit', class: Fruit::class, options: ['id' => 'id'])]
    public function favorite(Fruit $fruit, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = $request->toArray();
        if (!isset($data['isFavorite'])) {
            return $this->json(['error' => 'Invalid JSON body'], Response::HTTP_BAD_REQUEST);
        }

        if ($data['isFavorite'] && $entityManager->getRepository(Fruit::class)->countFavorites() > 9) {
            return $this->json(['error' => 'You can not have more than 10 favorite fruits!'],
                Response::HTTP_BAD_REQUEST);
        }
        $fruit->setIsFavorite($data['isFavorite']);

        $entityManager->persist($fruit);
        $entityManager->flush();

        return $this->json($fruit, Response::HTTP_OK);
    }
}
