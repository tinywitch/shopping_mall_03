<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\Product_image;
use Zend\Session\Container;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class ProductController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;


    private $sessionContainer;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
//        if (isset($this->sessionContainer->id)) {
//            $id = $this->sessionContainer->id;
//        } else {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }

        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a user with such ID.
        $product = $this->entityManager->getRepository(Product::class)
            ->find($id);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $view = new ViewModel([
            'product' => $product
        ]);
        $this->layout('application/layout');
        return $view;
    }
}
