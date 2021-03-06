<?php

    namespace app\admin\controller;


    use CasbinAdapter\Think\Facades\Casbin;
    use think\Controller;


    abstract class Base extends Controller {

        /**
         * 16个字符
         * @var string
         */
        protected $iv = '';

        /**
         * @return string
         */
        public function getIv (): string {
            return $this->iv;
        }

        /**
         * @param string $iv
         */
        public function setIv (string $iv) {
            $this->iv = $iv;
        }

        /**
         * 初始化的方法
         */
        protected function initialize () {
            parent::initialize(); // TODO: Change the autogenerated stub
            $this->checkRequestAuth();
            $this->assign('site_name', config('admin.site_name'));

        }

        /**
         * 校验每次app请求的数据是否合法
         */
        private function checkRequestAuth () {
            //1、获取header头中的数据
            $headers = \request()->header();
            if (empty($headers['sign'])) {
                // throw new ApiException('sign 不存在', 400);
            }
            return $headers;
        }

        private function casbin () {
            $sub = 'alice'; // 想要访问资源的用户。
            $obj = 'data1'; // 将被访问的资源。
            $act = 'read'; // 用户对资源执行的操作。

            if (true === Casbin::enforce($sub, $obj, $act)) {
                // permit alice to read data1
                echo 'permit alice to read data1';
            } else {
                // deny the request, show an error
            }
            //CasbinRule::
        }

        /**
         * 响应
         * @param int $code
         * @param string $msg
         * @param array $data
         */
        protected function response ($statusCode = 200, $msg = '', $data = [], $code = 200) {

            $redata = [
                'code' => $statusCode,
                'msg'     => $msg,
                'data'    => $data
            ];
            if ($this->iv != '') {
                $redata = array_merge($redata, ['iv' => $this->iv]);
            }
            return json($redata)->code($code);
        }

    }
