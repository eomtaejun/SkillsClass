<?php
    namespace App\Core;

    class DB{
        private static $instance;

        private static function getConnection() {
            if(is_null(self::$instance)){
                self::$instance=new \PDO('mysql:host=localhost;dbname=2025skills04080324;charset=utf8mb4', 'root', '', array(
                    \PDO::ATTR_DEFAULT_FETCH_MODE=>\PDO::FETCH_OBJ,
                    \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_WARNING
                ));
            }

            return self::$instance;
        }


        public static function execute($sql, $params=null) {
            $p=self::getConnection()->prepare($sql);
            $p->execute($params);
            return $p;
        }

        public static function fetch($sql, $params=null) {
            return self::execute($sql, $params)->fetch();
        }

        public static function fetchAll($sql, $params=null) {
            return self::execute($sql, $params)->fetchAll();
        }
    }