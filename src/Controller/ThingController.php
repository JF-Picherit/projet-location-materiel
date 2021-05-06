<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Thing;
use App\Form\ThingType;
use App\Repository\CategoryRepository;
use App\Repository\ThingRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')", statusCode=403, message="Accès refusé")
 * 
 * @Route("/thing")
 */
class ThingController extends AbstractController
{
    /**
     * @Route("/", name="thing_index", methods={"GET", "POST"})
     */
    public function index(ThingRepository $thingRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $builder = $this->createFormBuilder();
        $builder->add('categories', EntityType::class, [
            'class' => Category::class,
            'multiple' => true,
            'expanded' => true,
        ]);

        $builder->add('ok', SubmitType::class, ['label' => 'Filtrer']);
        $form = $builder->getForm();
        $form->setData([
            'categories' => $categoryRepository->findAll()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && isset($request->request->get('form')['categories'])) {
            $this->getDoctrine()->getManager()->flush();
            $categories = $request->request->get('form')['categories'];
            return $this->render('thing/index.html.twig', [
                'things' => $thingRepository->findByCategory($categories),
                'form' => $form->createView()
            ]);
        }
        $test = $thingRepository->findBy(array(), array('name' => 'asc'));
        //dd($test);
        return $this->render('thing/index.html.twig', [
            'things' => $thingRepository->findBy(array(), array('name' => 'asc')),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="thing_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $thing = new Thing();
        $form = $this->createForm(ThingType::class, $thing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($thing);
            $entityManager->flush();

            return $this->redirectToRoute('thing_index');
        }

        return $this->render('thing/new.html.twig', [
            'thing' => $thing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/{id}", name="thing_book")
     */
    public function book(Thing $thing, EntityManagerInterface $em): Response
    {
        $found = false;

        foreach ($thing->getThingInstances() as $instance) {
            if ($instance->getBooker() == null && $instance->getBorrower() == null) {
                $instance->setBooker($this->getUser());
                $em->flush();
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->addFlash('success', "L'objet a bien été reservé!");
        } else {
            $this->addFlash('danger', "Désolé, cet objet n'est pas disponible!");
        }

        return $this->redirectToRoute('thing_index');
    }

    /**
     * @Route("/debook/{id}", name="thing_debook")
     */
    public function debook(Thing $thing, EntityManagerInterface $em): Response
    {
        foreach ($thing->getThingInstances() as $instance) {
            if ($instance->getBooker() != null) {
                $instance->deleteBooker();
                $em->flush();
                $this->addFlash('danger', "La réservation de l'objet a été annulé!");
                break;
            }
        }

        return $this->redirectToRoute('thing_index');
    }


    /**
     * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Accès refusé")
     * 
     * @Route("/{id}/edit", name="thing_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Thing $thing): Response
    {
        
        $form = $this->createForm(ThingType::class, $thing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('thing_index');
        }

        return $this->render('thing/edit.html.twig', [
            'thing' => $thing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="thing_delete", methods={"POST"})
     */
    public function delete(Request $request, Thing $thing): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thing->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($thing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('thing_index');
    }
}
