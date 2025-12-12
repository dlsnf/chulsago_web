<?php

include "dbcon.php";

echo "test";
exit;


//user 썸네일 주소 절대값 이미지 파일명 바꾸기
$query1 = "SELECT * FROM user WHERE 1 = 1"; // SQL 쿼리문

$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

if( !$result11 ) {
	echo "Failed to list query thumbnail_image.php";
	$isSuccess = 0;
}

$blockList = array();

while( $row = mysql_fetch_array($result11) ) {

	$get_seq = $row["seq"];
	$image_name_origin = $row["thumbnail_image"];
	$image_pieces = explode("/", $image_name_origin);


	if( $image_name_origin == null || $image_pieces[2] != "samplusil.cafe24.com:8080")
	{
		continue;
	}

	$image_pieces[2] = "hansbuild.cafe24.com";

	echo $image_name_origin."<br>";

	$full_name = "";
	for($i = 0; $i < count($image_pieces); $i++)
	{
		//echo $image_pieces[$i];
		

		if ( $i  == count($image_pieces) - 1 )
		{

				$image_pieces = explode("_", $image_pieces[$i]);

				$image_1 = $image_pieces[0];
				$image_2 = $image_pieces[1];
				$image_3 = $image_pieces[2];
				$image_4 = $image_pieces[3];

				$image_time = explode(":", $image_4);
				$new_time = $image_time[0]."_".$image_time[1]."_".$image_time[2];

				
				//full name
				$full_name_temp = $image_1."_".$image_2."_".$image_3."_".$new_time;

				$full_name = $full_name.$full_name_temp;

		}else{
			$full_name = $full_name.$image_pieces[$i]."/";
		}
	}

	echo $full_name."<br><br>";

	$sqlUpdate = "UPDATE user SET thumbnail_image = '$full_name' WHERE seq = '$get_seq'";

	$res = mysql_query($sqlUpdate,$conn);

	if(!$res)
	{
		echo "db등록 실패";
	}else{
		//디비등록 성공

		$result = @mysql_query("COMMIT", $conn);

	}



}

exit;



//user 프로필 이미지 파일명 바꾸기
$query1 = "SELECT * FROM user WHERE 1 = 1"; // SQL 쿼리문

$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

if( !$result11 ) {
	echo "Failed to list query pin_chulsa_select_block.php";
	$isSuccess = 0;
}

$blockList = array();

while( $row = mysql_fetch_array($result11) ) {

	$get_seq = $row["seq"];
	$image_name_origin = $row["profile_image"];
	$image_pieces = explode("_", $image_name_origin);

	$image_1 = $image_pieces[0];
	$image_2 = $image_pieces[1];
	$image_3 = $image_pieces[2];

	if ( $image_name_origin == ""  || count($image_pieces) != 3)
	{
		continue;
	}else{
	}

	//echo "test ".count($image_pieces)." test";

	echo $get_seq."<br>".$image_name_origin."<br>";


	//time	
	echo $image_3."<br>";

	$image_time = explode(":", $image_3);
	
	$new_time = $image_time[0]."_".$image_time[1]."_".$image_time[2];

	echo $new_time."<br>";

	
	//full name
	$full_name = $image_1."_".$image_2."_".$new_time;
	echo $full_name."<br><br>";

	$sqlUpdate = "UPDATE user SET profile_image = '$full_name' WHERE seq = '$get_seq'";

	$res = mysql_query($sqlUpdate,$conn);

	if(!$res)
	{
		echo "db등록 실패";
	}else{
		//디비등록 성공

		$result = @mysql_query("COMMIT", $conn);

	}

}

exit;





//pin_chulsa 이미지 파일명 바꾸기
$query1 = "SELECT * FROM pin_chulsa WHERE 1 = 1"; // SQL 쿼리문

$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

if( !$result11 ) {
	echo "Failed to list query pin_chulsa_select_block.php";
	$isSuccess = 0;
}

$blockList = array();

while( $row = mysql_fetch_array($result11) ) {

	$get_seq = $row["seq"];
	$image_name_origin = $row["image_name"];
	$image_pieces = explode("_", $image_name_origin);

	$image_1 = $image_pieces[0];
	$image_2 = $image_pieces[1];
	$image_3 = $image_pieces[2];
	$image_4 = $image_pieces[3];



	echo $get_seq."<br>".$image_name_origin."<br>";


	//time	
	echo $image_4."<br>";

	$image_time = explode(":", $image_4);
	$new_time = $image_time[0]."_".$image_time[1]."_".$image_time[2];

	echo $new_time."<br>";

	
	//full name
	$full_name = $image_1."_".$image_2."_".$image_3."_".$new_time;
	echo $full_name."<br><br>";

	$sqlUpdate = "UPDATE pin_chulsa SET image_name = '$full_name' WHERE seq = '$get_seq'";

	$res = mysql_query($sqlUpdate,$conn);

	if(!$res)
	{
		echo "db등록 실패";
	}else{
		//디비등록 성공

		$result = @mysql_query("COMMIT", $conn);

	}

}

exit;



//pin_food 이미지 파일명 바꾸기

$query1 = "SELECT * FROM pin_food WHERE 1 = 1"; // SQL 쿼리문

$result11 = mysql_query($query1, $conn); // 쿼리문을 실행 결과

if( !$result11 ) {
	echo "Failed to list query pin_food_select_block.php";
	$isSuccess = 0;
}

$blockList = array();

while( $row = mysql_fetch_array($result11) ) {

	$get_seq = $row["seq"];
	$image_name_origin = $row["image_name"];
	$image_pieces = explode("_", $image_name_origin);

	$image_1 = $image_pieces[0];
	$image_2 = $image_pieces[1];
	$image_3 = $image_pieces[2];
	$image_4 = $image_pieces[3];



	echo $get_seq."<br>".$image_name_origin."<br>";


	//time	
	echo $image_4."<br>";

	$image_time = explode(":", $image_4);
	$new_time = $image_time[0]."_".$image_time[1]."_".$image_time[2];

	echo $new_time."<br>";

	
	//full name
	$full_name = $image_1."_".$image_2."_".$image_3."_".$new_time;
	echo $full_name."<br><br>";


	$sqlUpdate = "UPDATE pin_food SET image_name = '$full_name' WHERE seq = '$get_seq'";

	$res = mysql_query($sqlUpdate,$conn);

	if(!$res)
	{
		echo "db등록 실패";
	}else{
		//디비등록 성공

		$result = @mysql_query("COMMIT", $conn);

	}


}
exit;



exit;





?>