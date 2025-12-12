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
$get_type = $_POST['type'];
$get_pin_type = $_POST['pin_type'];
$get_pin_seq = $_POST['pin_seq'];


if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}

if( $get_type == "view_count_up" ){ //특정 pin 하나 불러오기

	//조회수 증가


	//트랜잭션 시작
	$result = @mysql_query("SET AUTOCOMMIT=0", $conn);
	$result = @mysql_query("BEGIN", $conn);



	//테이블 락
	$result = @mysql_query("Lock Tables pin_".$get_pin_type." write", $conn);

	$view_count = pin_view_count_select($get_pin_seq,$get_pin_type,$conn);
	$view_count++;
	


	//핀 디비 업데이트
	$sqlUpdate = "UPDATE pin_".$get_pin_type." SET view_count = '$view_count' WHERE seq = '$get_pin_seq'";


	$res2 = mysql_query($sqlUpdate,$conn);

	//테이블락 해제
	$result = @mysql_query("Unlock Tables", $conn);


	if ( $isSuccess == 0 ){
		$result = @mysql_query("ROLLBACK", $conn);
	    echo "Error RollBack";
	    exit;
	} else{
	    //echo "성공".$isSuccess;
	    $result = @mysql_query("COMMIT", $conn);

	    $boardList = array();
		$board['info'] = "success";
		array_push($boardList, $board);
		echo json_encode($boardList);

	}
	


}



//핀 조회수 조회
function pin_view_count_select($seq,$get_pin_type,$conn){

	//좋아요 수 조회
	$query = "SELECT * FROM pin_".$get_pin_type." WHERE seq = '$seq'"; // SQL 쿼리문

	$result = mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_view_count_select.php";
		global $isSuccess;
		$isSuccess = 0;
		//exit;
	}


	while( $row = mysql_fetch_array($result) ) {
		$view_count = $row["view_count"];
	}

	return $view_count;
}



?>