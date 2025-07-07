<?php 


return [

    [
        'menu'      => 'Home',
        'icon'      => 'fa-solid fa-house',
        'submenu'   => [

            'Dashboard' => [
                'route' => 'dashboard'
            ],
            

        ],

    ],




    [
        'menu'      => 'Users',
        'icon'      => 'fa-solid fa-address-card',
        'submenu'   => [

            'Enrollee' => [
                'route' => 'registration'
            ],
            

        ],

    ],

   
];