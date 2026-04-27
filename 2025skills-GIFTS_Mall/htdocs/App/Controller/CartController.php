<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class CartController extends Controller{
        public function add() {
            if(!isset($_SESSION['user'])){
                $this->json(["msg"=>"not user"]);
            }
            $fetch=DB::fetch("SELECT * FROM `shoppingcart` WHERE `user_id`=:user_id AND `product_id`=:product_id;", [
                ":user_id"=>$_SESSION['user']->id,
                ":product_id"=>$_GET['id']
            ]);
            if($fetch){
                DB::execute("UPDATE `shoppingcart` SET `quantity`=:quantity WHERE `user_id`=:user_id AND `product_id`=:product_id;", [
                    ":quantity"=>$fetch->quantity+1,
                    ":user_id"=>$_SESSION['user']->id,
                    ":product_id"=>$_GET['id']
                ]);
            } else{
                DB::execute("INSERT INTO `shoppingcart` VALUES (null, :user_id, :product_id, 1);", [
                    ":user_id"=>$_SESSION['user']->id,
                    ":product_id"=>$_GET['id']
                ]);
            }
            $this->json(["msg"=>"success"]);
        }

        public function getcart() {
            if(!isset($_SESSION['user'])){
                $this->json([]);
            }
            $products=DB::fetchAll("SELECT * FROM `shoppingcart` AS s LEFT JOIN `products` AS p ON s.product_id=p.id WHERE s.user_id=:user_id;", [":user_id"=>$_SESSION['user']->id]);
            $this->json($products);
        }

        public function edit() {
            DB::execute("UPDATE `shoppingcart` SET `quantity`=:quantity WHERE `user_id`=:user_id AND `product_id`=:product_id;", [
                ":quantity"=>$_POST['quantity'],
                ":user_id"=>$_SESSION['user']->id,
                ":product_id"=>$_POST['id']
            ]);
            $this->json("success");
        }
    }