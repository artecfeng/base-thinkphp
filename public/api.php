<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/12/26
     * Time: 6:41 PM
     */

    namespace think;

    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../vendor/artecfeng');
    // 加载框架基础引导文件
    require __DIR__ . '/../thinkphp/base.php';
    // 添加额外的代码
    Container::get('app')->path(APP_PATH)->run()->send();