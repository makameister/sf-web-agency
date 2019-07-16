<?php
namespace App\Controller;

use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use Knp\Component\Pager\PaginatorInterface;

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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request) : Response
    {
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/buy/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*" })
     * @param Property $property
     * @return Response
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