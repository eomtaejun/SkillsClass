<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class UserController extends Controller{
        // 회원가입
        public function join_action() {
            $fetch=DB::fetch("SELECT * FROM `users` WHERE `user_id`=:user_id;", [":user_id"=>$_POST['userid']]);
            if($fetch) $this->json(["msg"=>"exists"]); // 중복 ID

            // 암호화
            $salt=bin2hex(random_bytes(16));
            $hash=hash("sha256", $salt . $_POST['userpass']);

            DB::execute("INSERT INTO `users` VALUES (null, :user_id, :password, :name, :email, now(), :salt, false);", [
                ":user_id"=>$_POST['userid'],
                ":password"=>$hash,
                ":name"=>$_POST['username'],
                ":email"=>$_POST['email'],
                ":salt"=>$salt
            ]);
            $this->json(["msg"=>"success"]);
        }

        // 로그인
        public function login_action() {
            $fetch=DB::fetch("SELECT * FROM `users` WHERE `user_id`=:user_id;", [":user_id"=>$_POST['userid']]);
            if(!$fetch) $this->json(["msg"=>"undefined"]); // 유저 정보 없음

            $hash=hash("sha256", $fetch->salt . $_POST['userpass']);

            $user=DB::fetch("SELECT * FROM `users` WHERE `user_id`=:user_id AND `password`=:password;", [
                ":user_id"=>$_POST['userid'],
                ":password"=>$hash
            ]);
            if(!$user) $this->json(["msg"=>"mismatch"]); // 비밀번호 불일치

            $_SESSION['user']=$user;
            $this->json(["msg"=>"success"]);
        }

        // 로그아웃
        public function logout_action() {
            unset($_SESSION['user']);
            $this->success("로그아웃 완료")->move("/");
        }
    }