<?php

    namespace app\api\controller;

    use app\common\exception\ApiException;
    use app\common\tools\AES;
    use think\facade\Log;
    use think\helper\Str;
    use think\Request;

    /**
     * @title 测试demo
     * @description 接口说明
     * @group 接口分组
     * @header name:key require:1 default: desc:秘钥(区别设置)
     * @param name:public type:int require:1 default:1 other: desc:公共参数(区别设置)
     */
    class IndexController extends Base {
        /**
         * @title 测试demo接口
         * @description 接口说明
         * @author 开发者
         * @url /index/demo
         * @method GET
         *
         * @header name:device require:1 default: desc:设备号
         *
         * @param name:id type:int require:1 default:1 other: desc:唯一ID
         *
         * @return name:名称
         * @return mobile:手机号
         * @return list_messages:消息列表@
         * @list_messages message_id:消息ID content:消息内容
         * @return object:对象信息@!
         * @object attribute1:对象属性1 attribute2:对象属性2
         * @return array:数组值#
         * @return list_user:用户列表@
         * @list_user name:名称 mobile:手机号 list_follow:关注列表@
         * @list_follow user_id:用户id name:名称
         */
        public function index (Request $request) {
            header('Access-Control-Allow-Origin:*');
            $post = $request->post();
            $data = $request->post('data');
            $key = $request->post('key');
            $iv = $request->post('iv');
            //Log::error($data);
            //            $data = 'tw97f6U27iiNaaxYZAL5dzZ47V8QaT461UglXwqVNW8ODUHmey/TRfAq5EjyXDg2+aUc/05MxC6nd5K5tr+4wTDKa50zWaoFbe/fbcq2iIw=';
            //            $key = '24b4mwdKMc22ph6Y';
            //            $iv = 'HkkyftMeiMNmBe3c';
            $msg = AES::decrypt($post['data'], $key, $iv);
            return json(json_decode($msg));
        }

        public function test (Request $request) {
            //
            $key = 'lifespace350e419';
            if ($request->isGet()) {
                $iv = Str::random();
                $data = [
                    'dd' => 'dd',
                    'cc' => 'cc'
                ];
                $data = json_encode($data);
                // $re = AES::encrypt($data, $key, $iv);
                $this->setIv($iv);

                return $this->response(AES::encrypt($data, $key, $iv), 'success');
            }
            $iv = $request->post('iv');
            $data = $request->post('data');

            $redata = AES::decrypt(urldecode($data), $key, $iv);
            return $redata;
        }


        /**
         * @title 登录接口
         * @description 接口说明
         * @author 开发者
         * @url /api/demo
         * @method GET
         * @module 用户模块
         * @param name:name type:int require:1 default:1 other: desc:用户名
         * @param name:pass type:int require:1 default:1 other: desc:密码
         *
         * @return name:名称
         * @return mobile:手机号
         *
         */
        public function hello ($name = 'ThinkPHP5') {
            return 'hello,' . $name;
        }
    }
