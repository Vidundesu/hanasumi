<?php
// app/Config/routes.php
return [
    //admin routes
    'POST /backend/api/admin/login'=> 'AdminController@login',
    'POST /backend/api/admin/dashboard'=>'AdminController@getDashboardStat',
    'POST /backend/api/admin/pending/orders'=>'AdminController@getPendingOrder',
    'POST /backend/api/admin/deliver/staff'=>'AdminController@getDelivers',
    'POST /backend/api/admin/products/all'=>'AdminController@getProducts',

    //customer routes
    'GET /backend/api/db-test' => 'TestController@testDb',
    'POST /backend/api/checkAuth' => 'UserController@checkAuth',
    'POST /backend/api/customer/login' => 'UserController@login',

    'GET /backend/api/customer' => 'UserController@getCustomer',
    'POST /backend/api/customer/update/password' => 'UserController@updatePassword',
    'GET /backend/api/customer/delete' => 'UserController@deleteAccount',

    'POST /backend/api/registerUser' => 'UserController@registerUser',
    'POST /backend/api/customer/logout' => 'UserController@logout',

    //product API routes
    'GET /backend/api/product/flowers' => 'ProductController@getFlowerProducts',
    'GET /backend/api/product/cake' => 'ProductController@getCakeProducts',
    'GET /backend/api/product/custom' => 'ProductController@getCustomProducts',

    //order Api routes
    'POST /backend/api/order/make' => 'OrderController@placeOrder',
];

?>