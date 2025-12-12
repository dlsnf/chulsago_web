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
$get_pin_type = $_POST['pin_type'];

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



$query = "SELECT count(*) FROM pin_".$get_pin_type." WHERE status = 'able'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과


if( !$result ) {
	echo "Failed to list query pin_count.php";
	$isSuccess = FALSE;
}

$count = 0;

while( $row = mysql_fetch_array($result) ) {
	$count = $row[0];

}
	

//db 조회가 없으면
if (!$result)
{
	echo "db 조회 안됨";
}else{	
	$boardList = array();
	$board['status'] = "ok";
	$board['count'] = $count;
	array_push($boardList, $board);
	echo json_encode($boardList);
}



?>