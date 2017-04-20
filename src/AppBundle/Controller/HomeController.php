<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function getHomeAction()
    {
        return $this->redirectToRoute('get_articles');
    }
}
