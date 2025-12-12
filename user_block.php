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
$get_user_seq = $_POST['user_seq'];
$get_block_user_seq = $_POST['block_user_seq'];



if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}



//블럭 조회
$query = "SELECT * FROM user_block WHERE user_seq = '$get_user_seq' AND block_user_seq = '$get_block_user_seq'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query user_block_select.php";
	exit;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];

	array_push($boardList, $board);
}




if (count($boardList) == 0){//값이 없을때만


	//디비 추가
	$sqlInsert = "INSERT INTO user_block(user_seq, block_user_seq, ip, date_) VALUES ('$get_user_seq', '$get_block_user_seq', '$get_ip', '$get_date')";


	$res2 = mysql_query($sqlInsert,$conn);


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
		$board['status'] = "ok";
		array_push($boardList, $board);
		echo json_encode($boardList);
	}

		



}else{ //값 없음
	echo "이미 차단 되었습니다.";

}


?>