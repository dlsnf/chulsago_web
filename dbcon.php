<?php
// dbcon.php - PHP 8.1 완벽 호환 버전
$mysql_host = "localhost";
$mysql_user = "";
$mysql_password = "";
$mysql_db = "chulsago";

$isSuccess = 1;

$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);

if ($mysqli->connect_error) {
    error_log("MySQL 연결 실패: " . $mysqli->connect_error);
    echo "데이터베이스 연결 실패";
    $isSuccess = 0;
} else {
    $mysqli->set_charset("utf8mb4");
    $conn = $mysqli;   // 기존 코드 호환용
    // mysql_* 호환 레이어 포함
    if (file_exists(__DIR__ . '/mysql_compat.php')) {
        require_once __DIR__ . '/mysql_compat.php';
    }
}
?>