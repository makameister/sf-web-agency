<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\PropertyType;

Class AdminPropertyController extends AbstractController
{
    /**
     * @Var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $repository
     * @param EntityManagerInterface $manager
     */
    public function __construct(PropertyRepository $repository, EntityManagerInterface $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    // @Route("/admin, name="admin.property.index")
    /**
     * @return Response
     */
    public function index() : Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function new(Request $request) : Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($property);
            $this->manager->flush();
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    // @Route("/admin/{id}, name="admin.property.edit")
    /**
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property, Request $request) : Response
    {
        //L'entité et les données (préremplir)
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->manager->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete(Property $property, Request $request){
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
            $this->manager->remove($property);
            $this->manager->flush();
            $this->addFlash('success', 'Le bien a été supprimé');
        }
        return $this->redirectToRoute('admin.property.index');
    }



}