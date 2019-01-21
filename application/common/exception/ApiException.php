<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/12/26
     * Time: 5:04 PM
     */

    namespace app\common\exception;

    use think\Exception;

    class ApiException extends Exception {

        public $message = '';
        public $code = 0;
        public $statusCode = 0;

        /**
         * ApiException constructor.
         * @param string $message 信息
         * @param $code          http状态码
         */
        public function __construct ($message = '', $code = 0, $statusCode = 0) {
            $this->message = $message;
            $this->code = $code;
            $this->httpCode = $statusCode;
        }

        public function getStatusCode () {
            return $this->statusCode;
        }


    }