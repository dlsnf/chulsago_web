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
$get_pin_seq = $_POST['pin_seq'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



$query = "SELECT count(*) FROM pin_comment_".$get_pin_type." WHERE pin_seq = '$get_pin_seq' AND status = 'able'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query comment_count_select.php";
	$isSuccess = FALSE;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['count'] = $row[0];

	array_push($boardList, $board);
}

//db 조회가 없으면
if (!$result)
{
	echo "댓글 데이터가 없습니다.";
}else{	
	echo json_encode($boardList);
}




?>