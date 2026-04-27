<?php
    namespace App\Core;

    class Route{
        private $GET;
        private $POST;

        public function get($uri, $action) {
            $this->addRoute("GET", $uri, $action);
        }

        public function post($uri, $action) {
            $this->addRoute("POST", $uri, $action);
        }

        private function addRoute($method, $uri, $action) {
            $this->{$method}[]=$this->parseRoute($uri, $action);
        }

        private function parseRoute($uri, $action) {
            $params=explode("@", $action);

            return [
                'uri'=>$uri,
                'controller'=>"App\\Controller\\" . $params[0],
                'action'=>$params[1]
            ];
        }


        private function getURI() {
            $uri='/';

            if(isset($_GET['uri'])){
                $uri=rtrim('/' . $_GET['uri'], '/');

                $uri=filter_var($uri, FILTER_SANITIZE_URL);
            }

            return $uri;
        }

        public function dispatch() {
            $uri=$this->getURI();
            $method=$_SERVER['REQUEST_METHOD'];
            $routes=$this->{$method};

            foreach($routes as $route){
                if($route['uri']==$uri){
                    $route['controller']=new $route['controller']();
                    return $route['controller']->{$route['action']}();
                }
            }
        }
    }