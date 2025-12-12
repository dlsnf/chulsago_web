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
$get_user_seq = $_POST['user_seq'];
$get_pin_type = $_POST['pin_type'];
$get_pin_seq = $_POST['pin_seq'];

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



$query = "SELECT * FROM pin_like_".$get_pin_type." WHERE pin_seq = '$get_pin_seq' AND user_seq = '$get_user_seq'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과


if( !$result ) {
	echo "Failed to list query pin_like_select.php";
	$isSuccess = FALSE;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];


	array_push($boardList, $board);
}
if (count($boardList) != 0){
	//echo "값 있음";

	$boardList = array();
	$board['status'] = "ok";
	array_push($boardList, $board);
	echo json_encode($boardList);

}else{
	//echo "값 없음"; 

	$boardList = array();
	$board['status'] = "none";
	array_push($boardList, $board);
	echo json_encode($boardList);

}




?>