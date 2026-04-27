<?php
    namespace App\Controller;

    use App\Core\Controller;
    use App\Core\DB;

    class NoticeController extends Controller{
        public function get() {
            $notifications=DB::fetchAll("SELECT * FROM `notifications`");
            $this->json($notifications);
        }

        public function delete_action() {
            DB::execute("DELETE FROM `notifications` WHERE `id`=:id;", [":id"=>$_GET['id']]);
            $this->success("공지사항 삭제 완료")->back();
        }

        public function edit() {
            $notice=DB::fetch("SELECT * FROM `notifications` WHERE `id`=:id;", [":id"=>$_GET['id']]);
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/notification_edit.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function edit_action() {
            DB::execute("UPDATE `notifications` SET `title`=:title, `type`=:type, `date`=now() WHERE `id`=:id;", [
                ":title"=>$_POST['title'],
                ":type"=>$_POST['type'],
                ":id"=>$_GET['id']
            ]);
            $this->success("공지사항 수정 완료")->move("/admins/notifications");
        }

        public function add() {
            require_once $this->viewFolder . '/include/header.php';
            require_once $this->viewFolder . '/admins/notification_add.php';
            require_once $this->viewFolder . '/include/footer.php';
        }

        public function add_action() {
            if(!$this->required(["title", "type"])){
                $this->error("필수 항목 누락")->back();
            }

            DB::execute("INSERT INTO `notifications` VALUES (null, :title, :type, now());", [
                ":title"=>$_POST['title'],
                ":type"=>$_POST['type']
            ]);
            $this->success("공지사항 추가 완료")->move("/admins/notifications");
        }
    }