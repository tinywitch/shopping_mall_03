<?php

namespace Base;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    // This lines opens the configuration for the RouteManager
    'router' => [
        // Open configuration for all possible routes
        'routes' => [
            // Define a new route called "base"
            'base' => [
                // Define a "literal" route type:
                'type' => Literal::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/base" as uri:
                    'route' => '/base',
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'base' => __DIR__ . '/../view',
        ],
    ], 
];   
