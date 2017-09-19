<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Admin\Form\ProductForm;
use Admin\Form\EditProductForm;
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
        'products' => $products
        ]);
    }

    public function viewAction()
    {
        
        $productId = $this->params()->fromRoute('id', -1);
    
        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($productId);
        $sizes = $this->productManager->findSizeByProductId($productId);
        $images = $this->productManager->findAllColorByProductId($productId);
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }
        
        return new ViewModel([
            'product' => $product,
            'sizes' => $sizes,
            'images' => $images,
            ]);     
    }

    public function addAction(){
        $categories = $this->categoryManager->categories_for_select();

        $form = new ProductForm('create', $categories, $this->entityManager);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $count = count($_FILES['image']['name']);
            $data['color[]'] = $data['color'][0];
            $data['count'] = $count;
            
            $form->setData($data);
            if ($form->isValid()) {
                $data['alias'] = $this->slug($data['name']);
                
                $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                $httpadapter->setDestination('public/img/products/');
                
                $files = $httpadapter->getFileInfo();
                $data['image'] = $httpadapter->getFileName();
                foreach ($files as $file => $info) {
                    $httpadapter->receive($file);
                }
                
                $this->productManager->addNewProduct($data);             
                
                return $this->redirect()->toRoute('products', ['action'=>'list']);
            }
        }

        return new ViewModel([
            'form' => $form
            ]);
    }

    public function editAction()
    {  
        $categories = $this->categoryManager->categories_for_select();
        
        $productId = $this->params()->fromRoute('id', -1);

        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $images = $this->productManager->findAllColorByProductId($productId);
        //var_dump($images);die();
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);                      
        }

        $form = new EditProductForm(count($images), $categories, $this->entityManager, $product);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            //var_dump($_POST);echo "<pre>";print_r($_FILES);echo "<pre>";die();
    
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                //edit $_FILES :
                $countOfFiles = count($_FILES['image']['name']);
                $temp = [];
                for ($i = 0; $i < $countOfFiles; $i++){
                    $temp['image']['name'][$i] = $_FILES['image']['name'][$i]['image'];
                    $temp['image']['type'][$i] = $_FILES['image']['type'][$i]['image'];
                    $temp['image']['tmp_name'][$i] = $_FILES['image']['tmp_name'][$i]['image'];
                    $temp['image']['error'][$i] = $_FILES['image']['error'][$i]['image'];
                    $temp['image']['size'][$i] = $_FILES['image']['size'][$i]['image'];
                    for ($j = 1; $j < 5; $j++){
                        $temp['imageDetail' . $j]['name'][$i] = $_FILES['image']['name'][$i]['imageDetail' . $j];
                        $temp['imageDetail' . $j]['type'][$i] = $_FILES['image']['type'][$i]['imageDetail' . $j];
                        $temp['imageDetail' . $j]['tmp_name'][$i] = $_FILES['image']['tmp_name'][$i]['imageDetail' . $j];
                        $temp['imageDetail' . $j]['error'][$i] = $_FILES['image']['error'][$i]['imageDetail' . $j];
                        $temp['imageDetail' . $j]['size'][$i] = $_FILES['image']['size'][$i]['imageDetail' . $j];
                    }
                }

                $_FILES = $temp;
                
                $data['picture'] = $this->imageManager->saveImages($temp);
               
                // covert $data['image'] string to array
                if (is_string($data['picture'])) {
                    for ($i = 0; $i < $countOfFiles; $i++) {
                        if (!empty($temp['image']['name'][$i])) {
                            $data['picture'] = ['image_' . $i . '_' => $data['picture']];
                        }
                        for ($j = 1; $j < 5; $j++) {
                            if (!empty($temp['imageDetail' . $j]['name'][$i])) {
                                $data['picture'] = ['imageDetail' . $j . '_' . $i . "_" => $data['picture']];
                            }
                        }
                    }
                }

                $mesDelete = $this->imageManager->deleteFiles($images, $data['picture']);

                $data['alias'] = $this->slug($data['name']);
                //var_dump($data);die();
                $this->productManager->updateProduct($product, $data, $images);
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
            for ($i = 0;$i < count($images); $i++){
                $data['image'][$i]['size'] = $images[$i]['size'];
                $data['image'][$i]['color'] = $images[$i]['color']; 
            }     
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'product' => $product,
            'images' => $images,
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
