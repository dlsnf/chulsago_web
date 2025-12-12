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


$get_temp = explode(" ", $_POST['pin_seq']);
$get_temp2 = explode(" ", $_POST['pin_type']);
$get_temp3 = explode(" ", $_POST['status']);

$get_pin_seq = $get_temp[0]; //공격방지
$get_pin_type = $get_temp2[0];
$get_status = $get_temp3[0];


//db삭제 쿼리
$sqlUpdate = "UPDATE pin_".$get_pin_type." SET status = '$get_status' WHERE seq = '$get_pin_seq'";


$res = mysql_query($sqlUpdate,$conn);
if(!$res)
{
	echo "db 실패";
	//echo $get_seq.$get_photo;
	exit;
}else{


	?>
	<script>
		window.history.go(-1);
	</script>	
	<?php
}



?>