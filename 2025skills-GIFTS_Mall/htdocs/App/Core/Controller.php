<?php
    namespace App\Core;

    class Controller{
        protected $viewFolder=SOURCE . '/views';

        protected $msg;
        protected $msgType;

        public function __construct()
        {
            $this->flash();
        }

        private function flash() {
            if(isset($_SESSION['msg'])){
                $this->msg=$_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            if(isset($_SESSION['msgType'])){
                $this->msgType=$_SESSION['msgType'];
                unset($_SESSION['msgType']);
            }
        }


        protected function success($msg) {
            return $this->addMsg("success", $msg);
        }

        protected function error($msg) {
            return $this->addMsg("danger", $msg);
        }

        private function addMsg($msgType, $msg) {
            $_SESSION['msg']=$msg;
            $_SESSION['msgType']=$msgType;

            return $this;
        }


        protected function move($uri) {
            header("Location: " . $uri);
            exit;
        }

        protected function back() {
            $uri=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            $this->move($uri);
        }


        protected function json($value, $code=200) {
            header("Content-Type: application/json", true, $code);
            echo json_encode($value, JSON_UNESCAPED_UNICODE);
            exit;
        }

        protected function required($values) {
            foreach($values as $value){
                if(!isset($_POST[$value]) || is_null($_POST[$value]) || empty(trim($_POST[$value]))) return false;
            }

            return true;
        }

        protected function fileUpload($file, $category) {
            $filename=time() . "_" . $file['name'];
            
            if(file_exists(PRODUCTS . $category . '/' . $filename)) return false;

            if(!move_uploaded_file($file['tmp_name'], PRODUCTS . $category . '/' . $filename)) return false;

            return $filename;
        }
    }