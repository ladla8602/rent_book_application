<?php

    /*
    |--------------------------------------------------------------------------
    | Application Constants Defines Here
    |--------------------------------------------------------------------------
    |
    | @var String
    |
    */

return [

    // Roles
    'ADMIN_ROLE'    => 1,
    'RENTER_ROLE'   => 2,

    // Soft delete keys
    'IS_DELETED_YES'    => 1,
    'IS_DELETED_NO'     => 2,

    'DEFAULT_PAGE_SIZE'           => 10,

    // Storage Locations
    'STORAGE_ASSET_PATH'          => storage_path('assets/'),
    'STORAGE_INVOICE_PATH'        => storage_path('assets/invoices'),

    // PDF Invoice constants
    'INVOICE_FROM_ADDRESS' => 'ABC Book Rental Company, India',
    'INVOICE_BILLING_INFO' => 'Scheme No.54, Vijay Nagar Indore, Madhya Pradesh 452001',
    'INVOICE_PAYMENT_INFO' => 'Credit Card: Visa **** **** **** 0001'
];
