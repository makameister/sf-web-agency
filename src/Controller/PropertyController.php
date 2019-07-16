<?php
namespace App\Controller;

use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyController
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(PropertyRepository $repository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /**
     * @return Response
     */
    public function index() : Response
    {
        $params = $this->repository->findAllVisible();
        dump($params);

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/buy/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*" })
     * @param Property $property
     * @return Reponse
     */
    public function show(Property $property, string $slug) : Response
    {
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ],
            301
            );
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }
}