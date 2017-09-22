<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Category;
use Application\Entity\Product;
use Application\Entity\Province;
use Admin\Helper\TrunCate;

class HomeController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;
    private $productManager;
    private $categoryManager;

    public function __construct($entityManager, $categoryManager, $productManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }

    public function indexAction()
    {
        $newProducts = $this->entityManager->getRepository(Product::class)->findBy(['status' => Product::STATUS_PUBLISHED], ['date_created' => 'DESC'], 5);

        $bestSell = $this->productManager->getBestSellsInCurrentMonth(4);

        $bestSellProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $bestSell]);
        $bestSales = array_keys($this->productManager->getBestSaleProduct(5));
        $bestSaleProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $bestSales]);
        //var_dump($bestSaleProducts);die();
        $view = new ViewModel([
            'newProducts' => $newProducts,
            'bestSellProducts' => $bestSellProducts,
            'bestSaleProducts' => $bestSaleProducts
        ]);
        $this->layout('application/layout');

        return $view;
    }

    public function viewAction()
    {
        //var_dump(2);die();
        $view = new ViewModel();
        $this->layout('application/home');
        return $view;
    }

    public function searchAction()
    {
        $this->layout('application/layout');
        return new ViewModel();
    }

    public function getDataSearchAction()
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $productArray = [];
        foreach ($products as $product) {
            $product_a['id'] = $product->getID();
            $product_a['name'] = $product->getName();
            $product_a['image'] = $product->getImage();
            array_push($productArray, $product_a);
        }
        $product_json = json_encode($productArray);
        $this->response->setContent($product_json);

        return $this->response;
    }

    public function loaddistrictAction()
    {
        $data = $this->params()->fromPost();
        if ($data['province_id']) {
            $province_id = $data['province_id'];
            $province = $this->entityManager->getRepository(Province::class)
                ->find($province_id);
        } else {
            $province_name = $data['province_name'];
            $province = $this->entityManager->getRepository(Province::class)
                ->findOneBy(array('name' => $province_name));;
        }

        if ($province == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $districts = $province->getDistricts();
        foreach ($districts as $d) {
            $districts_for_select[$d->getId()] = $d->getName();
        }

        $data_json = json_encode($districts_for_select);
        $this->response->setContent($data_json);
        return $this->response;
    }

    public function loadprovinceAction()
    {
        $provinces = $this->entityManager->getRepository(Province::class)
            ->findAll();
        $arr = [];
        foreach ($provinces as $prov) {
            array_push($arr, $prov->getName());
        }
        $this->response->setContent(json_encode($arr));
        return $this->response;
    }
}
