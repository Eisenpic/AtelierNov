<?php

namespace mf\router;

use http\Header;
use mf\auth\Authentification;
use tweeterapp\control\TweeterController;

class Router extends AbstractRouter{

    public function run(){
        $auth = new Authentification();
        if(array_key_exists($this->http_req->path_info, self::$routes) && $auth->checkAccessRight(self::$routes[$this->http_req->path_info][2])){
            $ctrl = self::$routes[$this->http_req->path_info][0];
            $mth = self::$routes[$this->http_req->path_info][1];
            $ctrl = new $ctrl();
            $ctrl->$mth();
        } else {
            $default_path = $this->http_req->script_name . self::$aliases['default'];
            header("Location: $default_path");
        }
    }

    public function addRoute($name, $url, $ctrl, $mth, $level){
        self::$routes[$url] = [$ctrl, $mth, $level];
        self::$aliases[$name] = $url;
    }

    public function setDefaultRoute($url){
        self::$aliases['default'] = $url;
    }

    public static function executeRoute($alias){
        $path_info = self::$aliases[$alias];
        $mth = self::$routes[$path_info][1];
        $ctrl = self::$routes[$path_info][0];
        $ctrl = new $ctrl();
        $ctrl->$mth();
    }

    public function urlFor($route_name, $param_list=[]){
        $path = $this->http_req->script_name . self::$aliases[$route_name];

        if(isset($param_list)){
            $path .= "?";
            foreach($param_list as $key => $value){
                $path .= $key . "=" . $value ."&";
            }
            $path = substr($path, 0, -1);
        }
        return($path);
    }
}