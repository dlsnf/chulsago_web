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
$get_pin_user_seq = $_POST['pin_user_seq'];
$get_reporter_seq = $_POST['reporter_seq'];
$get_body = $_POST['body'];


if ($get_key != "nuri") 
{
	echo "key error or images size is big".$get_key;
	exit;
}



$sqlInsert = "INSERT INTO pin_report(pin_seq, pin_type, pin_user_seq, reporter_seq, body, ip, date_) VALUES ('$get_pin_seq', '$get_pin_type', '$get_pin_user_seq', '$get_reporter_seq', '$get_body', '$get_ip','$get_date')";


$res = mysql_query($sqlInsert,$conn);

if(!$res)
{
	echo "db등록 실패";
	$isSuccess = 0;
	exit;
}else{
	//디비등록 성공

	if ( $isSuccess == 0 ){
		$result = @mysql_query("ROLLBACK", $conn);
	    echo "Error RollBack";
	    exit;
	} else{
	    //echo "성공".$isSuccess;
	    $result = @mysql_query("COMMIT", $conn);
	}


	//성공
	$boardList = array();
	$board['seq'] = $get_seq;
	$board['status'] = "ok";
	array_push($boardList, $board);
	echo json_encode($boardList);
}




