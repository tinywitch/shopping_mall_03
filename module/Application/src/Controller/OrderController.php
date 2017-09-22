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
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    function viewAction()
    {
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }
}
