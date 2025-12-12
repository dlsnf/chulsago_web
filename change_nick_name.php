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
$get_nick_name = $_POST['nick_name'];



if ($get_key != "nuri") 
{
	echo "key error".$get_key;
	exit;
}




$query = "SELECT name FROM user WHERE name = '$get_nick_name'"; // SQL 쿼리문

$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query change_nick_name.php";
	$isSuccess = 0;
}

while( $row = mysql_fetch_array($result) ) {
	$nick_name = $row[0];
}


if ( !isset($nick_name) )
{
	//닉네임이 없을경우 변경
	$sqlUpdate = "UPDATE user SET name = '$get_nick_name' WHERE seq = '$get_user_seq'";


	$res = mysql_query($sqlUpdate,$conn);

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
		$board['seq'] = $get_user_seq;
		$board['status'] = "ok";
		array_push($boardList, $board);
		echo json_encode($boardList);
	}

}else{
	//닉네임이 존재하면 변경 안됨
	echo "닉네임이 존재합니다.";
	exit;
}







