<?php
namespace Reference;

return [
    'api_adapters' => [
        'invokables' => [
            'references' => Api\Adapter\ReferenceAdapter::class
        ],
    ],
    'entity_manager' => [
        'mapping_classes_paths' => [
            dirname(__DIR__) . '/src/Entity',
        ],
        'proxy_paths' => [
            dirname(__DIR__) . '/data/doctrine-proxies',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
        'factories' => [
            'addReferenceForm' => Service\ViewHelper\AddReferenceFormFactory::class,
        ],
    ],
    'controllers' => [
        'invokables' => [
            Controller\IndexController::class => Controller\IndexController::class,
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'reference' => [
                        'type' => \Laminas\Router\Http\Literal::class,
                        'options' => [
                            'route' => '/reference',
                            'defaults' => [
                                '__NAMESPACE__' => 'Reference\Controller',
                                'controller' => Controller\IndexController::class,
                                'action' => 'show',
                            ],        
                        ],         
                        'may_terminate' => true,
                        'child_routes' => [
                            'add' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/add/:id',
                                    'defaults' => [
                                        'action' => 'add',
                                    ],
                                ],
                                'may_terminate' => true,                            
                            ],
                            'id' => [
                                'type' => \Laminas\Router\Http\Segment::class,
                                'options' => [
                                    'route' => '/:id[/:action]',
                                    'constraints' => [
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '\d+',
                                    ],
                                    'defaults' => [
                                        'action' => 'show',
                                    ],
                                ],
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]

]; 