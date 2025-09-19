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
        'menu'      => 'Records',
        'icon'      => 'fa-solid fa-clipboard-list',
        'submenu'   => [

            'Attendance' => [
                'route' => 'attendance'
            ],

            'Enrollments' => [
                'route' => 'enrollment'
            ],

            'Fee Collection History' => [
                'route' => 'fee_collection'
            ]
            
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


    [
        'menu'      => 'Studio',
        'icon'      => 'fas fa-person-running',
        'submenu'   => [

            'Dance' => [
                'route' => 'dance'
            ],
            

        ],

    ],


    [
        'menu'      => 'RFID',
        'icon'      => 'fa-solid fa-id-card',
        'submenu'   => [

            'RFID Status' => [
                'route' => 'status'
            ],
            

        ],

    ],

   
];