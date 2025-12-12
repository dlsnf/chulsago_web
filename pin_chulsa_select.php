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


$get_key = isset($_POST['key']) ? content($_POST['key']) : ''; // 기본값 빈 문자열
$get_type = isset($_POST['type']) ? $_POST['type'] : ''; // 기본값 빈 문자열
$get_type2 = isset($_POST['type2']) ? $_POST['type2'] : ''; // line 31 수정
$get_search_text = isset($_POST['search_text']) ? $_POST['search_text'] : ''; // line 32 수정
$get_pin_type = isset($_POST['pin_type']) ? $_POST['pin_type'] : ''; // 기존에도 있지만, 안전하게
$get_pin_seq = isset($_POST['pin_seq']) ? $_POST['pin_seq'] : ''; // line 34 수정
$get_user_seq = isset($_POST['user_seq']) ? $_POST['user_seq'] : -1; // 기본값 -1 (코드에서 사용됨)
$get_first_num = isset($_POST['first_num']) ? $_POST['first_num'] : 0; // line 38 수정, 기본값 0
$get_last_num = isset($_POST['last_num']) ? $_POST['last_num'] : 10; // line 39 수정, 기본값 예시 10 (필요에 따라 조정)




if ($get_key != "nuri") 
{
	echo "key error";
	exit;
}


//like_pin.php 에도 있음
$hot_like = 2;
$hot_date_day = 3000;

$policy_query = "SELECT * FROM policy ORDER BY seq DESC LIMIT 0, 1"; // SQL 쿼리문
$policy_result = mysql_query($policy_query, $conn); // 쿼리문을 실행 결과

if( !$policy_result ) {
	echo "Failed to list policy_query pin_chulsa_select.php";
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




//날짜 빼기
$hot_date = date_create($get_date);
date_sub($hot_date,date_interval_create_from_date_string($hot_date_day." days"));
$hot_date = date_format($hot_date, "Y-m-d");





if ($get_type == "nuriList") 
{
	// if ( $get_first_num == '' ){
	// 	$get_first_num = 0;
	// }

	//Collection View Test


	$blockList = array();

	if ($get_user_seq != -1){ //사용자 차단자 조회

		$query1 = "SELECT * FROM user_block WHERE user_seq = '$get_user_seq'"; // SQL 쿼리문

		$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

		if( !$result11 ) {
			echo "Failed to list query pin_chulsa_select_block.php";
			$isSuccess = 0;
		}

		while( $row = mysql_fetch_array($result11) ) {
			$block_seq = $row["block_user_seq"];

			array_push($blockList, $block_seq);
			
		}

	}//사용자 차단 조회

	
	



	if ($get_type2 == "hot"){
		if ( $get_search_text != "" ){
			$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' AND pin_".$get_pin_type.".like >= '$hot_like' AND ( pin_".$get_pin_type.".address LIKE '%".$get_search_text."%' OR pin_".$get_pin_type.".body LIKE '%".$get_search_text."%') ORDER BY hot_date DESC,date_ DESC LIMIT $get_first_num, $get_last_num";
		}else{
			$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' AND pin_".$get_pin_type.".like >= '$hot_like' ORDER BY hot_date DESC,date_ DESC LIMIT $get_first_num, $get_last_num";
		}
		
	}else if ($get_type2 =="new"){
		if ( $get_search_text != "" ){
			$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' AND ( pin_".$get_pin_type.".address LIKE '%".$get_search_text."%' OR pin_".$get_pin_type.".body LIKE '%".$get_search_text."%') ORDER BY date_ DESC LIMIT $get_first_num, $get_last_num";
		}else{
			$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' ORDER BY date_ DESC LIMIT $get_first_num, $get_last_num";
		}
		
	}else{
		//all list
		$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' ORDER BY date_ DESC LIMIT $get_first_num, $get_last_num";
	}


	$result = mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_all_col.php";
		$isSuccess = 0;
	}


	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
		$board['seq'] = $row["seq"];
		$temp_seq = $board['seq'];
		$board['user_seq'] = $row["user_seq"];
		$temp_user_seq = $board['user_seq'];
		$board['latitude'] = $row["latitude"];
		$board['longitude'] = $row["longitude"];
		$board['image_name'] = $row["image_name"];
		$board['body'] = $row["body"];
		$board['like'] = $row["like"];
		$board['date_'] = $row["date_"];
		$board['ip'] = $row["ip"];

		//댓글 갯수구하기
		$query1 = "SELECT count(*) FROM pin_comment_".$get_pin_type." WHERE pin_seq = '$temp_seq' AND status = 'able'"; // SQL 쿼리문
		$result1=mysql_query($query1, $conn); // 쿼리문을 실행 결과

		if( !$result1 ) {
			echo "Failed to list query comment_count_select_pin_chulsa_select.php";
			$isSuccess = 0;
		}
		while( $row1 = mysql_fetch_array($result1) ) {
			$board['comment_count'] = $row1[0];
		}


		//사용자 프로필사진 조회
		$query2 = "SELECT * FROM user WHERE seq = '$temp_user_seq'"; // SQL 쿼리문
		$result2=mysql_query($query2, $conn); // 쿼리문을 실행 결과
		if( !$result2 ) {
			echo "Failed to list query login.php";
			$isSuccess = 0;
		}
		while( $row2 = mysql_fetch_array($result2) ) {
			$board['thumbnail_image'] = $row2["thumbnail_image"];
		}

		if (in_array($board['user_seq'], $blockList)) { //차단된 사용자가 존재할때

		}else{

			array_push($boardList, $board);
		}
	}

	//db 조회가 없으면
	if (!$result)
	{
		echo "데이터가 없습니다.";
	}else{	
		echo json_encode($boardList);
	}
	exit;

}else if ( $get_type == "hot" ){ //hot 또는 기한 제한 뉴 불러오기


	$blockList = array();

	if ($get_user_seq != -1){ //사용자 차단자 조회

		$query1 = "SELECT * FROM user_block WHERE user_seq = '$get_user_seq'"; // SQL 쿼리문

		$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

		if( !$result11 ) {
			echo "Failed to list query pin_chulsa_select_block.php";
			$isSuccess = 0;
		}
		

		while( $row = mysql_fetch_array($result11) ) {
			$block_seq = $row["block_user_seq"];

			array_push($blockList, $block_seq);
			
		}

	}//사용자 차단 조회




	$query = "SELECT * FROM pin_".$get_pin_type." WHERE pin_".$get_pin_type.".status = 'able' AND (pin_".$get_pin_type.".like >= '$hot_like' OR pin_".$get_pin_type.".date_ >= '$hot_date') ORDER BY date_ DESC"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_all.php";
		$isSuccess = 0;
	}


	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
		$board['seq'] = $row["seq"];
		$board['user_seq'] = $row["user_seq"];
		$board['latitude'] = $row["latitude"];
		$board['longitude'] = $row["longitude"];
		$board['image_name'] = $row["image_name"];
		$board['body'] = $row["body"];
		$board['like'] = $row["like"];

		



		$board['date_'] = $row["date_"];
		$board['ip'] = $row["ip"];


		if ( $board['like'] >= $hot_like ) //좋아요 두개이상 hot pin 
		{
			$board['pin_color'] = "red";
		}else{ //한달이내 새로운 핀 초록색
			$board['pin_color'] = "green";
		}

		if (in_array($board['user_seq'], $blockList)) { //차단된 사용자가 존재할때

		}else{

			array_push($boardList, $board);
		}


	}

	//db 조회가 없으면
	if (!$result)
	{
		echo "데이터가 없습니다.";
	}else{	
		echo json_encode($boardList);
	}

}else if ( $get_type == "my_pin" ){ //my_pin 조회



	$query = "SELECT * FROM pin_".$get_pin_type." WHERE pin_".$get_pin_type.".status = 'able' AND pin_".$get_pin_type.".user_seq = '$get_user_seq' ORDER BY date_ DESC"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_my_pin.php";
		$isSuccess = 0;
	}


	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
		$board['seq'] = $row["seq"];
		$board['user_seq'] = $row["user_seq"];
		$board['latitude'] = $row["latitude"];
		$board['longitude'] = $row["longitude"];
		$board['image_name'] = $row["image_name"];
		$board['body'] = $row["body"];
		$board['like'] = $row["like"];

		



		$board['date_'] = $row["date_"];
		$board['ip'] = $row["ip"];


		if ( $board['like'] >= $hot_like ) //좋아요 두개이상 hot pin 
		{
			$board['pin_color'] = "red";
		}else{ //한달이내 새로운 핀 초록색
			$board['pin_color'] = "green";
		}

		array_push($boardList, $board);
		


	}

	//db 조회가 없으면
	if (!$result)
	{
		echo "데이터가 없습니다.";
	}else{	
		echo json_encode($boardList);
	}


}else if ( $get_type == "all" ){ //전체 불러오기
	$query = "SELECT * FROM pin_".$get_pin_type." WHERE status = 'able' ORDER BY date_ DESC"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_all.php";
		$isSuccess = 0;
	}


	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
		$board['seq'] = $row["seq"];
		$board['user_seq'] = $row["user_seq"];
		$board['latitude'] = $row["latitude"];
		$board['longitude'] = $row["longitude"];
		$board['image_name'] = $row["image_name"];
		$board['body'] = $row["body"];
		$board['like'] = $row["like"];
		$board['date_'] = $row["date_"];
		$board['ip'] = $row["ip"];

		array_push($boardList, $board);
	}

	//db 조회가 없으면
	if (!$result)
	{
		echo "데이터가 없습니다.";
	}else{	
		echo json_encode($boardList);
	}

}else if( $get_type == "one" ){ //특정 pin 하나 불러오기

	$query = "SELECT * FROM pin_".$get_pin_type." WHERE seq = '$get_pin_seq'"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_one.php";
		$isSuccess = 0;
	}


	$boardList = array();

	while( $row = mysql_fetch_array($result) ) {
		$board['seq'] = $row["seq"];
		$board['type'] = $row["type"];
		$board['user_seq'] = $row["user_seq"];
		$board['latitude'] = $row["latitude"];
		$board['longitude'] = $row["longitude"];
		$board['address'] = $row["address"];
		$board['image_name'] = $row["image_name"];
		$board['body'] = $row["body"];
		$board['like'] = $row["like"];
		$board['view_count'] = $row["view_count"];
		$board['date_'] = $row["date_"];
		$board['ip'] = $row["ip"];

		$temp_seq = $board['seq'];
		$temp_user_seq = $board['user_seq'];

		//댓글 갯수구하기
		$query1 = "SELECT count(*) FROM pin_comment_".$get_pin_type." WHERE pin_seq = '$temp_seq' AND status = 'able'"; // SQL 쿼리문
		$result1=mysql_query($query1, $conn); // 쿼리문을 실행 결과

		if( !$result1 ) {
			echo "Failed to list query comment_count_select_pin_chulsa_select.php";
			$isSuccess = 0;
		}
		while( $row1 = mysql_fetch_array($result1) ) {
			$board['comment_count'] = $row1[0];
		}


		//사용자 프로필사진 조회
		$query2 = "SELECT * FROM user WHERE seq = '$temp_user_seq'"; // SQL 쿼리문
		$result2=mysql_query($query2, $conn); // 쿼리문을 실행 결과
		if( !$result2 ) {
			echo "Failed to list query login.php";
			$isSuccess = 0;
		}
		while( $row2 = mysql_fetch_array($result2) ) {
			$board['thumbnail_image'] = $row2["thumbnail_image"];
		}
		

		array_push($boardList, $board);
	}


	//db 조회가 없으면
	if (!$result)
	{
		echo "데이터가 없습니다.";
	}else{	
		echo json_encode($boardList);
	}

	

}




?>