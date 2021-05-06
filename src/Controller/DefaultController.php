<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ThingInstanceRepository;
use App\Entity\Thing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(ThingInstanceRepository $thingInstance): Response
    {
        $user = $this->getUser();
        
        if($user){
            $borrow_things = $user->getThingInstances();
            $user_role = $user->getRoles();
    
            if (in_array("ROLE_ADMIN", $user_role)) {
                return $this->render('default/index.html.twig', [
                    'borrow_things' => $thingInstance->getNoReturn(),
                ]);
            }
            else{
                return $this->render('default/index.html.twig', [
                    'borrow_things' => $borrow_things,
                ]);
            }
        }

        return $this->render('default/index.html.twig', []);
        
    }
}
