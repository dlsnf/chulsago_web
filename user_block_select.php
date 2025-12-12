<?php

include "dbcon.php";

//트랜잭션 시작
$result = @mysql_query("SET AUTOCOMMIT=0", $conn);
$result = @mysql_query("BEGIN", $conn);



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
$get_user_seq = $_POST['user_seq'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



//블럭 조회
$query = "SELECT user_block.*, user.name, user.thumbnail_image FROM user_block LEFT JOIN user ON user_block.block_user_seq = user.seq WHERE user_seq = '$get_user_seq'"; // SQL 쿼리문


$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query user_block_select.php";
	exit;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];
	$board['user_seq'] = $row["user_seq"];
	$board['block_user_seq'] = $row["block_user_seq"];

	$board['name'] = $row["name"];
	$board['thumbnail_image'] = $row["thumbnail_image"];
	
	$board['ip'] = $row["ip"];
	$board['date_'] = $row["date_"];

	array_push($boardList, $board);
}

//db 조회가 없으면
if (!$result)
{
	echo "데이터가 없습니다.";
}else{	
	echo json_encode($boardList);
}


?>