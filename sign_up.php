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
$get_email = $_POST['email'];
$get_password = $_POST['password'];
$get_type = $_POST['type'];
$get_name = "";

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


//사용자 갯수 구하기
$query = "SELECT count(*) FROM user"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query sign_up_count.php";
	$isSuccess = 0;
	exit;
}
while( $row = mysql_fetch_array($result) ) {
	$user_count = $row[0];
}


$random = $user_count . mt_rand(0, 99);
$get_name = "nick_" . $random;

$get_password = hash('sha256', $get_password);


//이메일 겹치는것 조회
$query = "SELECT count(*) FROM user WHERE type = '$get_type' AND email = '$get_email'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query sign_up_email_error.php";
	$isSuccess = 0;
	exit;
}
while( $row = mysql_fetch_array($result) ) {
	$email_count = $row[0];
}

if ( $email_count != 0 ){
	echo "동일한 메일주소를 이미 사용 중 입니다.";
	exit;
}


//회원가입
$sqlInsert = "INSERT INTO user(email, password, name, type, date_, ip) VALUES ('$get_email', '$get_password', '$get_name', '$get_type', '$get_date', '$get_ip')";


$res = mysql_query($sqlInsert,$conn);

if(!$res)
{
	echo "db등록 실패";
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
	$board['seq'] = "success";
	array_push($boardList, $board);
	echo json_encode($boardList);
	exit;
}



?>