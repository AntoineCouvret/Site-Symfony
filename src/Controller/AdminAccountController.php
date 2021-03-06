<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
     /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();


        return $this->render('admin/account/login.html.twig', [

        	'error' => $error

        ]);
        
    }
    /**
     * @Route("admin/logout", name="admin/account_logout")
     */
	public function logout()
    {
        
    }

    
}
