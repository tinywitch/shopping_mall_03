<?php
namespace Admin\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Application\Entity\Product;
use Application\Entity\Category;
use Zend\Validator\Regex;
class ProductForm extends Form {
    
    public function __construct(){
        parent::__construct();
        
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
        
        // Name 
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
        // Sale
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
        $this->addInputFilter();

    }
    private function addInputFilter() 
    {   
    }     
}
