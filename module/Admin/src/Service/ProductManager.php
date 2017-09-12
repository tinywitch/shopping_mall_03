<?php
namespace Admin\Service;

use Application\Entity\Product;
use Application\Entity\Image;
use Application\Entity\ProductColorImage;
use Application\Entity\ProductMaster;
use Zend\Filter\StaticFilter;
use Application\Entity\Keyword;

// The UserManager service is responsible for adding new posts.
class ProductManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }


    
    // This method adds a new product.
    public function addNewProduct($data)
    {
        //delete "public" in first of image_url
        for($i = 0; $i < $data['count']; $i++){
            $data['image']['image_'.$i.'_'] = ltrim($data['image']['image_'.$i.'_'], 
                "public");
            for($detail = 1;$detail < COUNT_IMAGE_DETAILS; $detail++){
                if(!empty($data['image']['imageDetail'.$detail.'_'.$i.'_']))
                    $data['image']['imageDetail'.$detail.'_'.$i.'_'] = 
                    ltrim($data['image']['imageDetail'.$detail.'_'.$i.'_'],"public");    
            }
        }
        // xu ly $data
        for($i = 0; $i < $data['count']; $i++){
            $data['color'][$i] = (int)$data['color'][$i];

        }    

        //init object
        //$i : Image upload
        //$j : 1->4 of ImageDetail1->4
        $product = new Product();

        for ($i = 0; $i < $data['count']; $i++){
            $product_color_image[$i] = new ProductColorImage();
            for($j = 0;$j < COUNT_IMAGE_DETAILS;$j++){
                $image[$i][$j] = new Image();
            }
            for($j = 0;$j < count($data['size']); $j++){
                $product_master[$i][$j] = new ProductMaster();
            }
            
        }

        
        
        // add new product
        $data['category'] = $this->entityManager->
            getRepository('Application\Entity\Category')->find($data['category_id']);
        $currentDate = date('Y-m-d H:i:s');
        $data['date_created'] = $currentDate;
        $product->setCategory($data['category']);
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setDescription($data['description']);
        $product->setStatus(1);
        $product->setDateCreated($data['date_created']);
        $product->setImage($data['image']['image_0_']);
        $this->addKeywordsToProduct($data['keywords'], $product);

        // add new images
        for ($i = 0; $i < $data['count']; $i++){

            $product_color_image[$i]->setColorId($data['color'][$i]);
            $product_color_image[$i]->setProduct($product);
            $product_color_image[$i]->setDateCreated($data['date_created']);
            $image[$i][0]->setImage($data['image']['image_'.$i.'_']);
            $image[$i][0]->setStatus(1);
            if( $i == 0 ) $image[$i][0]->setParentId(0);
            else $image[$i][0]->setParentId(-1);
            $image[$i][0]->setDateCreated($data['date_created']);
            $image[$i][0]->setProductColorImage($product_color_image[$i]);
            $this->entityManager->persist($image[$i][0]);
            for($j = 1;$j < COUNT_IMAGE_DETAILS;$j++){
                if(!empty($data['image']['imageDetail'.$j.'_'.$i.'_'])){
                    $image[$i][$j]->setImage($data['image']['imageDetail'.$j.'_'.$i.'_']);
                    $image[$i][$j]->setStatus(1);
                    $image[$i][$j]->setParentId($image[$i][0]->getId());
                    $image[$i][$j]->setDateCreated($data['date_created']);
                    $image[$i][$j]->setProductColorImage($product_color_image[$i]);
                }
            }
            for($j = 0;$j < count($data['size']); $j++){
                $product_master[$i][$j]->setProduct($product);
                $product_master[$i][$j]->setSizeId((int)$data['size'][$j]);
                $product_master[$i][$j]->setColorId($data['color'][$i]);

            }
            
        }


        // Add the entity to entity manager.
        $this->entityManager->persist($product);

        for ($i = 0; $i < $data['count']; $i++){
            $this->entityManager->persist($product_color_image[$i]);
            $this->entityManager->persist($image[$i][0]);
            for($j = 1;$j < COUNT_IMAGE_DETAILS;$j++){
                if(!empty($data['image']['imageDetail'.$j.'_'.$i.'_'])){
                    $this->entityManager->persist($image[$i][$j]);    
                }
            }
            for($j = 0;$j < count($data['size']); $j++){
                $this->entityManager->persist($product_master[$i][$j]);
            }
            
        }
        
        $this->entityManager->flush();

    }

    public function findSizeByProductId($productId){
        $product_masters =$this->entityManager->getRepository(ProductMaster::class)
            ->findSizeByProductId($productId);
            foreach($product_masters as $product_master){
                $size[] = $product_master['size_id'];
            };
            sort($size); 
            return $size;
    }

    public function findAllColorByProductId($productId){
        $product = $this->entityManager->getRepository(Product::class)->findOneById($productId);
        $productColorImages = $product->getProductColorImages();
        $count = 0;
        foreach ($productColorImages as $productColorImage) {
            $images = $productColorImage->getImages();
            
            foreach ($images as $image) {

                if($image->getParentId() == 0){ 
                    $data[0]['image'] = $image->getImage();

                    foreach ($images as $i) {

                        if($i->getParentId() == $image->getId()){
                            $data[0]['detail'][] = $i->getImage();    
                        }
                    }
                }
                if($image->getParentId() == -1){ 
                    $count++;
                    $data[$count]['image'] = $image->getImage();
                    foreach ($images as $i) {
                        if($i->getParentId() == $image->getId())
                            $data[$count]['detail'][] = $i->getImage();
                    }
                }

            }
             
        }
       
        return $data;
    }
    public function updateProduct($product, $data) 
    {
        $data['store'] = $this->entityManager->
            getRepository('Application\Entity\Store')->find($data['store_id']);
        $data['category'] = $this->entityManager->
            getRepository('Application\Entity\Category')->find($data['category_id']);
        
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setImage($data['image']);
        $product->setColor($data['color']);
        $product->setQuantity($data['quantity']);
        $product->setSize($data['size']);
        $product->setDescription($data['description']);
        $product->setStatus($data['status']);
        $product->setSale($data['sale']);
        if ($data['category'] != null)
            $product->setCategory($data['category']);
        if ($data['store'] != null)
            $product->setStore($data['store']);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeProduct($product)
    {   
        //remove comment
        $comments = $product->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }

        //remove keyword
        $productkeys = $product->getKeywords();
            foreach ($productkeys as $productkey) {
                $this->entityManager->remove($productkey);
        }
        $this->entityManager->remove($product);   
        $this->entityManager->flush();
    }

    public function convertKeywordsToString($product)
    {
        $keywords = $product->getKeywords();
        $keywordsCount = count($keywords);
        $keywordsStr = '';
        $i = 0;
        foreach ($keywords as $kw) {
            $i ++;
            $keywordsStr .= $kw->getKeyword();
            if ($i < $keywordsCount) 
                $keywordsStr .= ', ';
        }
        
        return $keywordsStr;
    }

    private function addKeywordsToProduct($keywordsStr, $product) 
    {
        // Remove tag associations (if any)
        $keywords = $product->getKeywords();
        foreach ($keywords as $keyword) {            
            $product->removeKeyWordAssociation($keyword);
        }
            
        // Add tags to product
        $keywords = explode(',', $keywordsStr);
        foreach ($keywords as $kword) {           
            $kword = StaticFilter::execute($kword, 'StringTrim');
            if (empty($kword)) {
                continue; 
            }
                
            $keyword = $this->entityManager->getRepository(Keyword::class)
                        ->findOneByKeyword($kword);
            if ($keyword == null)
                $keyword = new KeyWord();
            
            $keyword->setKeyword($kword);
            $currentDate = date('Y-m-d H:i:s');
            $keyword->setDateCreated($currentDate);
            $keyword->addProduct($product);
                    
            $this->entityManager->persist($keyword);
                    
            $product->addKeyword($keyword);
        }
    }

}
