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
        ]
    ]
];
