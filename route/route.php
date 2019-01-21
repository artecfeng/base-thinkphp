<?php
    // +----------------------------------------------------------------------
    // | ThinkPHP [ WE CAN DO IT JUST THINK ]
    // +----------------------------------------------------------------------
    // | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
    // +----------------------------------------------------------------------
    // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
    // +----------------------------------------------------------------------
    // | Author: liu21st <liu21st@gmail.com>
    // +----------------------------------------------------------------------
    use Zewail\Api\Facades\ApiRoute;


    Route::get('hello/:name', 'index/hello');


    ApiRoute::version('v1', function () {
        //TODO:: 可以是thinkphp自带的路由
        Route::post('think', function () {
            return 'hello,ThinkPHP5! v1';
        });
        //Route::resource('casbin', 'api/Casbin');
        Route::group('/api', function () {

        })->prefix('api/');
        /**
         * 后台路由
         */
        Route::group('admin', function () {
            Route::any('/', 'Index/index');
            Route::any('/login', 'Login/login');
            Route::any('/logina', 'Login/loginAjax');
            Route::any('/logout', 'Login/logout');

        })->prefix('admin/');

    });

    ApiRoute::version('v2', function () {
        //TODO:: 可以是thinkphp自带的路由
        Route::post('think', function () {
            return 'hello,ThinkPHP5 v2!';
        });
        Route::resource('casbin', 'api/Casbin');
    });
