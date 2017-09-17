<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SaleProgram;
use Admin\Form\SaleProgramForm;

class SaleProgramController extends AbstractActionController
{
	/**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $saleProgramManager;

    public function __construct($entityManager, $saleProgramManager)
    {
        $this->entityManager = $entityManager;
        $this->saleProgramManager = $saleProgramManager;        
    }

    public function indexAction()
    {
    	return new ViewModel();
    }

    public function addAction()
    {
        $form = new SaleProgramForm();
        if ($this->getRequest()->isPost()) {
            //var_dump(2);die();
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                // Check if Category exists?
                if ($this->saleProgramManager->addNewSaleProgram($data) != 0)             
                    return $this->redirect()->toRoute('sale_programs',['action'=>'index']);
            }
        }

    	return new ViewModel([
    		'form' => $form
    		]);
    }
}
