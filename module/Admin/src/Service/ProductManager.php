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
        $product = new Product();

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
        $product->setStatus(0);
        $product->setDateCreated($data['date_created']);
        $product->setImage($data['image']);
        $this->addKeywordsToProduct($data['keywords'], $product);
        
        $this->entityManager->persist($product);

        $this->entityManager->flush();

    }

    public function addNewColor($product, $data)
    {
        $imageUrls = $this->processImageUrl($data['image-details']);
        $currentDate = date('Y-m-d H:i:s');

        // creat product master
        for ($i = 0; $i < count($data['size']); $i++) {
            $product_master = new ProductMaster();

            $product_master->setProduct($product);
            $product_master->setSizeId((int)$data['size'][$i]);
            $product_master->setColorId($data['color']);

            $this->entityManager->persist($product_master);
        }

        // create product color images
        $product_color_image = new ProductColorImage();
        $product_color_image->setProduct($product);    
        $product_color_image->setColorId($data['color']);
        $product_color_image->setDateCreated($currentDate);

        $this->entityManager->persist($product_color_image);
        
        // create images for each color
        $i = 0;
        foreach ($imageUrls as $imageUrl) {
            $image = new Image();
            $image->setProductColorImage($product_color_image);
            $image->setImage($imageUrl);

            //set first image as main image
            if ($i == 0) {
                $image->setType(Image::MAIN);
                $i = 1;
            } else {
                $image->setType(Image::SUB);
            }
            $image->setDateCreated($currentDate);

            $this->entityManager->persist($image);
        }

        $this->entityManager->flush();
    }

    public function updateProduct($product, $data) 
    {
        $data['category'] = $this->entityManager->
            getRepository('Application\Entity\Category')->find($data['category_id']);
        
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setDescription($data['description']);
        $product->setStatus($data['status']);
        $product->setImage($data['image']);
        if (!empty($data['picture']['image_0_'])) {
            $product->setImage($data['picture']['image_0_']);
        }
        if ($data['category'] != null)
            $product->setCategory($data['category']);
        
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

    public function removeColor($product, $color_id)
    {
        $product_color_images = $product->getProductColorImages();
        $product_masters = $product->getProductMasters();

        foreach ($product_color_images as $pci) {
            if ($pci->getColorId() == $color_id)
                $this->entityManager->remove($pci); 
        }

        foreach ($product_masters as $pm) {
            if ($pm->getColorId() == $color_id)
                $this->entityManager->remove($pm); 
        }

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

    public function convertSizeView($sizes)
    {     
        $arr =['S','M','L','XL','XXL'];
        for($i = 0; $i < count($sizes); $i++) {
            $sizes[$i] = $arr[$sizes[$i] - 1];
        }
        return $sizes;
    }

    public function getColorForSelect($product)
    {
        $color = [
            ProductMaster::WHITE => 'White',
            ProductMaster::BLACK => 'Black',
            ProductMaster::YELLOW => 'Yellow',
            ProductMaster::RED => 'Red',
            ProductMaster::GREEN => 'Green',
            ProductMaster::PURPLE => 'Purple',
            ProductMaster::ORANGE => 'Orange',
            ProductMaster::BLUE => 'Blue',
            ProductMaster::GREY => 'Grey',
            ];
        $productMasters = $product->getProductMasters();
        
        foreach ($productMasters as $pm) {
           unset($color[$pm->getColorId()]); 
        }
        
        return $color;
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

    private function processImageUrl($imageUrls)
    {
        $i = 0;
        foreach ($imageUrls as $imageUrl) {
            $Urls[$i] = ltrim($imageUrl, "public");
            $i++;
        }
        return $Urls;
    }
}
