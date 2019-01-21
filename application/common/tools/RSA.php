<?php
    /**
     * Created by PhpStorm.
     * User: tengjufeng
     * Date: 2018/12/28
     * Time: 12:28 PM
     */

    /**
     * RSA算法类
     * 签名及密文编码：base64字符串/十六进制字符串/二进制字符串流
     * 填充方式: PKCS1Padding（加解密）/NOPadding（解密）
     *
     * Notice:Only accepts a single block. Block size is equal to the RSA key size!
     * 如密钥长度为1024 bit，则加密时数据需小于128字节，加上PKCS1Padding本身的11字节信息，所以明文需小于117字节
     *
     * 加入生成私匙和公匙
     *
     * PHP服务端与客户端交互或者提供开放API时，通常需要对敏感的数据进行加密，这时候rsa非对称加密就能派上用处了。
     *
     * 举个通俗易懂的例子，假设我们再登录一个网站，发送账号和密码，请求被拦截了。
     *
     * 密码没加密，那攻击者就直接拿到了密码，这是最搓的。
     * 密码加密了，是不可逆加密，那攻击者只需要模拟那个请求即可登录。
     * 密码加密了，是可逆加密，其中携带有时间等参数，后台可以根据时间等参数来判断是否有效，但因为是在前端加密，其加密方式也能在代码中找到，找到加密方式就可以得出解密方式。
     * 但是如果我们使用非对称加密就可以避免以上问题。
     *
     * 非对称加密算法需要两个密钥来进行加密和解密，这两个秘钥是公开密钥（public key，简称公钥）和私有密钥（private key，简称私钥）。
     *
     * 工作过程如下，甲乙之间使用非对称加密的方式完成了重要信息的安全传输。
     *
     * 不说其他的了 上源码
     * public function index(){
     *
     * header('Content-Type:text/html;Charset=utf-8;');
     *
     * $string = isset($_GET['a']) ? $_GET['a'] : '测试123';
     *
     * //证书路径
     * $pubfile = ROOT_PATH.'ssl/test.crt';
     * $prifile = ROOT_PATH.'ssl/test.pem';
     * //apache路径下的openssl.conf文件路径
     * $openssl_config_path = "D:/phpStudy/Apache/conf/openssl.cnf";
     *
     * $rsa =new Rsa($pubfile,$prifile,$openssl_config_path);
     *
     * echo "<pre>";
     * //生成签名
     * echo "\n签名的字符串:\n$string\n\n";
     * $sign = $rsa->sign($string);
     * echo "\n生成签名的值:\n$sign";
     *
     * //验证签名
     * $p=$rsa->verify($string, $sign);
     * echo "\n验证签名的值:\n$p";
     *
     *
     * //加密
     * echo "\n\r加密的字符串:\n$string\n\n";
     * $x = $rsa->encrypt($string);
     * echo "\n生成加密的值:\n$x";
     *
     * //解密
     * $y = $rsa->decrypt($x);
     * echo "\n解密的值:\n$y";
     * echo "</pre>";
     *
     * //创建新的密匙
     * echo "\n创建新的密匙:\n";
     * $rsa->buildNewKey();
     *
     * }
     */

    namespace app\common\tools;
    class Rsa {
        private $pubKey = null;
        private $priKey = null;
        //apache路径下的openssl.conf文件路径
        private $opensslConfigPath = "D:/phpStudy/Apache/conf/openssl.cnf";

        /**
         * 构造函数
         * @param string 公钥文件（验签和加密时传入）
         * @param string 私钥文件（签名和解密时传入）
         */
        public function __construct ($public_key_file = '', $private_key_file = '', $openssl_config_path = "") {
            if ($public_key_file) {
                $this->_getPublicKey($public_key_file);
            }
            if ($private_key_file) {
                $this->_getPrivateKey($private_key_file);
            }
            if ($openssl_config_path) {
                $this->opensslConfigPath = $openssl_config_path;
            }
        }

        /**
         * 自定义错误处理
         */

        private function _error ($msg) {
            die('RSA Error:' . $msg); //TODO
        }

        /**
         * 生成签名
         *
         * @param string 签名材料
         * @param string 签名编码（base64/hex/bin）
         * @return 签名值
         */

        public function sign ($data, $code = 'base64') {
            $ret = false;
            if (openssl_sign($data, $ret, $this->priKey)) {
                $ret = $this->_encode($ret, $code);
            }
            return $ret;
        }

        /**
         * 验证签名
         *
         * @param string 签名材料
         * @param string 签名值
         * @param string 签名编码（base64/hex/bin）
         * @return bool
         */

        public function verify ($data, $sign, $code = 'base64') {
            $ret = false;
            $sign = $this->_decode($sign, $code);
            if ($sign !== false) {
                switch (openssl_verify($data, $sign, $this->pubKey)) {
                    case 1:
                        $ret = true;
                        break;
                    case 0:
                    case -1:
                    default:
                        $ret = false;
                }
            }
            return $ret;
        }

        /**
         * 加密
         *
         * @param string 明文
         * @param string 密文编码（base64/hex/bin）
         * @param int 填充方式（貌似php有bug，所以目前仅支持OPENSSL_PKCS1_PADDING）
         * @return string 密文
         */

        public function encrypt ($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING) {
            $ret = false;
            if (!$this->_checkPadding($padding, 'en'))
                $this->_error('padding error');
            if (openssl_public_encrypt($data, $result, $this->pubKey, $padding)) {
                $ret = $this->_encode($result, $code);
            }
            return $ret;
        }

        /**
         * 解密
         *
         * @param string 密文
         * @param string 密文编码（base64/hex/bin）
         * @param int 填充方式（OPENSSL_PKCS1_PADDING / OPENSSL_NO_PADDING）
         * @param bool 是否翻转明文（When passing Microsoft CryptoAPI-generated RSA cyphertext, revert the bytes in the block）
         * @return string 明文
         */

        public function decrypt ($data, $code = 'base64', $padding = OPENSSL_PKCS1_PADDING, $rev = false) {
            $ret = false;
            $data = $this->_decode($data, $code);
            if (!$this->_checkPadding($padding, 'de'))
                $this->_error('padding error');
            if ($data !== false) {
                if (openssl_private_decrypt($data, $result, $this->priKey, $padding)) {
                    $ret = $rev ? rtrim(strrev($result), "\0") : '' . $result;
                }
            }
            return $ret;
        }

        /**
         * 生产新密匙
         * Power by Mikkle
         * QQ:776329498
         */
        public function buildNewKey () {

            $config = [
                'private_key_bits' => 2048,
            ];
            $resource = openssl_pkey_new($config);
            openssl_pkey_export($resource, $privateKey);
            if (!$resource) {
                $config['config'] = $this->opensslConfigPath;
                $resource = openssl_pkey_new($config);
                openssl_pkey_export($resource, $privateKey, null, $config);
            }
            $detail = openssl_pkey_get_details($resource);
            $publicKey = $detail['key'];
            echo "<pre>";
            echo "$publicKey";

            echo "$privateKey";
            echo "</pre>";

        }

        // 私有方法

        /**
         * 检测填充类型
         * 加密只支持PKCS1_PADDING
         * 解密支持PKCS1_PADDING和NO_PADDING
         *
         * @param int 填充模式
         * @param string 加密en/解密de
         * @return bool
         */

        private function _checkPadding ($padding, $type) {
            if ($type == 'en') {
                switch ($padding) {
                    case OPENSSL_PKCS1_PADDING:
                        $ret = true;
                        break;
                    default:
                        $ret = false;
                }
            } else {
                switch ($padding) {
                    case OPENSSL_PKCS1_PADDING:
                    case OPENSSL_NO_PADDING:
                        $ret = true;
                        break;
                    default:
                        $ret = false;
                }
            }
            return $ret;
        }

        private function _encode ($data, $code) {
            switch (strtolower($code)) {
                case 'base64':
                    $data = base64_encode('' . $data);
                    break;
                case 'hex':
                    $data = bin2hex($data);
                    break;
                case 'bin':
                default:
            }
            return $data;
        }

        private function _decode ($data, $code) {
            switch (strtolower($code)) {
                case 'base64':
                    $data = base64_decode($data);
                    break;
                case 'hex':
                    $data = $this->_hex2bin($data);
                    break;
                case 'bin':
                default:
            }
            return $data;
        }

        private function _getPublicKey ($file) {
            $key_content = $this->_readFile($file);

            if ($key_content) {
                $this->pubKey = openssl_get_publickey($key_content);
            }

        }

        private function _getPrivateKey ($file) {
            $key_content = $this->_readFile($file);

            if ($key_content) {
                $this->priKey = openssl_get_privatekey($key_content);
            }
        }

        private function _readFile ($file) {
            $ret = false;
            if (!file_exists($file)) {
                $this->_error("The file {$file} is not exists");
            } else {
                $ret = file_get_contents($file);

            }
            return $ret;
        }

        private function _hex2bin ($hex = false) {
            $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
            return $ret;
        }

    }