<?php
namespace Controllers;

use Apps\DB;

class MainController {
    function indexPage(){
        view("index");
    }

    function storePage(){
        view("store");
    }


    // 온라인 집들이
    function partyPage(){
        $sql = "SELECT K.*, user_id, user_name, ifnull(cnt, 0) cnt, ifnull(total, 0) total
                FROM knowhows K 
                LEFT JOIN users U ON U.id = K.uid
                LEFT JOIN (SELECT COUNT(*) cnt, SUM(score) total, kid FROM knowhow_reviews GROUP BY kid) S ON S.kid = K.id";
        $knowhows = DB::fetchAll($sql);

        $_myList = DB::fetchAll("SELECT * FROM knowhow_reviews WHERE uid = ?", [user()->id]);
        $myList = [];
        foreach($_myList as $item) 
            $myList[] = $item->kid; 

        view("party", ["knowhows" => $knowhows, "myList" => $myList]);
    }

    function writeKnowhow(){
        checkInput();
        extract($_POST);
        extract($_FILES);

        $before_ext = extname($before_img['name']);
        $after_ext = extname($after_img['name']);

        $before_name = "before_". time() . $before_ext;
        $after_name = "after_". time() . $after_ext;

        $uploadPath = _UPLOAD.DS."knowhows";

        move_uploaded_file($before_img['tmp_name'], $uploadPath .DS.$before_name);
        move_uploaded_file($after_img['tmp_name'], $uploadPath .DS.$after_name);
        
        DB::query("INSERT INTO knowhows(before_img, after_img, content, uid) VALUES (?, ?, ?, ?)", [$before_name, $after_name, $content, user()->id]);
        go("/online-party", "새로운 게시글이 작성되었습니다.");
    }

    function reviewKnowhow(){
        checkInput();
        extract($_POST);
        
        $knowhow = DB::find("knowhows", $kid);
        if(!$knowhow) back("게시글이 존재하지 않습니다.");
        
        DB::query("INSERT INTO knowhow_reviews(uid, kid, score) VALUES (?, ?, ?)", [user()->id, $kid, $score]);
        go("/online-party", "평점이 매겨졌습니다.");
    }

    // 시공 견적 페이지
    function estimatePage(){
        $orderList = ['user', 'wait', 'complete', 'start_date'];
        $order = isset($_GET['order']) && array_search($_GET['order'], $orderList) !== false ? $_GET['order'] : '';
        

        $sql = "SELECT Q.*, user_name, user_id, cnt
                FROM requests Q
                LEFT JOIN users U ON Q.uid = U.id
                LEFT JOIN (SELECT COUNT(*) cnt, qid FROM responses GROUP BY qid) R ON R.qid = Q.id";
        if($order === 'wait') {
            $sql .= " WHERE sid IS NULL";
        } else if($order === "complete") {
            $sql .= " WHERE sid IS NOT NULL";
        } else if($order === "user"){
            $sql .= " ORDER BY user_id ASC";
        } else if($order === "start_date"){
            $sql .= " ORDER BY start_date DESC";
        }

        $requests = DB::fetchAll($sql);

        $_myList = DB::fetchAll("SELECT * FROM responses WHERE uid = ?", [user()->id]);
        $myList = [];
        foreach($_myList as $item)
            $myList[] = $item->qid;


        $sql = "SELECT COUNT(*) cnt, SUM(price) total
                FROM requests Q
                LEFT JOIN responses S ON Q.sid = S.id
                WHERE Q.sid IS NOT NULL
                AND S.uid = ?";
        $myInfo = DB::fetch($sql, [user()->id]);

        $sql = "SELECT S.*, content, sid, start_date, user_id, user_name
                FROM responses S
                LEFT JOIN requests Q ON Q.id = S.qid
                LEFT JOIN users U ON Q.uid = U.id
                WHERE S.uid = ?";
        $responses = DB::fetchAll($sql, [user()->id]);

        view("estimate", ["order" => $order, "requests" => $requests, "myList" => $myList, "responses" => $responses, "myInfo" => $myInfo]);
    }

    function writeRequest(){
        checkInput();
        extract($_POST);   

        DB::query("INSERT INTO requests(uid, start_date, content) VALUES (?, ?, ?)", [user()->id, $start_date, $content]);
        go("/estimates", "요청이 완료되었습니다.");
    }

    function writeResponse(){
        checkInput();
        extract($_POST);

        $req = DB::find("requests", $qid);
        if(!$req) back("해당 요청이 존재하지 않습니다.");
        if(!is_numeric($price) || $price < 0) back("올바른 금액을 입력해 주세요");

        DB::query("INSERT INTO responses (uid, qid, price) VALUES (?, ?, ?)", [user()->id, $qid, $price]);
        go("/estimates", "견적을 보냈습니다.");
    }

    function viewEstimates(){
        if(!isset($_GET['id'])) json_response();
        
        $req = DB::find('requests', $_GET['id']);
        if(!$req) json_response();
        
        $list = DB::fetchAll("SELECT R.*, user_id, user_name FROM responses R LEFT JOIN users U ON U.id = R.uid WHERE R.qid = ?", [$req->id]);

        json_response(true, ["list" => $list, "request" => $req]);
    }

    function pickEstimate(){
        checkInput();
        extract($_POST);

        $req = DB::find("requests", $qid);
        $res = DB::find("responses", $sid);

        if(!$req || !$res) back("잘못된 요청입니다.");
        
        DB::query("UPDATE requests SET sid = ? WHERE id = ?", [$res->id, $req->id]);
        go("/estimates", "선택되었습니다.");
    }
}