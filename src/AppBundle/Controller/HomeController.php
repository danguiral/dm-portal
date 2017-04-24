<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Method({"GET"})
     * @return Response
     */
    public function getHomeAction()
    {
        return $this->redirectToRoute('get_articles');
    }
}
