<?php

namespace App\Controller;

use App\Service\FruitsDatatableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route('/fruits', name: 'app_fruits')]
    public function fruits(Request $request, FruitsDatatableFactory $datatableFactory): Response
    {
        $table = $datatableFactory->create()->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('table_page.html.twig', [
            'title' => 'Fruits database',
            'datatable' => $table,
        ]);
    }

    #[Route('/favorites', name: 'app_favorites')]
    public function favorites(Request $request, FruitsDatatableFactory $datatableFactory): Response
    {
        $table = $datatableFactory->create(true)->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('table_page.html.twig', [
            'title' => 'Favorite fruits',
            'datatable' => $table,
        ]);
    }
}
