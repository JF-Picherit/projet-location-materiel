<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ThingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



use App\Entity\Category;
use App\Entity\Thing;
use App\Form\ThingType;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * @Security("is_granted('ROLE_ADMIN')", statusCode=403, message="Accès refusé")
 * 
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * Liste les utilisateurs du site
     * 
     * @route("/utilisateurs", name="utilisateurs")
     */
    public function usersList(UserRepository $users){
        return $this->render("admin/users.html.twig", [
            'users' => $users->findAll()
        ]);
    }
}
