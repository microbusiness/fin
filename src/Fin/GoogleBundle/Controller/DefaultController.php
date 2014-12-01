<?php

namespace Fin\GoogleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Main controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     *
     * @Route("/", name="main_homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 'Main Page');
    }
}
