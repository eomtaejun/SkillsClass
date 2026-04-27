<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class TestController extends Controller{
        public function products() {
            DB::execute("INSERT INTO `products` VALUES (:id, :title, :detail, :price, :discount, :category, :img, now());", [
                ":id"=>$_POST['id'],
                ":title"=>$_POST['title'],
                ":detail"=>$_POST['detail'],
                ":price"=>$_POST['price'],
                ":discount"=>$_POST['discount'],
                ":category"=>$_POST['category'],
                ":img"=>$_POST['img']
            ]);
            $this->json("success");
        }

        public function notifications() {
            DB::execute("INSERT INTO `notifications` VALUES (:id, :title, :type, :date);", [
                ":id"=>$_POST['id'],
                ":title"=>$_POST['title'],
                ":type"=>$_POST['type'],
                ":date"=>$_POST['date']
            ]);
            $this->json("success");
        }
    }