<?php

// ===== 디버그/진단 로깅 =====
error_log("=== UPLOAD_IMG DEBUG START ===");
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
error_log("CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'NOT_SET'));
error_log("POST keys: " . json_encode(array_keys($_POST)));
error_log("FILES keys: " . json_encode(array_keys($_FILES)));
error_log("post_max_size (ini): " . ini_get('post_max_size'));
error_log("upload_max_filesize (ini): " . ini_get('upload_max_filesize'));
if (isset($_FILES['file'])) {
    error_log("FILES['file']['error']: " . $_FILES['file']['error']);
    error_log("FILES['file']['size']: " . $_FILES['file']['size']);
    error_log("FILES['file']['name']: " . $_FILES['file']['name']);
}
error_log("=== UPLOAD_IMG DEBUG END ===");
// ===== 디버그/진단 로깅 끝 =====

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

// POST 파라미터 안전 추출 (존재 여부 체크)
$get_key = isset($_POST['key']) ? content($_POST['key']) : '';
$get_pin_type = $_POST['pin_type'] ?? '';
$get_seq = $_POST['seq'] ?? '';
$get_latitude = $_POST['latitude'] ?? '';
$get_longitude = $_POST['longitude'] ?? '';
$get_address = $_POST['address'] ?? '';
$get_body = $_POST['body'] ?? '';

// key 검증
if ($get_key != "nuri") 
{
	// JSON 에러 응답으로 통일
	$error_response = json_encode(['error' => 'key error: ' . $get_key]);
	error_log("KEY ERROR: $error_response");
	echo $error_response;
	exit;
}

// firstName
// lastName
// userId

$get_chosenFile_1 = '';
$get_chosenFile_2 = '';
$get_chosenFile_3 = '';
$get_chosenFile_4 = '';
$get_chosenFile_5 = '';
$get_chosenFile_6 = '';
$get_chosenFile_7 = '';
$get_chosenFile_8 = '';
$get_chosenFile_9 = '';
$get_thumbnail = '';
// 업로드 체크 상태: 0=없음/오류, 1=검증오류, 2=성공
$up_chack = 0;


//echo "<br>".$get_manufacturer."<br>";



//용량관리
$dir = "/var/www";
$free = disk_free_space("/var/www");
$total = disk_total_space("/var/www");
$free_to_mbs = round( $free / ((1024*1024)*1024), 1);
$total_to_mbs = round( $total / ((1024*1024)*1024), 1);

//echo "You have" .$free_to_mbs. "GBs from" .$total_to_mbs. "total GBs";
if ($free_to_mbs <= 1)
{
	echo "서버에 용량이 부족합니다.";
	exit;
}



//////////////////////////파일 카운트 관리//////////////////////////
$get_date2 = date('Y-m-d', $stamp);
$get_date3 = date('Y-m-d_H_i_s', $stamp);



//chulsa pin 갯수 조회
$query = "SELECT seq FROM pin_".$get_pin_type." ORDER BY seq DESC LIMIT 0, 1"; // SQL 쿼리문


$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

if( !$result ) {
	echo "Failed to list query upload_img.php";
	$isSuccess = FALSE;
}
while( $row = mysql_fetch_array($result) ) {
	$count = $row['seq'] + 1;
}


$directory = "upload/img/"; // 이건 님의 최종 디렉토리

//해당 디렉토리 안에 파일 개수 알아내기
// if (glob($directory . 'thumbnail/' . "*") != false){
// $count = count(glob($directory . 'thumbnail/' . "*"));

// }

if($count == '')
{
	$count = 1;
}

// if ($count == 0){
// 	$count = 1;
// }else{
// 	$count = ( $count/3 ) + 1;
// }
//echo $count;

//////////////////////////파일 카운트 관리//////////////////////////




################파일 업로드를 위해 추가된 부분 : 시작 ######################### 


	$file_name = $_FILES['file']['name'];//파일이름 dd.png

	//temp_file
	$tmp_file = $_FILES['file']["tmp_name"];

	$error = $_FILES['file']["error"];

	// 사진 회전 각도 초기화
	$degree = 0;

	// -- 안전한 이미지 포맷 검사 --
	// exif_imagetype: 이미지 타입을 반환, 실패하면 false
	if ($tmp_file != '') {
		$imgType = @exif_imagetype($tmp_file);
		if ($imgType === false) {
			// exif_imagetype 실패 시 MIME으로 확인
			$mime = '';
			if (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				if ($finfo) {
					$mime = finfo_file($finfo, $tmp_file);
					finfo_close($finfo);
				}
			}
			if (strpos($mime, 'image/') !== 0) {
				$up_chack = 1;
				$isSuccess = 0;
				echo "올바른 이미지 파일이 아닙니다.";
				exit;
			}
		}
	}

	//echo $file_name."<br>";


// 업로드한 파일이 저장될 디렉토리 정의
$target_dir = $directory;  // 서버에 up 이라는 디렉토리가 있어야 한다.


$file_name_arr = explode(".",$file_name); //파일이름 배열 dd , png
$file_type = end($file_name_arr); //배열의 마지막부분 (png)
$extension = strtolower($file_type); //파일 확장자명 소문자로


$file_name_web = iconv("utf-8","euc-kr", $count . "_" . $get_seq . "_" . $get_date3 . "." . $extension); //웹상에서 쓸 이름 인코딩해야함
$uploadfile = $target_dir . $file_name_web; // 웹상에서 쓸 파일 경로


//파일 절대경로
$file_name_abs = 'http://hansbuild.cafe24.com/chulsago/upload/img/thumbnail/400_'.$file_name_web;

//썸네일 절대경로
	$file_name_thumbnail_abs = 'http://hansbuild.cafe24.com/chulsago/upload/img/thumbnail/400_'.$file_name_web;



//dest_file
$dest_file = "upload/img/" . $file_name_web;

// 파일 업로드 검증 및 처리
if($tmp_file == '')//파일이 없을경우 사진저장 X
{
	$error_response = json_encode(['error' => '사진파일이 없습니다.']);
	error_log("NO_FILE_ERROR: $error_response");
	echo $error_response;
	exit;
}else if(!($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'bmp'|| $extension == 'gif')){
	$up_chack=1;
	$error_response = json_encode(['error' => '사진파일이 아닙니다. (확장자: ' . $extension . ')']);
	error_log("INVALID_EXT_ERROR: $error_response");
	echo $error_response;
	$isSuccess = 0;
	exit;
}else if($_FILES['file']['size'] > 10000*100*30){ //30MB
	$up_chack=1;
	$error_response = json_encode(['error' => '사진 용량이 초과되었습니다. (최대 300MB, 현재: ' . round($_FILES['file']['size']/(1024*1024), 2) . 'MB)']);
	error_log("SIZE_LIMIT_ERROR: $error_response");
	echo $error_response;
	$isSuccess = 0;
	exit;
}else if (preg_match('/\.(php|phtml|inc|html?|cgi)$/i', $file_name)) {
	// 파일명에 위험한 확장자가 포함된 경우 거부
	$up_chack = 1;
	$error_response = json_encode(['error' => '지원하는 파일형식이 아닙니다.']);
	error_log("DANGEROUS_EXT_ERROR: $error_response");
	echo $error_response;
	$isSuccess = 0;
	exit;
}else if(file_exists($target_dir . $file_name_web)) {  // 동일한 파일이 있는지 확인하는 부분
	$up_chack=1;
	$error_response = json_encode(['error' => '동일 파일명이 있습니다.']);
	error_log("DUPLICATE_FILE_ERROR: $error_response");
	echo $error_response;
	$isSuccess = 0;
	exit;
	 }else{

		//사진회전 체크
		$exifData = exif_read_data($tmp_file);

        if($exifData['Orientation'] == 6) { 
            // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 
            $degree = 270; 
        } 
        else if($exifData['Orientation'] == 8) { 
            // 반시계방향으로 90도 돌려줘야 정상 
            $degree = 90; 
        } 
        else if($exifData['Orientation'] == 3) { 
            $degree = 180; 
        } 

        if($degree) { 

            if($exifData['FileType'] == 1) { 
            	$source = imagecreatefromgif($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagegif($source, $dest_file); 
            }else if($exifData['FileType'] == 2) {
            	$source = imagecreatefromjpeg($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagejpeg($source, $dest_file);
            }else if($exifData['FileType'] == 3) {
            	$source = imagecreatefrompng($tmp_file); 
                $source = imagerotate ($source , $degree, 0); 
                imagepng($source, $dest_file);
            }

            //썸네일 저장 함수

			thumbnail($directory,$file_name_web,400);
			thumbnail($directory,$file_name_web,800);
            thumbnail($directory,$file_name_web,1200);
			photoDelete($directory,$file_name_web,400);
			

			//디비등록
			$up_chack = 2;
			//파일 절대경로
			$get_chosenFile_1 = $file_name_abs;
			
			


            
            imagedestroy($source); 
        }else{ 
            //파일업로드
			if(!move_uploaded_file($tmp_file, $directory . $file_name_web ))
			{
				echo "파일 업로드 실패\n";
				echo $directory . $file_name_web;
				$isSuccess = 0;
			}else{

				//썸네일 저장 함수
				thumbnail($directory,$file_name_web,400);
				thumbnail($directory,$file_name_web,800);
	            thumbnail($directory,$file_name_web,1200);
				photoDelete($directory,$file_name_web,400);

				$get_thumbnail = $file_name_thumbnail_abs;
			

				$up_chack = 2;
				//파일 절대경로
				$get_chosenFile_1 = $file_name_abs;
				
				
			} 
		
		

		}
		//업로드할땐 꼭 iconv인코딩 된걸로

	 }




################파일 업로드를 위해 추가된 부분 : 끝 ######################### 


//사진 업로드가 완성된 상태일때
if ($up_chack == 2){
	//echo "사진 업로드 성공";

	//동일한 좌표값 구하기
	$query = "SELECT count(*) FROM pin_chulsa WHERE latitude = '$get_latitude' AND longitude = '$get_longitude'"; // SQL 쿼리문

	$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

	if( !$result ) {
		echo "Failed to list query pin_chulsa_select_count.php";
		$isSuccess = 0;
	}

	while( $row = mysql_fetch_array($result) ) {
		$samsamCount = $row[0];
	}

	//중복된 데이터일때 한칸 이동
	if ( $samsamCount >= 1){
		$get_latitude = (double)$get_latitude + 0.00002;
	}



	$sqlInsert = "INSERT INTO pin_".$get_pin_type."(type, user_seq, latitude, longitude, address, image_name, body, date_, hot_date, ip) VALUES ('$get_pin_type', '$get_seq', '$get_latitude', '$get_longitude', '$get_address','$file_name_web','$get_body','$get_date','$get_date','$get_ip')";


	$res = mysql_query($sqlInsert,$conn);

	if(!$res)
	{
		$error_response = json_encode(['error' => 'db등록 실패']);
		error_log("DB_INSERT_FAILED: $error_response");
		echo $error_response;
		$isSuccess = 0;
		exit;
	}else{
		//디비등록 성공

		if ( $isSuccess == 0 ){
			$result = @mysql_query("ROLLBACK", $conn);
		    $error_response = json_encode(['error' => 'Error RollBack']);
		    error_log("ROLLBACK_ERROR: $error_response");
		    echo $error_response;
		    exit;
		} else{
		    //echo "성공".$isSuccess;
		    $result = @mysql_query("COMMIT", $conn);
		}


		//성공
		$boardList = array();
		$board['seq'] = $get_seq;
		$board['body'] = $get_body;
		$board['status'] = "ok";
		array_push($boardList, $board);
		echo json_encode($boardList);
	}


	
}else{
	$error_response = json_encode(['error' => '사진 업로드 실패']);
	error_log("UPLOAD_FAILED: $error_response");
	echo $error_response;
}


//섬네일 저장함수
function thumbnail($directory,$file_name_web,$photo_size)
{
	//이미지 사이즈 가져오기
	$info_image=getimagesize($directory.$file_name_web);
	
	// getimagesize 실패 대비 (파일 없음 또는 읽기 불가)
	if ($info_image === false) {
		error_log("thumbnail() ERROR: getimagesize failed for $directory$file_name_web");
		return;
	}
	
	$w = $info_image[0]; //가로사이즈
	$h = $info_image[1]; //세로사이즈
/*
	echo "가로:".$w; 
	echo "세로:".$h;
	echo "확장자:".$info_image['mime'];
*/
	//해당 디렉토리 안에 파일 존재 유무
	$file_date_dir = "thumbnail";	
	if(is_dir($directory.$file_date_dir)){

	}else{
		//echo "폴더 존재 X";
		umask(0);
		mkdir($directory.$file_date_dir, 0755); //폴더 생성
	}

/*
	//동일파일 삭제
	if(is_file($directory."thumbnail/".$file_name_web)){
		//echo "파일삭제";
		//echo $directory.$board['profile'];
		unlink($directory."thumbnail/".$file_name_web);
	}
*/

	switch($info_image['mime']){
		case "image/gif":
		$get_type = "gif";
		$origin_img=imagecreatefromgif($directory.$file_name_web);
		break;
		case "image/jpeg":
		$get_type = "jpeg";
		$origin_img=imagecreatefromjpeg($directory.$file_name_web);
		break;
		case "image/png":
		$get_type = "png";
		$origin_img=imagecreatefrompng($directory.$file_name_web);
		break;
		case "image/bmp":
		$get_type = "bmp";
		$origin_img=imagecreatefrombmp($directory.$file_name_web);
		break;
	}

	//사진 비율 구하기
	//가로 : 세로 = 1 : 세로/가로

	// if($w >= 800 || $h >= 800) //이미지 사이즈가 긴축이 800px 이상이면 줄여줌
	// {
	// 	if($w >= $h) //가로가 긴축일때
	// 	{
	// 		$new_width = 800;
	// 		$new_height = $new_width*($h/$w);

	// 		$new_width2 = 600;
	// 		$new_height2 = $new_width2*($h/$w);//600px짜리 썸네일 만들기
	// 	}else{ //세로가 긴축일때
	// 		$new_height = 800;
	// 		$new_width = $new_height*($w/$h);
			
	// 		$new_height2 = 600;
	// 		$new_width2 = $new_height2*($w/$h);	//600px짜리 썸네일 만들기
	// 	}
		
	// }else{
	// 	$new_width = $w;
	// 	$new_height = $h;
	// }

	if($w >= $photo_size || $h >= $photo_size ) //이미지 사이즈가 긴축이 800px 이상이면 줄여줌
	{
		if($w >= $h) //가로가 긴축일때
		{
			$new_width2 = $photo_size;
			$new_height2 = $new_width2*($h/$w);//600px짜리 썸네일 만들기
		}else{ //세로가 긴축일때
			$new_height2 = $photo_size;
			$new_width2 = $new_height2*($w/$h);	//600px짜리 썸네일 만들기
		}
		
	}else{
		$new_width2 = $w;
		$new_height2 = $h;
	}

	



	//1:1기준 이미지틀
	//$new_width = 100;
	//$new_height = 100;

	// 새 이미지 틀을 만든다.
	//$new_img=imagecreatetruecolor($new_width,$new_height);  // 가로  픽셀, 세로 픽셀 //긴축이 800px짜리 만들기
	
	// $new_width2, $new_height2가 유효한 값인지 확인
	if (!isset($new_width2) || !isset($new_height2) || $new_width2 <= 0 || $new_height2 <= 0) {
		error_log("thumbnail() ERROR: Invalid dimensions - width:$new_width2, height:$new_height2");
		return;
	}
	
	$new_img2=imagecreatetruecolor($new_width2,$new_height2);  //긴축이 600px짜리 만들기
	
	$offset_x = 0;
	$offset_y = 0;
	
	//크롭 원본사이즈
	$crop_width = $w;
	$crop_height = $h;

/*
	//1:1 크롭하기
	if($w >= $h) //가로가 클경우 세로기준
	{
		$crop_width = $h;
		$crop_height = $h;
		
		//사진 중앙정렬
		$offset_x = $w/2 - $crop_width/2 ;
	}else{ //세로가 클경우 가로기준
		$crop_width = $w;
		$crop_height = $w;

		//사진 중앙정렬
		$offset_y = $h/2 - $crop_height/2 ;

	}
	*/

	//imagecopyresampled($new_img, $origin_img, 0, 0, $offset_x, $offset_y, $new_width, $new_height, $crop_width, $crop_height);

	imagecopyresampled($new_img2, $origin_img, 0, 0, $offset_x, $offset_y, $new_width2, $new_height2, $crop_width, $crop_height);
	

	//사진 저장
	switch($get_type){
		case "gif":
			// 저장한다.

			//움직이는 gif는 썸네일 안되기때문에 그냥 아래에서 원본 복사
			//$save_path=$directory."thumbnail/".$file_name_web;
			//imagegif($new_img, $save_path);
		break;

		case "jpeg":
			//$save_path=$directory."thumbnail/".$file_name_web;
			//imagejpeg($new_img, $save_path);
			$save_path2=$directory."thumbnail/".$photo_size."_".$file_name_web;
			imagejpeg($new_img2, $save_path2);
		break;

		case "png":
			//$save_path=$directory."thumbnail/".$file_name_web;
			//imagepng($new_img, $save_path);

			$save_path2=$directory."thumbnail/".$photo_size."_".$file_name_web;
			imagepng($new_img2, $save_path2);
		break;

		case "bmp":
			//$save_path=$directory."thumbnail/".$file_name_web;
			//imagewbmp($new_img, $save_path);

			$save_path2=$directory."thumbnail/".$photo_size."_".$file_name_web;
			imagewbmp($new_img2, $save_path2);
		break;

	}

	//썸네일 저장에 실패할경우 원본사진으로 저장하기

/*
	//파일복사
	$oldfile = $directory.$file_name_web; // a.php 라는 파일을 지정합니다
	$newfile = $directory."thumbnail/".$file_name_web; // /test/ 디렉토리 안에 a.php 이름으로 정해 옮길것입니다.

	//파일 찾기
	if(is_file($directory."thumbnail/".$file_name_web)){
		//해당 파일이 있을경우
		
	}else{ //파일이 없을경우
		if(!copy($oldfile, $newfile)) { //복사합니다 
			echo "Error\n$oldfile\n$newfile"; // 에러가 나면 출력합니다 
		} else if(file_exists($newfile)) { // 성공을 할시
			//내용을 입력합니다
		}
	}
	*/

	



	

	/*
	imagecopyresampled($new_img, $origin_img, 0, 0, $offset_x, $offset_y, $width, $height, $crop_width, $crop_height);
	이제껏 내가 본 내장함수 중에 파라미터가 엄청 많다.
	위 함수와 유사한 것으로 'imagecopyresized'가 있다. 파라미터는 동일하다.
	다만 퀄리티가 'imagecopyresampled' 더 낳다고 한다.

	그럼 파라미터에 대해 보자.
	$new_img : 기존 이미지를 축소하여 붙여 넣을 대상
	$origin_img: 기존 이미지

	$offset_x : 기존 이미지의 영역을 기준점으로 부터 x축 좌표를 지정한다.
	$offset_y : 기존 이미지의 영역을 기준점으로 부터 y축 좌표를 지정한다.


	*/

}	


//사진 삭제
function photoDelete($directory,$file_name_web,$photo_size)
{
	//400px짜리 파일없을시에 복사
	$oldfile2 = $directory.$file_name_web; // a.php 라는 파일을 지정합니다
	$newfile2 = $directory."thumbnail/".$photo_size."_".$file_name_web; // /test/ 디렉토리 안에 a.php 이름으로 정해 옮길것입니다.

	//파일 찾기
	if(is_file($directory."thumbnail/".$photo_size."_".$file_name_web)){
		//해당 파일이 있을경우

		if(is_file($directory.$file_name_web)){
			//echo "파일삭제";
			unlink($directory.$file_name_web);
		}

		
	}else{ //파일이 없을경우
		if(!copy($oldfile2, $newfile2)) { //복사합니다 
			echo "Error\n$oldfile\n$newfile"; // 에러가 나면 출력합니다 

		} else if(file_exists($newfile2)) { // 성공을 할시

		
			
			if(is_file($directory.$file_name_web)){
				//echo "파일삭제";
				unlink($directory.$file_name_web);
			}

			$file_name_abs = 'http://hansbuild.cafe24.com/chulsago/upload/img/'.$file_name_web;

		}
	}
}





