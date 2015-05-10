<?php

return array(
    'home' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'home',
            ),
        ),
    ),
    'view-page' => array(
        'type' => 'Zend\Mvc\Router\Http\Segment',
        'options' => array(
            'route'    => '/strona/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'viewPage',
            ),
        ),
    ),
    'save-subscriber' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route'    => '/save-new-subscriber',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'saveSubscriberAjax',
            ),
        ),
    ),
    'newsletter-confirmation' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/newsletter-confirmation/:code',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'confirmationNewsletter',
            ),
            'constraints' => array(
                'code' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
    'news' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/aktualnosci',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'news',
            ),
        ),
    ),
    'events' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/wydarzenia',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'events',
            ),
        ),
    ),
    'one-news' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/aktualnosci/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'oneNews',
            ),
            'constraints' => array(
                'slug' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
    'one-events' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/wydarzenia/:slug',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'oneEvent',
            ),
            'constraints' => array(
                'slug' => '[a-zA-Z0-9_-]+'
            ),
        ),
    ),
    'search' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/wyszukiwanie[/strona/:number]/:search',
            'defaults' => array(
                'controller' => 'Page\Controller\Page',
                'action'     => 'search',
            ),
        ),
    ),
);