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

// POST 파라미터 안전 추출
$get_key = isset($_POST['key']) ? content($_POST['key']) : '';
$get_pin_seq = isset($_POST['pin_seq']) ? $_POST['pin_seq'] : '';
$get_pin_type = isset($_POST['pin_type']) ? $_POST['pin_type'] : '';


if ($get_key != "nuri") 
{
	echo "key error or images size is big".$get_key;
	exit;
}





$selUpdate = "UPDATE pin_".$get_pin_type." SET status = 'delete' WHERE seq = '$get_pin_seq'";


$res = mysql_query($selUpdate,$conn);





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




