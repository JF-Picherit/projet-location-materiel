<?php

namespace App\Controller;

use App\Entity\Thing;
use App\Entity\ThingInstance;
use App\Form\ThingInstanceType;
use App\Repository\ThingInstanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\DateType;


/**
 * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Accès refusé")
 * 
 * @Route("/thing/instance")
 */
class ThingInstanceController extends AbstractController
{
    /**
     * 
     * @Route("/{thing}/index", name="thing_instance_index", methods={"GET"})
     */
    public function index(Thing $thing, ThingInstanceRepository $thingInstanceRepository): Response
    {
        return $this->render('thing_instance/index.html.twig', [
            'thing' => $thing,
            'thing_instances' => $thingInstanceRepository->findBy(['thing' => $thing->getId()], array('serial' => 'asc')),
        ]);
    }

    /**
     * @Route("/{thing}/new", name="thing_instance_new", methods={"GET","POST"})
     */
    public function new(Thing $thing, Request $request): Response
    {
        $thingInstance = new ThingInstance();
        $thingInstance->setThing($thing);
        $form = $this->createForm(ThingInstanceType::class, $thingInstance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($thingInstance);
            $entityManager->flush();

            return $this->redirectToRoute('thing_instance_index', [
                'thing' => $thing->getId()
            ]);
        }

        return $this->render('thing_instance/new.html.twig', [
            'thing' => $thing,
            'thing_instance' => $thingInstance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="thing_instance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ThingInstance $thingInstance): Response
    {
        $form = $this->createForm(ThingInstanceType::class, $thingInstance);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('thing_instance_index', [
                'thing' => $thingInstance->getThing()->getId()
            ]);
        }

        return $this->render('thing_instance/edit.html.twig', [
            'thing_instance' => $thingInstance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/attribut", name="thing_instance_attribut", methods={"GET","POST"})
     */
    public function attribut(Request $request, ThingInstance $thingInstance): Response
    {
        $today = new \DateTime(date("Y-m-d"));
        $thingInstance->setReturnDate($today);
        $form = $this->createFormBuilder($thingInstance)
        ->add('returnDate', DateType::class, ['widget' => 'single_text'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thingInstance->setBorrowDate($today);
            $thingInstance->setBorrower($thingInstance->getBooker());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('thing_instance_index', [
                'thing' => $thingInstance->getThing()->getId()
            ]);
        }

        return $this->render('thing_instance/attribut.html.twig', [
            'thing_instance' => $thingInstance,
            'form' => $form->createView(),
            'booker' => $thingInstance->getBooker()->getEmail()
        ]);
    }

    /**
     * @Route("/{id}/annule_booker", name="thing_instance_annule_booker", methods={"GET","POST"})
     */
    public function annule_booker(Request $request, ThingInstance $thingInstance): Response
    {
        $thingInstance->deleteBooker();
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('thing_instance_index', [
            'thing' => $thingInstance->getThing()->getId()
        ]);
    }

    /**
     * @Route("/{id}/return", name="thing_instance_return", methods={"GET","POST"})
     */
    public function instanceReturn(Request $request, ThingInstance $thingInstance): Response
    {
        $thingInstance->emptyInstance();
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('thing_instance_index', [
            'thing' => $thingInstance->getThing()->getId()
        ]);
    }


    /**
     * @Route("/{id}", name="thing_instance_delete", methods={"POST"})
     */
    public function delete(Request $request, ThingInstance $thingInstance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thingInstance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($thingInstance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('thing_instance_index', [
            'thing' => $thingInstance->getThing()->getId()
        ]);
    }
}
