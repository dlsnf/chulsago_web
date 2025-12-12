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




if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


$policy_query = "SELECT * FROM policy ORDER BY seq DESC LIMIT 0, 1"; // SQL 쿼리문
$policy_result = mysql_query($policy_query, $conn); // 쿼리문을 실행 결과

if( !$policy_result ) {
	echo "Failed to list policy_query get_policy.php";
}

$boardList = array();

while( $row = mysql_fetch_array($policy_result) ) {
	//$hot_like = $row["hot_like"];
	//$hot_date_day = $row["hot_date_day"];
	$board["seq"] = $row["seq"];
	$board["hot_like"] = $row["hot_like"];
	$board["hot_date_day"] = $row["hot_date_day"];
	
	array_push($boardList, $board);

	break;
}

if( $board["hot_like"] == null )
{
	$board["hot_like"] = 2;
	$board["hot_date_day"] = 3000;
}


//db 조회가 없으면
if (!$policy_result)
{
	echo "데이터가 없습니다.";
}else{	
	echo json_encode($boardList);
}

?>