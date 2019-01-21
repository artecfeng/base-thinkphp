<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/12/26
     * Time: 4:46 PM
     */

    namespace app\common\responseMsg;

    class ResponseMsg {
        /**
         * @var int
         */
        public $code = 0;
        /**
         * @var null
         */
        public $msg = null;
        /**
         * @var
         */
        public $data;

        public function __construct ($code = 0, $msg = null, $data) {
            $this->code = $code;
            $this->msg = $msg;
            $this->data = $data;
        }


        /**
         * @param null $msg
         * @return ResponseMsg
         */
        public static function error ($msg = null) {
            return new self(404, $msg, $data = []);
        }

        /**
         * @param $code
         * @param $msg
         * @param $data
         * @return ResponseMsg
         */
        public static function success ($code, $msg, $data) {
            return new self($code, $msg, $data);
        }

    }