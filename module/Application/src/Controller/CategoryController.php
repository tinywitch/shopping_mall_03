<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\category;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Admin\Helper\TrunCate;
/**
 * 
 */
class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;


    private $sessionContainer;

    private $categoryManager;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionContainer, $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->categoryManager = $categoryManager;
    }

    /**
     * The "view" action displays a page .
     */
    public function viewAction()
    {   
        $categoryId = $this->params()->fromRoute('id', -1);
        $arr = [];
        $arr = $this->categoryManager->arrCate($arr,$categoryId);
        $page = $this->params()->fromQuery('page', 1);
        $category = $this->entityManager->getRepository(category::class)->find($categoryId);
        $products = $this->entityManager->getRepository(Product::class)
            ->findProductsByCategory($arr);

        $adapter = new DoctrineAdapter(new ORMPaginator($products, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(6);                
        $paginator->setCurrentPageNumber($page);

        $mainCategories = $this->categoryManager->mainCategories();
        $arrCateTree = $this->categoryManager->arrCateTree();

        foreach ($paginator as $product) {
            $truncate = new TrunCate();
            $truncate_name = $truncate($product->getName(), 30);
            $product->setName($truncate_name);
        }
        $view = new ViewModel([
            'products' => $paginator,
            'category' => $category,
            'mainCategories' => $mainCategories,
            'arrCateTree' => $arrCateTree,
        ]);
        $this->layout('application/layout');
        return $view;
    }
}
