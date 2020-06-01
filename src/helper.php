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
    echo "location.href='$url';";
    if($message != "") echo "alert('$message');";
    echo "</script>";
    exit;
}

function back($message){
    echo "<script>";
    echo "history.back();";
    if($message != "") echo "alert('$message');";
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

function json_response($message, $result = false, $extend = []){
    header("Content-Type: application/json");
    echo json_encode(array_merge(["message" => $message, "result" => $result], $extend));
}

function checkInput($response = "js"){
    foreach($_POST as $input){
        if($input === ""){
            if($response === "js"){
                back("모든 정보를 입력해 주세요.");
            } else {
                json_response("모든 정보를 입력해 주세요.");
            }
        }
    }

    foreach($_FILES as $file) {
        if(!is_file($file['tmp_name'])) {
            if($response === "js"){
                back("파일을 업로드해 주세요.");
            } else {
                json_response("파일을 업로드해 주세요.");
            }           
        }
    }
}

function extname($filepath){
    return substr($filepath, strrpos($filepath, "."));
}