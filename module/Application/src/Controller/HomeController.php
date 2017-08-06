<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractActionController
{
    public function indexAction()
    {
    	//var_dump(2);die();
        $view = new ViewModel();
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
}
