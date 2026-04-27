<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class MainController extends Controller{
        public function index() {
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/main/index.php';
            require_once $this->viewFolder . '/include/footer.php';
        }
        
        public function introduction() {
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/main/introduction.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function products_all() {
            $products=DB::fetchAll("SELECT * FROM `products`;");
            // $categories=DB::fetchAll("SELECT `category` FROM `products` GROUP BY `category`;");
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/main/products_all.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function products_popularity() {
            $products=DB::fetchAll("SELECT * FROM `products` WHERE `discount` IS NOT NULL;");
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/main/products_popularity.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function shoppingcart() {
            if(!isset($_SESSION['user'])){
                $this->error("로그인 후 이용 가능")->back();
            }

            $products=DB::fetchAll("SELECT p.*, s.quantity AS 'quantity' FROM `shoppingcart` AS s LEFT JOIN `products` AS p ON s.product_id=p.id WHERE s.user_id=:user_id;", [":user_id"=>$_SESSION['user']->id]);
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/main/shoppingcart.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function users() {
            if(!isset($_SESSION['user']) || $_SESSION['user']->admin==false){
                $this->error("관리자만 접근 가능")->back();
            }
            $users=DB::fetchAll("SELECT * FROM `users`;");
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/users.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function products() {
            if(!isset($_SESSION['user']) || $_SESSION['user']->admin==false){
                $this->error("관리자만 접근 가능")->back();
            }
            $products=DB::fetchAll("SELECT * FROM `products`;");
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/products.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function notifications() {
            if(!isset($_SESSION['user']) || $_SESSION['user']->admin==false){
                $this->error("관리자만 접근 가능")->back();
            }
            $notifications=DB::fetchAll("SELECT * FROM `notifications` ORDER BY `date` DESC, `id` DESC;");
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/notifications.php';
            require_once $this->viewFolder . '/include/footer.php';
        }
    }