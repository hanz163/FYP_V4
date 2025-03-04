<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysql = require __DIR__ . "/database. php";

$sql = "SELECT * FROM users
WHERE reset token hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if($user === null){
    die("token not found");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE users
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE ID = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss",$password_hash,$user["id"]);
$stmt->execute();
echo "Password updated. You can now login.";