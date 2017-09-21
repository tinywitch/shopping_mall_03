<?php

namespace Application\Service;

use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Application\Entity\ProductColorImage;
use Application\Entity\Image;
use Application\Entity\User;
use Application\Entity\OrderItem;
use Zend\Filter\StaticFilter;
use Application\Entity\Comment;


class ProductManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $color = [
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
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCommentCountStr($product)
    {
        $commentCount = count($product->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1)
            return '1 comment';
        else
            return $commentCount . ' comments';
    }

    // This method adds a new comment to .
    public function addComment($product, $data)
    {
        // Create new Comment entity.
        $user = $this->entityManager->
        getRepository('Application\Entity\User')->find($data['user_id']);
        $comment = new Comment();
        $comment->setProduct($product);
        $comment->setUser($user);
        $comment->setContent($data['content']);
        if ($data['comment_id'] != null) {
            $parent = $this->entityManager->
            getRepository(Comment::class)->find($data['comment_id']);
            $comment->setParent($parent);
        }
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($comment);

        // Apply changes.
        $this->entityManager->flush();

        return $comment;
    }

    public function addReview($product, $data)
    {
        $user = $this->entityManager->getRepository('Application\Entity\User')->find($data['user_id']);
        $review = new Review();
        $review->setProduct($product);
        $review->setUser($user);
        $review->setContent($data['content']);
        $review->setRate($data['rate']);
        $currentDate = date('Y-m-d H:i:s');
        $review->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($review);

        // Apply changes.
        $this->entityManager->flush();

        return $review;
    }

    public function deleteComment($data)
    {
        $comment = $this->entityManager->
        getRepository(Comment::class)->find($data['comment_id']);

        $this->entityManager->remove($comment);
        $this->entityManager->flush();
    }

    public function getCountSellsInMonth($productId)
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);
        $count = 0;

        foreach ($product->getProductMasters() as $productMaster) {
            $count = $count + count($productMaster->findOrderByMonth());
        }

        return $count;
    }

    public function getBestSellsInCurrentMonth($countOfBest)
    {
        $arr = [];
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {
            if (count($arr) < $countOfBest) {
                $arr[] = $product->getId();

            } else {
                if ($this->getCountSellsInMonth($product->getId()) > min($arr)) {
                    sort($arr);
                    $arr[0] = $product->getId();
                }
            }
        }

        return $arr;
    }

    public function getBestSaleProduct($countOfBest)
    {
        $arr = [];
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        foreach ($products as $product) {

            if (count($arr) < $countOfBest) {
                $arr[$product->getId()] = $product->getCurrentSale();

            } else {
                asort($arr);
                if ($product->getCurrentSale() > current($arr)) {

                    unset($arr[key($arr)]);

                    $arr[$product->getId()] = $product->getCurrentSale();
                }
            }
        }

        return $arr;
    }


    public function getDataProductDetail($productId)
    {
        $data = [];
        $product = $this->entityManager->getRepository(Product::class)->findOneById($productId);
        $size_colors = $product->getSizeAndImageEachColors();
        $data['id'] = $productId;
        $data['name'] = $product->getName();
        $data['price'] = $product->getCurrentPrice();
        $data['rate_sum'] = $product->getRateSum();
        $data['rate_count'] = $product->getRateCount();
        $data['intro'] = $product->getIntro();


        foreach ($size_colors as $key => $size_color) {
            $data['colors'][] = $this->color[$key];
            $data['sizes'][$this->color[$key]] = $size_color['size'];
            $data['images'][$this->color[$key]][0] = $size_color['image'][0];
            foreach ($size_color['image'][1] as $image) {
                $data['images'][$this->color[$key]][] = $image;
            }

        }
        // find review
        $data['review']['size'] = 10;
        $data['review']['page'] = 1;
        $data['review']['total'] = $product->getRateCount();
        $reviews = $product->getReviews();

        for ($i = 0; $i < count($reviews); $i++) {
            $item = [];
            $item['id'] = $reviews[$i]->getId();
            $item['rate'] = $reviews[$i]->getRate();
            $item['user_name'] = 'Test';
            $item['content'] = $reviews[$i]->getContent();
            $item['date_created'] = $reviews[$i]->getDateCreated();
            $item['province'] = 'Ha Noi';
            $data['review']['items'][] = $item;

        }
        //find comment
        $coments = $product->getComments();
        $data['comment']['size'] = 10;
        $data['comment']['page'] = 1;
        $data['comment']['total'] = count($coments);


        for ($i = 0; $i < count($comments); $i++) {
            $item = [];
            $commentId = $comments[$i]->getId();
            $item['id'] = $commentId;
            $item['user_id'] = 1;
            $item['user_name'] = 'Test';
            $item['content'] = $comments[$i]->getContent();
            $replyComments = $this->entityManager->getRepository(Comment::class)
                ->findBy(['parent_id' => $commentId], ['date_created' => 'DESC']);
            foreach ($replyComments as $reply) {
                $itemReply['id'] = $reply->getId();
                $itemReply['user_id'] = 1;
                $itemReply['user_name'] = "Admin";
                $itemReply['content'] = $reply->getContent();
                $item['replies'][] = $itemReply;
            }

            //$item['date_created'] = $comments[$i]->getDateCreated();
            $data['comment']['items'][] = $item;

        }

        return $data;
    }
}
