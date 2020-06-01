<?php
function dump(){
    foreach(func_get_args() as $arg){
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
        echo "<br>";
    }
}

function dd(){
    dump(...func_get_args());
    exit;
}

function user(){
    return isset($_SESSION['user']) ? $_SESSION['user'] : false;
}

function go($url, $message = ""){
    echo "<script>";
    if($message != "") echo "alert('$message');";
    echo "location.href='$url';";
    echo "</script>";
    exit;
}

function back($message){
    echo "<script>";
    if($message != "") echo "alert('$message');";
    echo "history.back();";
    echo "</script>";
    exit;
}

function view($pagePath, $output = []){
    extract($output);
    $pagePath = _VIEW . DS . str_replace("/", DS, $pagePath) . ".php";

    if(is_file($pagePath)) {
        require _VIEW . DS ."layouts/header.php";
        require $pagePath;
        require _VIEW . DS ."layouts/footer.php";
    }
}

function json_response($result = false, $extend = []){
    header("Content-Type: application/json");
    echo json_encode(array_merge(["result" => $result], $extend));
    exit;
}

function checkInput(){
    foreach($_POST as $input){
        if($input === ""){
            back("모든 정보를 입력해 주세요.");
        }
    }

    foreach($_FILES as $file) {
        if(!is_file($file['tmp_name'])) {
            back("파일을 업로드해 주세요.");
        }
    }
}

function extname($filepath){
    return substr($filepath, strrpos($filepath, "."));
}