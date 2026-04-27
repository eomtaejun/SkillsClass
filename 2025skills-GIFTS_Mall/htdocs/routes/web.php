<?php
    // 테스트
    $Route->post("/test/products", "TestController@products");
    $Route->post("/test/notifications", "TestController@notifications");

    // 페이지 로드
    $Route->get("/", "MainController@index");
    $Route->get("/introduction", "MainController@introduction");
    $Route->get("/products/all", "MainController@products_all");
    $Route->get("/products/popularity", "MainController@products_popularity");
    $Route->get("/shoppingcart", "MainController@shoppingcart");
    $Route->get("/admins/users", "MainController@users");
    $Route->get("/admins/products", "MainController@products");
    $Route->get("/admins/notifications", "MainController@notifications");

    // 로그인/회원가입
    $Route->post("/users/join_action", "UserController@join_action");
    $Route->post("/users/login_action", "UserController@login_action");
    $Route->get("/users/logout_action", "UserController@logout_action");

    // 공지사항
    $Route->get("/js/notification", "NoticeController@get");
    $Route->get("/admins/notifications/delete_action", "NoticeController@delete_action");
    $Route->get("/admins/notifications/edit", "NoticeController@edit");
    $Route->post("/admins/notifications/edit_action", "NoticeController@edit_action");
    $Route->get("/admins/notifications/add", "NoticeController@add");
    $Route->post("/admins/notifications/add_action", "NoticeController@add_action");

    // 장바구니
    $Route->get("/products/add", "CartController@add");
    $Route->get("/js/cart", "CartController@getcart");
    $Route->post("/cart/edit", "CartController@edit");
    
    // 상품관리
    $Route->get("/js/products", "ProductController@getproducts");
    $Route->get("/admins/produdts/edit", "ProductController@edit");
    $Route->post("/admins/products/edit_action", "ProductController@edit_action");
    $Route->get("/admins/products/delete_action", "ProductController@delete_action");
    $Route->get("/admins/products/add", "ProductController@add");
    $Route->post("/admins/products/add_action", "ProductController@add_action");