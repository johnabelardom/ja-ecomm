<?php

return [
    'currency' => [
        'symbol' => '$',
        'code' => 'USD',
    ],
    'shipping_methods' => [
        'standard' => [
            'name' => 'Standard Shipping',
            'price' => 10.00
        ],
        'express' => [
            'name' => 'Express Shipping',
            'price' => 15.00
        ]
    ],
    'order_status' => [
        'new' => 'Order Details Received',
        'to-pack' => 'To Pack',
        'packed' => 'Packed',
        'for-pickup' => 'For Pickup',
        'picked-up' => 'Picked-up',
        'ongoing-delivery' => 'Ongoing Delivery',
        'delivered' => 'Delivered',
        'failed-delivery' => 'Delivery Failed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
        'refunded' => 'Refundedd',
    ]
];