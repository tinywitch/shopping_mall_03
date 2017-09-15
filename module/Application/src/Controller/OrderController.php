<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class OrderController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;

    function __construct($entityManager, $sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
    }

    function trackAction()
    {
        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    function viewAction()
    {
        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }
}
