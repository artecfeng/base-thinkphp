<?php

    namespace app\admin\controller;

    use app\common\exception\ApiException;
    use think\Controller;
    use think\facade\Validate;
    use think\Request;

    class LoginController extends Base {
        /**
         * 登录功能
         *
         * @return \think\Response
         */
        public function login () {
            //
            // $site_name = config('admin.site_name');
            $token = $this->request->token();
            return view('/logins', compact('token'));
        }

        /**
         * 登录行为 post
         *
         * @return \think\Response
         */
        public function loginAjax (Request $request) {
            //
            if ($request->isPost()) {
                //1、验证数据
                $rule = [
                    'username' => 'require|max:25|token',
                    'password' => 'require|max:25'
                ];
                $validate = Validate::make($rule);
                if (!$validate->check($request->post())) {
                    // throw new ApiException($validate->getError());
                    return $this->response(404, $validate->getError(), $request->token());
                }
                return $this->response(200, 'login success');
            }
            return $this->response(200, 'request error', $request->token());
        }

        /**
         * 保存新建的资源
         *
         * @param  \think\Request $request
         * @return \think\Response
         */
        public function save (Request $request) {
            //
        }

        /**
         * 显示指定的资源
         *
         * @param  int $id
         * @return \think\Response
         */
        public function read ($id) {
            //
        }

        /**
         * 显示编辑资源表单页.
         *
         * @param  int $id
         * @return \think\Response
         */
        public function edit ($id) {
            //
        }

        /**
         * 保存更新的资源
         *
         * @param  \think\Request $request
         * @param  int $id
         * @return \think\Response
         */
        public function update (Request $request, $id) {
            //
        }

        /**
         * 删除指定资源
         *
         * @param  int $id
         * @return \think\Response
         */
        public function delete ($id) {
            //
        }
    }
