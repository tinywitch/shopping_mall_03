<?php
namespace Admin\Form;

use Application\Entity\Image;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods as ClassMethodsHydrator;

class ImageFieldset extends Fieldset implements InputFilterProviderInterface
{	
	private $color = [
        '1' => 'White',
        '2' => 'Black',
        '3' => 'Blue',
        '4' => 'Yellow',
        '5' => 'Red',
        '6' => 'Green',
        '7' => 'Purple',
        '8' => 'Orange',
        '9' => 'Light blue',
        '10' => 'Sky blue',
        '11' => 'Grey',
        ];
    private $size = [
        '21' => '21',
        '22' => '22',
        '23' => '23',
        '24' => '24',
        '25' => '25',
        '26' => '26',
        '27' => '27',
        '28' => '28',
        '29' => '29',
        '30' => '30',
        '31' => '31',
        ];
    public function __construct()
    {
        parent::__construct('image');

        $this->setHydrator(new ClassMethodsHydrator(false));
        

        $this->setLabel('Image');
        $this->setObject(new Image());
        $this->add([
            'name' => 'color',
            'type' => 'Select',
            'attributes' => [
                'class' => 'form-control width-custom',
                
                ],
            'options' => [
                'value_options' => $this->color,
                'label' => 'Color :',
                ],    
            ]);
        $this->add([
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'size',
            'options' => [
                'label' => 'Size : ',
                'value_options' => $this->size,
            ]
        ]);
        $this->add([
            'name' => 'image',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-custom',
                'id' => 'image',
                ],
            'options' => [
                'label' => 'Picture',
                ],
            ]);
        $this->add([
            'name' => 'imageDetail1',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-150',
                ],
            'options' => [
                'label' => 'Image details',
                ],
            ]);

        // File image detail 2
        $this->add([
            'name' => 'imageDetail2',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-150',
                ],
            'options' => [
                'label' => 'Image detail 2',
                ],
            ]);

        // File image detail 3
        $this->add([
            'name' => 'imageDetail3',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-150',
                ],
            'options' => [
                'label' => 'Image detail 3',
                ],
            ]);

        // File image detail 4
        $this->add([
            'name' => 'imageDetail4',
            'type' => 'File',
            'attributes' => [
                'class' => 'file form-control width-150',

                ],
            'options' => [
                'label' => 'Image detail 4',
                ],
            ]);
    }
    public function getInputFilterSpecification()
    {
        return [
            'color' => [
                'required' => true,
            ],
        ];
    }
}