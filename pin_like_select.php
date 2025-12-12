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
$get_pin_seq = $_POST['pin_seq'];
$get_pin_type = $_POST['pin_type'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



//좋아요 한사람 조회
$query = "SELECT PL.*, UR.name, UR.thumbnail_image FROM pin_like_".$get_pin_type." as PL LEFT JOIN user as UR ON UR.seq = PL.user_seq WHERE pin_seq = '$get_pin_seq' ORDER BY PL.seq DESC"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query pin_like_select.php";
	exit;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];
	$board['user_seq'] = $row["user_seq"];
	$board['pin_seq'] = $row["pin_seq"];

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