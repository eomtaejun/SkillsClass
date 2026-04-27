<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class ProductController extends Controller{
        public function edit() {
            $product=DB::fetch("SELECT * FROM `products` WHERE `id`=:id;", [":id"=>$_GET['id']]);
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/product_edit.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function edit_action() {
            if(!$this->required(["title", "detail", "price", "category"])){
                $this->error("필수 항목 누락")->back();
            }
            if($_FILES['img']['error']!==0){
                $this->error("상품사진 항목 누락")->back();
            }
            if(!preg_match("/^image\/.+$/", $_FILES['img']['type'])){
                $this->error("이미지 파일만 업로드 가능")->back();
            }

            $image=$this->fileUpload($_FILES['img'], $_POST['category']);

            if($_POST['discount']){
                DB::execute("UPDATE `products` SET `discount`=NULL WHERE `category`=:category;", [":category"=>$_POST['category']]);
            }

            DB::execute("UPDATE `products` SET `title`=:title, `detail`=:detail, `price`=:price, `discount`=:discount, `category`=:category, `img`=:img, `date`=now() WHERE `id`=:id;", [
                ":title"=>$_POST['title'],
                ":detail"=>$_POST['detail'],
                ":price"=>$_POST['price'],
                ":discount"=>$_POST['discount'],
                ":category"=>$_POST['category'],
                ":img"=>$image,
                ":id"=>$_GET['id']
            ]);
            $this->success("상품 수정 완료")->move("/admins/products");
        }

        public function delete_action() {
            DB::execute("DELETE FROM `products` WHERE `id`=:id;", [":id"=>$_GET['id']]);
            DB::execute("DELETE FROM `shoppingcart` WHERE `product_id`=:id;", [":id"=>$_GET['id']]);
            $this->success("상품 삭제 완료")->back();
        }

        public function add() {
            $product=DB::fetch("SELECT * FROM `products` WHERE `id`=:id;", [":id"=>$_GET['id']]);
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/product_add.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function add_action() {
            if(!$this->required(["title", "detail", "price", "category"])){
                $this->error("필수 항목 누락")->back();
            }
            if($_FILES['img']['error']!==0){
                $this->error("상품사진 항목 누락")->back();
            }
            if(!preg_match("/^image\/.+$/", $_FILES['img']['type'])){
                $this->error("이미지 파일만 업로드 가능")->back();
            }

            $image=$this->fileUpload($_FILES['img'], $_POST['category']);

            if($_POST['discount']){
                DB::execute("UPDATE `products` SET `discount`=NULL WHERE `category`=:category;", [":category"=>$_POST['category']]);
            }

            DB::execute("INSERT INTO `products` VALUES(null, :title, :detail, :price, :discount, :category, :img, now());", [
                ":title"=>$_POST['title'],
                ":detail"=>$_POST['detail'],
                ":price"=>$_POST['price'],
                ":discount"=>$_POST['discount'],
                ":category"=>$_POST['category'],
                ":img"=>$image
            ]);
            $this->success("상품 수정 완료")->move("/admins/products");
        }

        public function getproducts() {
            $products=DB::fetchAll("SELECT * FROM `products`;");
            $this->json($products);
        }
    }