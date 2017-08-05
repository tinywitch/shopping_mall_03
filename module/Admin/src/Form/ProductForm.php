<?php
namespace Admin\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Product;
use Application\Entity\Category;
use Zend\Validator\Regex;
use Admin\Validator\ProductExistsValidator;
use Zend\InputFilter\InputFilterProviderInterface;
class ProductForm extends Form {

 private $scenario;
   /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
   private $entityManager = null;
   
    /**
     * Current user.
     * @var Application\Entity\Product 
     */
    private $product = null;

    public function __construct($scenario = 'create', $entityManager = null, $product = null){
        parent::__construct();
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->product = $product;
        // FORM Attribute
        $this->setAttributes([
            'action'    => '#',
            'method'    => 'POST',
            'class'     => 'form-horizontal',
            'role'      => 'form',
            'name'      => 'ProductForm',
            'id'        => 'ProductForm',
            'style'     => 'padding-top: 20px;',
            'enctype'   => 'multipart/form-data'
            ]);

        // ID
        $this->add([
            'name'          => 'id',
            'attributes'    => [
            'type'  => 'hidden',
            ],
            ]);
        $this->add([
            'name'          => 'name',
            'type'          => 'text',
            'attributes'    => [
            'class'         => 'form-control width-custom',
            'id'            => 'name',
            'placeholder'   => 'Enter product name : ',
            ],
            'options'       => [
            'label'             => 'Product name :',
            'label_attributes'  => [
            'for'       => 'name',
            'class'     => 'control-label ',
            ]
            ],
            ]);

        // Description
        $this->add([
            'name'          => 'description',
            'type'          => 'Textarea',
            'attributes'    => [
            'class'         => 'form-control width-custom',
            'id'            => 'description',
            ],
            'options'       => [
            'label'             => 'Description :',
            'label_attributes'  => [
            'for'       => 'description',
            'class'     => 'control-label',
            ]
            ],
            ]);
        // File picture
        $this->add([
            'name'          => 'image',
            'type'          => 'File',
            'attributes'    => [
            'class'         => 'form-control width-custom',
            'id'            => 'image',
            ],
            'options'       => [
            'label'             => 'Picture',
            'label_attributes'  => [
            'for'       => 'image',
            'class'     => 'control-label',
            ]
            ],
            ]);
        // Price
        $this->add([
            'name'          => 'price',
            'type'          => 'Text',
            'attributes'    => [
            'class'         => 'form-control width-custom',
            'id'            => 'price',
            'placeholder'   => 'Enter Price',
            ],
            'options'       => [
            'label'             => 'Price :',
            'label_attributes'  => [
            'for'       => 'price',
            'class'     => 'control-label',
            ]
            ],
            ]);
        // Intro
        $this->add([
            'name'          => 'intro',
            'type'          => 'Text',
            'attributes'    => [
            'class'         => 'form-control width-custom',
            'id'            => 'intro',
            'placeholder'   => 'Enter Intro :',
            ],
            'options'       => [
            'label'             => 'Intro :',
            'label_attributes'  => [
            'for'       => 'intro',
            'class'     => 'control-label',
            ]
            ],
            ]);
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
            'value' => 'Create',
            'id' => 'submitbutton',
            ],
            ]);
        // Category
        // $this->add([
        //     'name'          => 'category_id',
        //     'type'          => 'Select',
        //     'options'       => [
        //     'empty_option'  => '-- Select category --',

        //     'label' => 'Category',
        //     'label_attributes'  => [
        //     'for'       => 'category_id',
        //     'class'     => 'control-label',
        //     ],
        //     ],
        //     'attributes'    => [
        //     'class'         => 'form-control',
        //     ],
        //     ]);
        // Store
        // $this->add([
        //     'name'          => 'store_id',
        //     'type'          => 'Select',
        //     'options'       => [
        //     'empty_option'  => '-- Select store --',

        //     'label' => 'Store',
        //     'label_attributes'  => [
        //     'for'       => 'store_id',
        //     'class'     => 'control-label',
        //     ],
        //     ],
        //     'attributes'    => [
        //     'class'         => 'form-control',
        //     ],
        //     ]);
        // Sale value
        if ($this->scenario == 'edit') {
            $this->add([
                'name'          => 'sale',
                'type'          => 'Text',
                'attributes'    => [
                'class'         => 'form-control width-200',
                'id'            => 'sale',
                'placeholder'   => 'Enter sale off value:',
                ],
                'options'       => [
                'label'             => 'Sale off value:',
                'label_attributes'  => [
                'for'       => 'sale',
                'class'     => 'control-label',
                ]
                ],
                ]);
        // Status
            $this->add([
                'name'          => 'status',
                'type'          => 'Select',
                'options'       => [
                'empty_option'  => '-- Select status --',
                'value_options' => [
                '1'    => 'Active',
                '0'  => 'InActive',
                ],
                'label' => 'Status',
                'label_attributes'  => [
                'for'       => 'status',
                'class'     => 'control-label',
                ],
                ],
                'attributes'    => [
                'class'         => 'form-control width-200',
                ],
                ]);                
            $this->add([
                'name'          => 'popular_level',
                'type'          => 'Select',
                'attributes'    => [
                'class'         => 'form-control width-200',
                'id'            => 'popular_level',
                ],
                'options'       => [
                'empty_option'  => '-- Select type --',
                'value_options' => array(
                    '1'       => 'Special',
                    '0'        => 'Normal',
                    ),
                'label'             => 'Product type :',
                'label_attributes'  => [
                'for'       => 'popular_level',
                'class'     => 'control-label ',
                ]
                ],    
                ]);
        }
        $this->addInputFilter();

    }
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    { 
       // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
       // Name
        $inputFilter->add([
            'name'      => 'name',
            'required'  => true,
            'validators'    => [
            [
            'name'      => 'NotEmpty',
            
            ],       
            
            [
            'name'      => 'StringLength',
            'options'   => array('min' => 4, 'max' => 200),
            
            ],
            [
            'name' => ProductExistsValidator::class,
            'options' => [
            'entityManager' => $this->entityManager,
            'product' => $this->product
            ],
            
            ],
            ],
            ]); 
        // Add input for "description" field
        $inputFilter->add([
            'name'     => 'description',
            'required' => true,
            'filters'  => [                    
            ['name' => 'StringTrim'],
            ],                
            'validators' => [
            [
            'name'    => 'StringLength',
            'options' => [
            'min' => 1,
            'max' => 8096
            ],
            ],
            ],
            ]);
        // Add input for "intro" field
        $inputFilter->add([
            'name'     => 'intro',
            'required' => true,
            'filters'  => [                    
            ['name' => 'StringTrim'],
            ],                
            'validators' => [
            [
            'name'    => 'StringLength',
            'options' => [
            'min' => 1,
            'max' => 8096
            ],
            ],
            ],
            ]);
        //price
        $inputFilter->add([
            'name'     => 'price',
            'required' => true,
            'validators'  => [                    
            ['name'     => 'Digits',],
            ],                
            
            ]); 
        if($this->scenario == 'edit'){
        //sale 
            $inputFilter->add(
                [
                'name'      => 'sale',
                'required'  => false,
                'validators'  => [                    
                ['name'     => 'Digits',],
                ], 
                ]
                );
        //status 
            $inputFilter->add(
                [
                'name'      => 'status',
                'required'  => true,
                ]
                );
         //popular_level 
            $inputFilter->add(
                [
                'name'      => 'popular_level',
                'required'  => true,
                ]
                );
        }
    }     
}
