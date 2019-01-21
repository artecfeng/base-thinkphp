<?php

    /**
     * Created by PhpStorm.
     * User: feng
     * Version: 0.0.1
     * php curl 类库
     */

    namespace app\common\tools;
    class Utils {
        private static $instance = null;

        private function __construct () {
        }

        private function __clone () {
            // TODO: Implement __clone() method.
        }

        /**
         * @param $url
         *
         * @return string http get 请求
         */
        public static function http_get ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            if (!curl_exec($ch)) {
                $data = '';
            } else {
                $data = curl_multi_getcontent($ch);
            }
            curl_close($ch);

            return $data;
        }

        /**
         * @return \CurlUtils|null单例模式
         */
        public static function getInstance () {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         * @param $url
         * @param $json_str
         *
         * @return 发送post请求，参数为json
         */
        public static function http_post_json ($url, $json_str) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_str);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($json_str)
            ));
            $response = curl_exec($ch);
            //$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
            curl_close($ch);

            return $response;

        }

        /**
         * @param string $url
         * @param array $post_data
         *
         * @return http post 请求 参数array
         */
        public static function http_post ($url = '', $post_data = array()) {
            if (empty($url) || empty($post_data)) {
                return false;
            }

            $o = "";
            foreach ($post_data as $k => $v) {
                $o .= "$k=" . urlencode($v) . "&";
            }
            $post_data = substr($o, 0, -1);

            $postUrl = $url;
            $curlPost = $post_data;
            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $postUrl);//
            curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            $data = curl_exec($ch);//运行curl
            curl_close($ch);

            return $data;
        }

        public static function imgToBase64 ($img_file) {
            /**
             * 获取图片的Base64编码(不支持url)
             * @date 2017-02-20 19:41:22
             *
             * @param $img_file 传入本地图片地址
             *
             * @return string
             */


            $img_base64 = '';
            if (file_exists($img_file)) {
                $app_img_file = $img_file; // 图片路径
                $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

                //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
                $fp = fopen($app_img_file, "r"); // 图片是否可读权限

                if ($fp) {
                    $filesize = filesize($app_img_file);
                    $content = fread($fp, $filesize);
                    $file_content = chunk_split(base64_encode($content)); // base64编码
                    switch ($img_info[2]) {           //判读图片类型
                        case 1:
                            $img_type = "gif";
                            break;
                        case 2:
                            $img_type = "jpg";
                            break;
                        case 3:
                            $img_type = "png";
                            break;
                    }

                    $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

                }
                fclose($fp);
            }

            return $img_base64; //返回图片的base64


            //输出Base64编码
        }

        /**
         * @param $str
         * @return 判断是否是utf8编码
         */
        public static function is_utf8 ($str) {
            $len = strlen($str);
            for ($i = 0; $i < $len; $i++) {
                $c = ord($str[$i]);
                if ($c > 128) {
                    if (($c > 247)) {
                        return false;
                    } elseif ($c > 239) {
                        $bytes = 4;
                    } elseif ($c > 223) {
                        $bytes = 3;
                    } elseif ($c > 191) {
                        $bytes = 2;
                    } else {
                        return false;
                    }
                    if (($i + $bytes) > $len) {
                        return false;
                    }
                    while ($bytes > 1) {
                        $i++;
                        $b = ord($str[$i]);
                        if ($b < 128 || $b > 191) {
                            return false;
                        }
                        $bytes--;
                    }
                }
            }
            return true;
        }

        /**
         * @param $str
         * @return 判断是否是base64编码
         */
        public static function func_is_base64 ($str) {
            if (@preg_match('/^[0-9]*$/', $str) || @preg_match('/^[a-zA-Z]*$/', $str)) {
                return false;
            } elseif (self::is_utf8(base64_decode($str)) && base64_decode($str) != '') {
                return true;
            }
            return false;
        }

        /**
         * 判断字符串是否base64编码
         */
        public static function is_base64 ($str) {
            return $str == base64_encode(base64_decode($str)) ? true : false;
        }

        /**
         * @param $code
         * @param string $data
         * @param string $msg
         * @return \think\response\Json
         */
        public static function json ($code, $data = '', $msg = '') {
            return json([
                'code' => $code,
                'data' => $data,
                'msg'  => $msg
            ]);
        }
    }