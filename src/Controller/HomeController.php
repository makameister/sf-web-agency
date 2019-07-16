<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

Class HomeController extends AbstractController
{
    /**
     * @param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository) : Response
    {
        $properties = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }
}

