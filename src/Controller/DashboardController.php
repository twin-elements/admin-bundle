<?php

namespace TwinElements\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function index()
    {
        return $this->render('@TwinElementsAdmin/dashboard.html.twig');
    }
}
