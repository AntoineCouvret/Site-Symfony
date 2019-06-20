<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(AdRepository $repo)
    {
        return $this->render('admin/ad/index.html.twig', [
 				'ads' => $repo ->findAll()
        ]);
    }

    /**
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     */
    public function delete(Ad $ad, ObjectManager $manager)
    {
        //dump (count($ad->getBookings()));
        //die;
        if (count($ad->getBookings()) > 0) {
        	$this->addFlash(
            'warning',
            "L'annonce ne peut être supprimée"
        	);
        }
        else{

        $manager -> remove ($ad);
        $manager -> flush();

        $this->addFlash(
            'success',
            "L'annonce a bien été supprimée"

        );
    	}


        return $this -> redirectToRoute('admin_ads_index');


    }
}
