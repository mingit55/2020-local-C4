<?php

require __DIR__ . "/Apps/DB.php";

use Apps\DB;

for($i = 1; $i <= 4; $i++){
    $params = ["specialist$i", hash('sha256', '1234'), "전문가$i", "specialist$i.jpg"];
    DB::query("INSERT INTO users(user_id, password, user_name, photo, auth) VALUES (?, ?, ?, ?, 1)", $params);
}