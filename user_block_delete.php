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
$get_seq = $_POST['seq'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


$sqlDelete = "DELETE FROM user_block WHERE seq = '$get_seq'";


$res2 = mysql_query($sqlDelete,$conn);



if(!$res2)
{
	echo "db업데이트 실패";
	$isSuccess = 0;
	exit;
}else{


	if ( $isSuccess == 0 ){
		$result = @mysql_query("ROLLBACK", $conn);
	    echo "Error RollBack";
	    exit;
	} else{
	    //echo "성공".$isSuccess;
	    $result = @mysql_query("COMMIT", $conn);
	}

	$boardList = array();
	$board['status'] = "delete";
	array_push($boardList, $board);
	echo json_encode($boardList);
	
}



?>