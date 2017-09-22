<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Admin\Form\ProductForm;
use Admin\Form\AddColorForm;
use Zend\File\Transfer\Adapter\Http;

class ProductController extends AbstractActionController
{
    private $list_color = [
                ProductMaster::WHITE => 'white',
                ProductMaster::BLACK => 'black',
                ProductMaster::YELLOW => 'yellow',
                ProductMaster::RED => 'red',
                ProductMaster::GREEN => 'green',
                ProductMaster::PURPLE => 'purple',
                ProductMaster::ORANGE => 'orange',
                ProductMaster::BLUE => 'blue',
                ProductMaster::GREY => 'grey',
                ];
    private $list_size = [
                ProductMaster::S => 'S',
                ProductMaster::M => 'M',
                ProductMaster::L => 'L',
                ProductMaster::XL => 'XL',
                ProductMaster::XXL => 'XXL',
                ];
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * User manager.
     * @var Admin\Service\ProductManager
     */
    private $productManager;
    /**
     * User manager.
     * @var Admin\Service\CategoryManager
     */
    private $categoryManager;
    private $storeManager;
    /**
     * User manager.
     * @var Admin\Service\ImageManager
     */
    private $imageManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct(
        $entityManager, 
        $productManager, 
        $categoryManager, 
        $storeManager, 
        $imageManager
    ) {
        $this->entityManager = $entityManager;
        $this->productManager = $productManager;
        $this->categoryManager = $categoryManager; 
        $this->storeManager = $storeManager;
        $this->imageManager = $imageManager;

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
    
        return new ViewModel([
            'products' => $products,
        ]);
    }

    public function viewAction()
    {
        $productId = $this->params()->fromRoute('id', -1);
    
        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($productId);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }

        $size_and_images = $product->getSizeAndImageEachColors();
        
        return new ViewModel([
            'product' => $product,
            'size_and_images' => $size_and_images,
            'list_color' => $this->list_color,
            'list_size' => $this->list_size
            ]);     
    }

    public function addAction(){
        $categories = $this->categoryManager->categories_for_select();

        $form = new ProductForm('create', $categories, $this->entityManager);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data['alias'] = $this->slug($data['name']);
                
                $files =  $_FILES;
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $httpadapter->setDestination('public/img/products/');
                $httpadapter->receive();
                $data['image'] = $httpadapter->getFileName();
                if ($data['image'] != null){
                    $data['image'] = ltrim($data['image'], "public");
                }

                
                $this->productManager->addNewProduct($data);             
                
                return $this->redirect()->toRoute('products', ['action'=>'list']);
            }
        }

        return new ViewModel([
            'form' => $form
            ]);
    }

    public function addcolorAction()
    {

        $productId = $this->params()->fromRoute('id', -1);
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                      
        }

        $color = $this->productManager->getColorForSelect($product); 
        $form = new AddColorForm($color);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['image-details'] = $this->imageManager->saveImages($_FILES);

            $this->productManager->addNewColor($product, $data);

            return $this->redirect()->toRoute('products', ['action'=>'list']);
        }

        return new ViewModel([
            'product' => $product,
            'form' => $form
            ]);
    }

    public function removecolorAction()
    {
        $productId = $this->params()->fromRoute('id', -1);
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                      
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $this->productManager->removeColor($product, $data['color_id']);

            return $this->redirect()->toRoute('products', ['action'=>'view', 'id' => $product->getId()]);
        }

    }

    public function editAction()
    {  
        $categories = $this->categoryManager->categories_for_select();
        
        $productId = $this->params()->fromRoute('id', -1);

        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        //var_dump($images);die();
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                      
        }

        $form = new ProductForm('edit', $categories, $this->entityManager);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
    
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $files =  $_FILES;
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $httpadapter->setDestination('public/img/products/');
                $httpadapter->receive();
                $data['image'] = $httpadapter->getFileName();
                $data['image'] = ltrim($data['image'], "public");

                $data['alias'] = $this->slug($data['name']);
                //var_dump($data);die();
                $this->productManager->updateProduct($product, $data);
                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('products', ['action'=>'list']);
            }
        } else 
        {
            $data = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'intro' => $product->getIntro(),
                
                'description' => $product->getDescription(),   
                'status' => $product->getStatus(),
                'category_id' => $product->getCategory(),
                'keywords' => $this->productManager->convertKeywordsToString($product)

                ];

            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'product' => $product,
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

        $filename = 'public' . $product->getImage();

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
