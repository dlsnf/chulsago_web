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

$get_key = isset($_POST['key']) ? content($_POST['key']) : '';
$get_email = isset($_POST['email']) ? $_POST['email'] : '';
$get_password = isset($_POST['password']) ? $_POST['password'] : '';
$get_name = isset($_POST['name']) ? $_POST['name'] : '';
$get_type = isset($_POST['type']) ? $_POST['type'] : '';
$get_id = isset($_POST['id']) ? $_POST['id'] : '';
$get_profile_image = $_POST['profile_image'];
$get_thumbnail_image = $_POST['thumbnail_image'];

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


if ($get_type == "app")
{
	//계정 조회
	$query = "SELECT seq, email, password FROM user WHERE type = '$get_type' AND email = '$get_email'"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query login_app.php";
		$isSuccess = 0;
		exit;
	}
	while( $row = mysql_fetch_array($result) ) {
		$seq = $row["seq"];
		$get_email_db = $row["email"];
		$get_password_db = $row["password"];
	}
	//해쉬암호화 변환
	$get_password = hash('sha256', $get_password);

	if( !isset($get_email_db) )
	{
		echo "계정이 존재하지 않습니다.";
		exit;
	}else if($get_password_db != $get_password){
		echo "비밀번호가 틀립니다.";
		exit;
	}else{
		$boardList = array();
		$board['seq'] = $seq;
		array_push($boardList, $board);
		echo json_encode($boardList);
		exit;
	}

}else if ($get_type == "kakao")
{
	//계정 사용자 조회
	$query = "SELECT seq FROM user WHERE type = '$get_type' AND email = '$get_email'"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query login_get_type.php";
		$isSuccess = 0;
	}
	while( $row = mysql_fetch_array($result) ) {
		$seq = $row["seq"];
	}

	//사용자가 있으면 가입중지
	if (isset($seq))
	{
		$boardList = array();
		$board['seq'] = $seq;
		array_push($boardList, $board);
		echo json_encode($boardList);
		exit;
	}




	//회원가입
	$sqlInsert = "INSERT INTO user(email, name, type, id, profile_image, thumbnail_image, date_, ip) VALUES ('$get_email', '$get_name', '$get_type','$get_id','$get_profile_image','$get_thumbnail_image','$get_date','$get_ip')";


	$res = mysql_query($sqlInsert,$conn);

	if(!$res)
	{
		echo "db등록 실패";
		exit;
	}else{

		//가입 성공 후 seq 조회

		//카카오 사용자 조회
		$query = "SELECT seq FROM user WHERE type = 'kakao' AND email = '$get_email'"; // SQL 쿼리문

		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query login.php";
			$isSuccess = 0;
		}
		while( $row = mysql_fetch_array($result) ) {
			$seq = $row["seq"];
		}


		if ( $isSuccess == 0 ){
			$result = @mysql_query("ROLLBACK", $conn);
		    echo "Error RollBack";
		    exit;
		} else{
		    //echo "성공".$isSuccess;
		    $result = @mysql_query("COMMIT", $conn);
		}



		$boardList = array();
		$board['seq'] = $seq;
		array_push($boardList, $board);
		echo json_encode($boardList);
		exit;
	}
}else if ($get_type == "apple")
{
	//계정 사용자 조회
	$query = "SELECT seq FROM user WHERE type = '$get_type' AND id = '$get_id'"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query login_get_type apple .php";
		$isSuccess = 0;
	}
	while( $row = mysql_fetch_array($result) ) {
		$seq = $row["seq"];
	}

	//사용자가 있으면 가입중지
	if (isset($seq))
	{
		$boardList = array();
		$board['seq'] = $seq;
		array_push($boardList, $board);
		echo json_encode($boardList);
		exit;
	}




	//회원가입
	$sqlInsert = "INSERT INTO user(email, name, type, id, profile_image, thumbnail_image, date_, ip) VALUES ('$get_email', '$get_name', '$get_type','$get_id','$get_profile_image','$get_thumbnail_image','$get_date','$get_ip')";


	$res = mysql_query($sqlInsert,$conn);

	if(!$res)
	{
		echo "db등록 실패";
		exit;
	}else{

		//가입 성공 후 seq 조회

		//카카오 사용자 조회
		$query = "SELECT seq FROM user WHERE type = '$get_type' AND id = '$get_id'"; // SQL 쿼리문

		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query login apple .php";
			$isSuccess = 0;
		}
		while( $row = mysql_fetch_array($result) ) {
			$seq = $row["seq"];
		}


		if ( $isSuccess == 0 ){
			$result = @mysql_query("ROLLBACK", $conn);
		    echo "Error RollBack";
		    exit;
		} else{
		    //echo "성공".$isSuccess;
		    $result = @mysql_query("COMMIT", $conn);
		}



		$boardList = array();
		$board['seq'] = $seq;
		array_push($boardList, $board);
		echo json_encode($boardList);
		exit;
	}
}


?>