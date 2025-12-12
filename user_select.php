<?php

include "dbcon.php";

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}



$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = time();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_key = content($_POST['key']);
$get_seq = $_POST['seq'];

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


//카카오 사용자 조회
$query = "SELECT * FROM user WHERE seq = '$get_seq'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query login.php";
	$isSuccess = FALSE;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];
	$board['email'] = $row["email"];
	$board['name'] = $row["name"];
	$board['point'] = $row["point"];
	$board['type'] = $row["type"];
	$board['id'] = $row["id"];
	$board['profile_image'] = $row["profile_image"];
	$board['thumbnail_image'] = $row["thumbnail_image"];
	$board['date_'] = $row["date_"];
	$board['ip'] = $row["ip"];

	array_push($boardList, $board);
}

//db 조회가 없으면
if (!$result)
{
	echo "사용자 데이터가 없습니다.";
}else{	
	echo json_encode($boardList);
}



?>