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
$get_body = $_POST['body'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


//comment_insert
$sqlInsert = "INSERT INTO pin_comment_".$get_pin_type."(user_seq, type, pin_seq, body, date_, ip) VALUES ('$get_user_seq', '$get_pin_type', '$get_pin_seq', '$get_body', '$get_date', '$get_ip')";


$res = mysql_query($sqlInsert,$conn);

if(!$res)
{
	echo "db등록 실패";
	$isSuccess = 0; //실패 
	exit;
}else{
	
	if ( $isSuccess == 0 ){ //실패
		//$result = @mysql_query("ROLLBACK", $conn);
	    echo "Error RollBack";
	    exit;
	} else{
	    //echo "성공".$isSuccess;
	    //$result = @mysql_query("COMMIT", $conn);
	}

	$boardList = array();
	$board['status'] = "ok";
	array_push($boardList, $board);
	echo json_encode($boardList);
}





?>