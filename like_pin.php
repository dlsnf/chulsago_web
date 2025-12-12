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
$get_pin_type = $_POST['pin_type'];
$get_pin_seq = $_POST['pin_seq'];
$get_pin_user_seq = $_POST['pin_user_seq'];

if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}





//pin_chulsa_select.php 도 있음
$hot_like = 2;
$hot_date_day = 3000;

$policy_query = "SELECT * FROM policy ORDER BY seq DESC LIMIT 0, 1"; // SQL 쿼리문
$policy_result = mysql_query($policy_query, $conn); // 쿼리문을 실행 결과

if( !$policy_result ) {
	echo "Failed to list policy_query like_pin.php";
}


while( $row = mysql_fetch_array($policy_result) ) {
	$hot_like = $row["hot_like"];
	$hot_date_day = $row["hot_date_day"];
	break;
}

if( $hot_like == null )
{
	$hot_like = 2;
	$hot_date_day = 3000;
}



//핀 조회
$query = "SELECT * FROM pin_like_".$get_pin_type." WHERE pin_seq = '$get_pin_seq' AND user_seq = '$get_user_seq'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query pin_like_chulsa.php";
	exit;
}


$boardList = array();

while( $row = mysql_fetch_array($result) ) {
	$board['seq'] = $row["seq"];


	array_push($boardList, $board);
}


if (count($boardList) != 0){
	//echo "값 있음"; //좋아요 취소 디비 삭제


	$sqlInsert = "DELETE FROM pin_like_".$get_pin_type." WHERE pin_seq = '$get_pin_seq' AND user_seq = '$get_user_seq'";


	$res = mysql_query($sqlInsert,$conn);

	if(!$res)
	{
		echo "db등록 실패";
		$isSuccess = 0;
		exit;
	}else{
		//디비등록 성공

		//테이블 락
		$result = @mysql_query("Lock Tables pin_".$get_pin_type." write", $conn);

		$like_count = pin_like_count_select($get_pin_seq,$get_pin_type,$conn);
		if ($like_count > 0)
		{
			$like_count--;
		}

		//디비 업데이트
		$sqlUpdate = "UPDATE pin_".$get_pin_type." SET pin_".$get_pin_type.".like= '$like_count' WHERE seq = '$get_pin_seq'";


		$res2 = mysql_query($sqlUpdate,$conn);

		//테이블락 해제
		$result = @mysql_query("Unlock Tables", $conn);

		if(!$res2)
		{
			echo "db업데이트 실패";
			$isSuccess = 0;
			exit;
		}else{
			//디비업데이트 성공
			//성공 유저 포인트 감소


			//테이블 락
			$result = @mysql_query("Lock Tables user write", $conn);

			//유저 포인트 조회
			$user_point = user_point_select($get_pin_user_seq, $conn);
			if ($user_point > 0)
			{
				$user_point--;
			}

			//디비 업데이트
			$sqlUpdate = "UPDATE user SET user.point = '$user_point' WHERE seq = '$get_pin_user_seq'";


			$res2 = mysql_query($sqlUpdate,$conn);

			//테이블락 해제
			$result = @mysql_query("Unlock Tables", $conn);


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

		}


		
	}

}else{
	//echo "값 없음"; //좋아요 등록

	//insert
	$sqlInsert = "INSERT INTO pin_like_".$get_pin_type."(user_seq, type, pin_seq, date_, ip) VALUES ('$get_user_seq', '$get_pin_type', '$get_pin_seq', '$get_date','$get_ip')";


	$res = mysql_query($sqlInsert,$conn);

	if(!$res)
	{
		echo "db등록 실패";
		$isSuccess = 0;
		exit;
	}else{
		//디비등록 성공

		//테이블 락
		$result = @mysql_query("Lock Tables pin_".$get_pin_type." write", $conn);

		$like_count = pin_like_count_select($get_pin_seq,$get_pin_type,$conn);
		$like_count++;







		//hot_date가 등록되었는지 체크

		$query = "SELECT * FROM pin_".$get_pin_type." WHERE seq = '$get_pin_seq'"; // SQL 쿼리문

		$result = mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query like_pin.php pin_like_hot_date_check";
			$isSuccess = 0;
			//exit;
		}

		while( $row = mysql_fetch_array($result) ) {
			$hot_date = $row["hot_date"];
		}

		if ( $hot_date == '0000-00-00 00:00:00'){
			//echo "값 없음";
			$hot_date_check = 0;
		}else{
			//echo "값 있음";
			$hot_date_check = 1;
		}

		//hot_date가 등록되었는지 체크



		if ( $hot_date_check == 0 ){//핫데이트 등록 하기

			if ( $like_count == $hot_like ){ //hot 등록
				//핀 디비 업데이트
				$sqlUpdate = "UPDATE pin_".$get_pin_type." SET pin_".$get_pin_type.".like = '$like_count', pin_".$get_pin_type.".hot_date = '$get_date' WHERE seq = '$get_pin_seq'";
			}else{
				//핀 디비 업데이트
				$sqlUpdate = "UPDATE pin_".$get_pin_type." SET pin_".$get_pin_type.".like = '$like_count' WHERE seq = '$get_pin_seq'";
			}


		}else{//핫데이트 등록 안하기
			
			//핀 디비 업데이트
			$sqlUpdate = "UPDATE pin_".$get_pin_type." SET pin_".$get_pin_type.".like = '$like_count' WHERE seq = '$get_pin_seq'";
			
		}//if




		$res2 = mysql_query($sqlUpdate,$conn);

		//테이블락 해제
		$result = @mysql_query("Unlock Tables", $conn);

		if(!$res2)
		{
			echo "db업데이트 실패";
			$isSuccess = 0;
			exit;
		}else{
			//디비업데이트 성공
			//성공 유저 포인트 증가


			//테이블 락
			$result = @mysql_query("Lock Tables user write", $conn);

			//유저 포인트 조회
			$user_point = user_point_select($get_pin_user_seq, $conn);
			$user_point++;


			//디비 업데이트
			$sqlUpdate = "UPDATE user SET user.point = '$user_point' WHERE seq = '$get_pin_user_seq'";


			$res2 = mysql_query($sqlUpdate,$conn);

			//테이블락 해제
			$result = @mysql_query("Unlock Tables", $conn);


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
				$board['status'] = "add";
				array_push($boardList, $board);
				echo json_encode($boardList);
			}

		}
		
	}

}





//핀 좋아요 조회
function pin_like_count_select($seq,$get_pin_type,$conn){

	//좋아요 수 조회
	$query = "SELECT * FROM pin_".$get_pin_type." WHERE seq = '$seq'"; // SQL 쿼리문

	$result = mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_like_count_select.php";
		global $isSuccess;
		$isSuccess = 0;
		//exit;
	}


	while( $row = mysql_fetch_array($result) ) {
		$like_count = $row["like"];
	}

	return $like_count;
}

//유저 포인트 조회
function user_point_select($pin_user_seq,$conn){

	//좋아요 수 조회
	$query = "SELECT * FROM user WHERE seq = '$pin_user_seq'"; // SQL 쿼리문

	$result = mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query user_point_select.php";
		global $isSuccess;
		$isSuccess = 0;
		//exit;
	}

	while( $row = mysql_fetch_array($result) ) {
		$user_point = $row["point"];

	}


	return $user_point;
}





?>