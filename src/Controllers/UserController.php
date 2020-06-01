<?php
namespace Controllers;

use Apps\DB;


class UserController {
    function signIn(){
        checkInput();
        extract($_POST);

        $user = DB::who($user_id);
        if(!$user || $user->password !== hash('sha256', $password)) back("아이디 또는 비밀번호가 일치하지 않습니다.");
        
        $_SESSION['user'] = $user;

        go("/", "로그인 되었습니다.");
    }

    function signUp(){
        checkInput();
        extract($_POST);
        
        $user = DB::who($user_id);
        if($user) back("중복되는 아이디입니다.");
        if($captcha !== $input_captcha) back("자동입력방지 문자를 잘못 입력하였습니다.");
        if(!preg_match("/^(?=.*[a-zA-Z].*)(?=.*[0-9].*)(?=.*[!@#$%^&*()].*)([a-zA-Z0-9!@#$%^&*()]+)$/", $password))
            back("비밀번호는 [영문 / 특수문자 / 숫자]로 구성되어야 합니다.");

        if(!preg_match("/^data:image\/(?<extname>[a-z]+);base64,(?<data>.+)/", $photo, $matches)) {
            back("올바른 이미지를 업로드해 주세요.");
        }
        extract($matches);
        $filename = time(). ".". $extname;
        
        file_put_contents(_UPLOAD . "/users/$filename", base64_decode($data));

        DB::query("INSERT INTO users(user_id, user_name, password, photo) VALUES (?, ?, ?, ?)", [$user_id, $user_name, hash('sha256', $password), $filename]);
        go("/", "회원가입 되었습니다.");
    }

    function logout(){
        unset($_SESSION['user']);
        go("/", "로그아웃 되었습니다.");
    }


    // 전문가 페이지
    function specialistPage(){
        $sql = "SELECT U.*, ifnull(cnt, 0) cnt, ifnull(total, 0) total
                FROM users U
                LEFT JOIN (SELECT COUNT(*) cnt, SUM(score) total, sid FROM user_reviews GROUP BY sid) S ON S.sid = U.id
                WHERE auth = 1";
        $userList = DB::fetchAll($sql);

        

        view("specialist", ["userList" => $userList]); 
    }

    function reviewUser(){
        checkInput();
        extract($_POST);

        $user = DB::find("users", $sid);
        if(!$user) back("후기를 달 전문가를 찾을 수 없습니다.");
        if(!is_numeric($price) || $price < 0) back("올바른 금액을 입력하세요.");
        
        DB::query("INSERT INTO user_reviews(uid, sid, price, score, content) VALUES (?, ?, ?, ?, ?)", [user()->id, $sid, $price, $score, $content]);
        go("/specialists", "후기가 작성되었습니다.");
    }

    function getReviews(){
        $sql = "SELECT R.*, U1.user_id, U1.user_name, U2.user_id s_id, U2.user_name s_name
                FROM user_reviews R
                LEFT JOIN users U1 ON U1.id = R.uid
                LEFT JOIN users U2 ON u2.id = R.sid";
        $reviewList = DB::fetchAll($sql);       
        
        json_response(true, ["list" => $reviewList]);
    }
}