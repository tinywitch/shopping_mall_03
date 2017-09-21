<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Form\ShippingForm;
use Application\Entity\User;

/**
 * This controller is responsible for cart management (adding, editing,
 * viewing cart and changing user's password).
 */
class CartController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;

    private $sessionContainer;
    private $cartManager;
    private $orderManager;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $cartManager, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->cartManager = $cartManager;
        $this->orderManager = $orderManager;
        $this->sessionContainer = new Container('UserLogin');

    }

    /**
     * The "view" action displays a page allowing to view cart's details.
     */
    public function viewAction()
    {
        $cookie = $this->getRequest()->getCookie('Cart', 'default');
        $cart_info = json_decode($cookie["Cart"]);

        $view = new ViewModel([
            'data' => $cart_info,
        ]);
        $this->layout('application/layout');
        return $view;
    }

    public function checkoutAction()
    {
        $cookie = $this->getRequest()->getCookie('Cart', 'default');
        $cart_info = json_decode($cookie["Cart"]);
        $user['full_name'] = "vu truong";
        $user['phone_number'] = "0912593240";
        $user['email'] = "vutruong@gmail.com";
        $user['province'] = "Ha noi";
        $user['district'] = "vu truong";
        $user['address'] = "vu truong";
        $user['totalPrice'] = 100;
        $user['user_id'] = 1;
        $this->orderManager->addNewOrder($user, $cart_info);
        //var_dump($cart_info);die();
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->getRequest()->getContent();

            $data = json_decode($data);
            var_dump($data);

            // $data->ship_add->full_name;
            // $data->ship_add->phone_number;
            // $data->ship_add->email;
            // $data->ship_add->province;
            // $data->ship_add->dist;
            // $data->ship_add->address;

            // $data->sub_total;
            // $data->ship_tax;
            // $totalPrice = $data->sub_total + $data->ship_tax;

            // Validate form
            if (true) {

                // Get filtered and validated data
                // lay du lieu
//                $currentDate = date('Y-m-d H:i:s');
//                $data['date_created'] = $currentDate;
//                $data['status'] = 1;
//                $arrItems = $cart_info->items;
//
//                $this->orderManager->addNewOrder($data, $cart_info, $arrItems);

                $cookie = new \Zend\Http\Header\SetCookie(
                    'Cart',
                    '',
                    strtotime('-1 Year', time()), // -1 year lifetime (negative from now)
                    '/'
                );
                // $this->getResponse()->getHeaders()->addHeader($cookie); // Xoa cookie

                // response data
                $res = [
                    'status' => 'ok',
                    'order_id' => '12314kj',
                ];

                $this->response->setContent(json_encode($res));
                return $this->response;
            }
        } else {

        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }
}
