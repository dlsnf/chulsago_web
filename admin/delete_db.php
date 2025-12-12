<?php
include "common.php";
include "dbcon.php";

header("Content-Type: text/html; charset=utf-8");

session_start(); // 세션을 시작헌다.


if (isset($_SESSION['id'])) //admin
{
	
}else{
	echo "잘못된 접근입니다.";
	exit;
}


$get_temp = explode(" ", $_GET['seq']);
$get_temp2 = explode(" ", $_GET['photo']);

$get_seq = $get_temp[0]; //공격방지
$get_photo = $get_temp2[0];



//db삭제 쿼리
$sqldelete = "DELETE FROM portfolio WHERE seq=".$get_seq;


$res = mysql_query($sqldelete,$conn);
if(!$res)
{
	echo "db삭제 실패";
	echo $get_seq.$get_photo;
	exit;
}else{

	//db삭제 성공, 사진 삭제

	$directory = "upload/up_img/thumbnail/"; // 디렉토리


	//echo $file_name."<br>";
	if($get_photo != '')
	{


		if(is_file($directory.$get_photo)){
			//echo "파일삭제";
			unlink($directory.$get_photo);
		}else{
			echo "파일삭제실패";
			exit;
		}

	}
	

	

	?>
	<script>
		location.replace("index.php");
	</script>	
	<?php
}



?>