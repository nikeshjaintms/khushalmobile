<?php

return [
    'database_enum' => [
        'sales' => [
            'payment_method' => [
                'name' => [
                    '1' => 'Online',
                    '2' => 'Cash',
                    '3' => 'Finance',

                ],
                'code' => [
                    'Online' => '1',
                    'Cash' => '2',
                    'Finance' => '3',

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
        ]
    ]
];
