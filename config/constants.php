<?php

return [
    'database_enum' => [
        'sales' => [
            'payment_method' => [
                'name' => [
                    '1' => 'Online/Cash',
                    '2' => 'Finance',

                ],
                'code' => [
                    'Online/Cash' => '1',
                    'Finance' => '2',

                ]
                ],
                'tax_type' => [
                    'name' => [
                        '1' => 'CGST/SGST',
                        '2' => 'IGST',


                    ],
                    'code' => [
                        'CGST/SGST' => '1',
                        'IGST' => '2',


                    ]
                ]
        ] ,

        'salesTransaction' => [
            'payment_method' => [
                'name' => [
                    '1' => 'Cash',
                    '2'=> 'Online',
                    '3' => 'Finance',

                ],
                'code' => [
                    'Cash' => '1',
                    'Online' => '2',
                    'Finance' => '3',

                ]
            ],

        ] ,

        'deductions' => [
            'payment_mode' => [
                'name' => [
                    1 => 'Online',
                    2 => 'Cash',

                ],
                'code' => [
                    'Online' => 1,
                    'Cash' => 2,

                ]
            ],

        ]
    ]
];
