<?php

namespace Renelems\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

use Renelems\DBBundle\Entity\Admin;

class SecurityController extends Controller
{
	/**
     * @Route("/login", name="admin_login")
     * @Template()
     */
    public function loginAction()
    {
    	$admin = new Admin();
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'RenelemsBackofficeBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    
    /**
     * @Route("/login_check", name="admin_login_check")
     * @Template()
     */
    public function loginCheckAction() {
    
    }
    
    /**
     * @Route("/logout", name="admin_logout")
     * @Template()
     */
    public function logoutAction() {
    
    }
}
