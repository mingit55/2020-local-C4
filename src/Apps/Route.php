<?php
namespace Apps;

class Route {
    static $get = [];
    static $post = [];

    static function __callStatic($name, $arguments){
        if(strtoupper($name) === $_SERVER['REQUEST_METHOD']){
            $propName = strtolower($name);
            self::${$propName}[] = $arguments;
        }
    }

    static function getURL(){
        $url = explode("?", $_SERVER['REQUEST_URI'])[0];
        $filteredURL = filter_var($url, FILTER_SANITIZE_URL);
        $trimmedURL = rtrim($filteredURL);
        return $trimmedURL;
    }


    static function execute($page, $params = []){
        $action = $page[1];
        $split = explode("@", $action);

        
        $method = $split[1];
        $conName = "Controllers\\{$split[0]}";
        $con = new $conName();
        $con->{$method}(...$params);
        exit;
    }

    /**
     * 간단하게 URL만 비교하면, URL 내의 데이터는 뽑아올 수 없지만
     * 빠른 속도로 구현할 수 있음.
     */

    static function connectSimple(){
        $currentURL = self::getURL();
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        foreach(self::${$method} as $page){
            if($currentURL === $page){
                self::execute($page);
            }
        }

        http_response_code(404);
    }

    /**
     * 유저 친화적 URL Routing 을 구현할 때 사용
     */

     static function connect(){
        $currentURL = self::getURL();
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        foreach(self::${$method} as $page){
            $regex = preg_replace("/{([^\/]+)}/", "([^/]+)", $page[0]);
            $regex = preg_replace("/\//", "\\/", $regex);
            
            if(preg_match("/^{$regex}$/", $currentURL, $matches)){
                if(isset($page[2]) && $page[2] === "user" && !user()) go("/", "로그인해 주세요.");

                unset($matches[0]); // 첫번째 인덱스엔 입력한 모든 데이터가 들어가 있음 => 두번째부터는 표현식으로 맺은 그룹 !

                self::execute($page, $matches); // 따라서 그룹을 메소드 실행 시 넣어주면 매개변수로 활용 가능
            }
        }
        http_response_code(404);
     }
}