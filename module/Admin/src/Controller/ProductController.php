<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\UserForm;
use Application\Entity\Product;
use Application\Entity\Category;
use Application\Entity\Store;
use Admin\Form\ProductForm;
use Admin\Helper\ToSlug;
use Zend\File\Transfer\Adapter\Http;

class ProductController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * User manager.
     * @var Application\Service\ProductManager
     */
    private $productManager;
    /**
     * User manager.
     * @var Application\Service\CategoryManager
     */
    private $categoryManager;
    private $storeManager;
    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $productManager, $categoryManager, $storeManager)
    {
        $this->entityManager = $entityManager;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager; 
        $this->storeManager = $storeManager;     
    }

    /**
     * This action displays the "New User" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Post entity will be created.
     */
    public function indexAction(){
        return ViewModel();
    }
    public function listAction(){
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        // Render the view template
        return new ViewModel([
        'products' => $products
        ]);
    }
    public function addAction(){
        $form = new ProductForm('create', $this->categoryManager, $this->storeManager, $this->entityManager);
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
        // Get POST data.
        $data = $this->params()->fromPost();
        $files =  $_FILES;
        $httpadapter = new \Zend\File\Transfer\Adapter\Http();
        $httpadapter->setDestination('public/img/products/');
        $httpadapter->receive();
        $data['image'] = $httpadapter->getFileName();
        $data['image'] = ltrim($data['image'],"public");
        $form->setData($data);
        if ($form->isValid()) {
            // Get validated form data.
          //var_dump($data);die();
            $data['alias'] = $this->slug($data['name']);
            $data['status'] = 1;
            $data['rate_avg'] = 0;
            $data['rate_count'] = 0;
            $data['sale'] = 0;
            $data['popular_level'] = 0;
            $data['category'] = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
            $data['store'] = $this->entityManager->getRepository(Store::class)->find($data['store_id']);
            $currentDate = date('Y-m-d H:i:s');
            $data['date_created'] = $currentDate;
            // Use post manager service to add new post to database. 
            $this->productManager->addNewProduct($data);             
            // Redirect the user to "index" page.
            return $this->redirect()->toRoute('products', ['action'=>'list']);
        }
    }

    // Render the view template.
    return new ViewModel([
    'form' => $form
    ]);
    }
    public function editAction()
    {
        // Get product ID.    
        $productId = $this->params()->fromRoute('id', -1);

        // Find existing post in the database.    
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        // Create the form.
        $form = new ProductForm('edit', $this->categoryManager, $this->storeManager, $this->entityManager, $product);
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            echo "hasn't product with id = ".$productId;                        
        } 
        if ($this->getRequest()->isPost()) {
        // Get POST data.
        $data = $this->params()->fromPost();
        // Fill form with data.
        //var_dump($data);die();
        if($data['category_id'] == 0) $data['category_id'] = $product->getCategory()->getId();
        if($data['store_id'] == 0) $data['store_id'] = $product->getStore()->getId();
        if($_FILES['image']['name']=='') $data['image'] = $product->getImage();
        else{
            $httpadapter = new \Zend\File\Transfer\Adapter\Http();
            $httpadapter->setDestination('public/img/products/');
            $httpadapter->receive();
            $data['image'] = $httpadapter->getFileName();
              $data['image'] = ltrim($data['image'],"public");
        }
        $form->setData($data); 
        if ($form->isValid()) {
               // Get validated form data.
            $data = $form->getData();
            $data['alias'] = $this->slug($data['name']);
            $data['category'] = $this->entityManager->getRepository(Category::class)->find($data['category_id']);
            $data['store'] = $this->entityManager->getRepository(Store::class)->find($data['store_id']);
            $this->productManager->updateProduct($product, $data);
            // Redirect the user to "admin" page.
            return $this->redirect()->toRoute('products', ['action'=>'list']);
        }
        }else 
        {
            $data = [
            'name'  =>  $product->getName(),
            'price' =>  $product->getPrice(),
            'intro' =>  $product->getIntro(),
            'image' =>  $product->getImage(),
            'popular_level' => $product->getPopular_level(),  
            'description'   => $product->getDescription(),   
            'status'    => $product->getStatus(),
            'sale'  => $product->getSale(),   
            ];

            $form->setData($data);
        }

        // Render the view template.
        return new ViewModel([
        'form' => $form,
        'product' => $product
        ]);
    }

    public function deleteAction()
    {

        $productId = $this->params()->fromRoute('id', -1);
        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($productId);        
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        $filename = 'public'.$product->getImage();
        $Message = '';
        if (file_exists($filename)) {
            if (!unlink($filename)) {
              $Message = "Error deleting $filename";
            } else {
              $Message = "Deleted $filename";
            }
        } else {
            $Message = "The file $filename does not exist";
        } 
        $this->productManager->removeProduct($product);

        // Redirect the user to "products/list" page.
        return $this->redirect()->toRoute('products', ['action'=>'list']);
    }

    public function slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}
