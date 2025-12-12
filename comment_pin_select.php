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

$get_user_seq = $_POST['user_seq'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}




if ($get_user_seq != -1){ //사용자 차단자 조회

	$query1 = "SELECT * FROM user_block WHERE user_seq = '$get_user_seq'"; // SQL 쿼리문

	$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

	if( !$result11 ) {
		echo "Failed to list query pin_chulsa_select_block.php";
		$isSuccess = 0;
	}

	$blockList = array();

	while( $row = mysql_fetch_array($result11) ) {
		$block_seq = $row["block_user_seq"];

		array_push($blockList, $block_seq);
		
	}

}//사용자 차단 조회




$query = "SELECT * FROM pin_comment_".$get_pin_type." WHERE pin_seq = '$get_pin_seq' AND status = 'able' ORDER BY date_ ASC"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query comment_pin_select.php";
	$isSuccess = FALSE;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];
	$board['user_seq'] = $row["user_seq"];
	$user_seq = $row["user_seq"];
	$board['type'] = $row["type"];
	$board['body'] = $row["body"];
	$board['like'] = $row["like"];
	$board['status'] = $row["status"];
	$board['date_'] = $row["date_"];
	$board['ip'] = $row["ip"];


	//user info select
	$query2 = "SELECT * FROM user WHERE seq = '$user_seq'"; // SQL 쿼리문
	$result2 = mysql_query($query2, $conn); // 쿼리문을 실행 결과
	if( !$result2 ) {
		echo "Failed to list query comment_pin_select_user.php";
		$isSuccess = 0;
	}
	while( $row2 = mysql_fetch_array($result2) ) {
		$user_name = $row2["name"];
		$user_point = $row2["point"];
		$user_thumbnail_image = $row2["thumbnail_image"];
	}

	$board['user_name'] = $user_name;
	$board['user_point'] = $user_point;
	$board['user_thumbnail_image'] = $user_thumbnail_image;


	if (in_array($board['user_seq'], $blockList)) { //차단된 사용자가 존재할때

	}else{

		array_push($boardList, $board);
	}

}

//db 조회가 없으면
if (!$result)
{
	echo "댓글 데이터가 없습니다.";
}else{	
	echo json_encode($boardList);
}




?>