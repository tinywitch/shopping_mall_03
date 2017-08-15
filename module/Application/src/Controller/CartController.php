<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Order;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class CartController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * The "view" action displays a page allowing to view cart's details.
     */
    public function viewAction()
    {
        if (isset($this->sessionContainer->items)) {
            $items = $this->sessionContainer->items;
        } else {
            $items = [];
        }

        $view = new ViewModel([
            'items' => $items
        ]);
        $this->layout('application/layout');
        return $view;
    }

    public function addAction()
    {

    }
}
