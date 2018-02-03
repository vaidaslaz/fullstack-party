<?php

/*
 * (c) Vaidas Lažauskas <vaidas@notrix.lt>
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * App\Controller\AuthController
 */
class AuthController extends Controller
{
    /**
     * @Route(
     *     "/login{trailingSlash}",
     *     defaults={"trailingSlash" = "/"},
     *     requirements={"trailingSlash" = "[/]{0,1}"}
     * )
     * @Template()
     *
     * @return array
     */
    public function loginAction()
    {
        return [
            'login_to' => $this->getParameter('hwi_oauth.resource_owners'),
        ];
    }
}
