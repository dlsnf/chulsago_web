<?php

	include "common.php";
	include "dbcon.php";

	session_start(); // 세션을 시작헌다.

	$get_pin_seq = $_GET['pin_seq'];
	$get_pin_type = $_GET['pin_type'];


	if ( isset($_SESSION['id']) ){

	}else{
		//exit;
	}

	
	//echo $url_abs;
	if($_SESSION['id'] == 'admin')
	{
		
		$query="SELECT * FROM pin_".$get_pin_type." WHERE seq = '$get_pin_seq'"; // SQL 쿼리문

		$result=mysql_query($query, $conn); // 쿼리문을 실행 결과

		if( !$result ) {
			echo "Failed to list query detail";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['type'] = $row['type'];
			$board['user_seq'] = $row['user_seq'];
			$board['latitude'] = $row['latitude'];
			$board['longitude'] = $row['longitude'];
			$board['address'] = $row['address'];
			$board['image_name'] = $row['image_name'];
			$board['body'] = nl2br(strip_tags($row['body']));
			$board['like'] = $row['like'];
			$board['view_count'] = $row['view_count'];
			$board['status'] = $row['status'];
			$board['date_'] = $row['date_'];
			$board['ip'] = $row['ip'];

			array_push($boardList, $board);
		}

	}else{

	}

	if ( $board['status'] == "able"){
		$selected1 = "selected";
		$selected2 = "";
		$selected3 = "";
	}else if ( $board['status'] == "disable"){
		$selected1 = "";
		$selected2 = "selected";
		$selected3 = "";
	}else if ( $board['status'] == "delete"){
		$selected1 = "";
		$selected2 = "";
		$selected3 = "selected";
	}
	
	


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>출사누리-ADMIN-DETAIL</title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<!--<meta name="viewport" content="width=1200, initial-scale=0.5,minimum-scale=1.0,maximum-scale=2.0,user-scalable=yes">-->
<meta name="viewport" content="width=1200, maximum-scale=2.0, user-scalable=yes">
		<!-- 키워드 태그 -->
		<!-- 키워드 태그 -->
		<!-- <meta name="description" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비">
		<meta name="keywords" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비">
		<meta name="author" content="광덕제일기초"> -->
		<meta property="og:image" content="../images/logo_main_min.png"/>
		<meta property="og:title" content="gwangteok.com"/>
		<!-- <meta property="og:description" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비" /> -->

		<!-- icon -->
		<link rel="icon" href="../images/logo_icon_200.png" type="image/x-icon">
		<link rel="shortcut icon" href="../images/logo_icon_200.png" type="image/x-icon">
		<link rel="shortcut icon" href="../images/logo_icon_200.png" type="image/vnd.microsoft.icon">
		<link rel="apple-touch-icon" href="../images/logo_apple_min.png">
		<link rel="apple-touch-icon-precomposed" href="../images/logo_apple_min.png">


		<meta name="format-detection" content="telephone=no">
		<script src="js/jquery-1.11.0.min.js"></script>
		<link rel="stylesheet" href="css/style.css?<?=filemtime('css/style.css')?>">



	</head>
	<body>

	
	<div class="all_wrap">


	<div class="top">

		<span>관리자님 환영합니다!</span><span class="logout" onclick="location.href='logout.php';">LOGOUT</span>

	</div>


	<div class="wrap">

		<div class="menu" style="padding:10px;">
			<span style="margin:10px;">신고 핀 상세보기 페이지</span>
			<div style="margin:10px auto; border:1px solid #000; cursor:pointer; border-radius: 10px; width:80px; height:20px;" onclick="window.history.go(-1);">뒤로가기</div>
		</div>


		<div class="content" style="margin-top:10px;">

				
		
											


			<span>pin_seq : <?=$board['seq']?></span><br><br>
			<span>pin_type : <?=$board['type']?></span><br><br>
			<span>user_seq : <?=$board['user_seq']?></span><br><br>
			<div id="map" style="margin:0 auto; width:400px; height:400px;"></div><br><br>
			<span>latitude : <?=$board['latitude']?>, longitude : <?=$board['longitude']?></span><br><br>

			<span>address : <?=$board['address']?></span><br><br>
			
			<span><img src="http://samplusil.cafe24.com:8080/chulsago/upload/img/thumbnail/800_<?php echo $board['image_name'];?>" width="300px"/></span><br><br>
			<span>body : <?=$board['body']?></span><br><br>
			<span>like : <?=$board['like']?></span><br><br>
			<span>view_count : <?=$board['view_count']?></span><br><br>
			<span style="font-weight:bold;">status : <?=$board['status']?></span><br><br>
			<span>date_ : <?=$board['date_']?></span><br><br>
			<span>ip : <?=$board['ip']?></span><br><br>


			<form class="form1" name="form1" id="form1" method="POST" accept-charset="utf-8" ENCTYPE="multipart/form-data" action="status_change.php">
				<select class="select_status" name="status" style="font-size:30px;">
					<option value="able" <?=$selected1?>>able</option>
					<option value="disable" <?=$selected2?>>disable</option>
					<option value="delete" <?=$selected3?>>delete</option>
				</select>
				<input type="hidden" name="pin_seq" value="<?=$board['seq']?>">
				<input type="hidden" name="pin_type" value="<?=$board['type']?>">
				<input type="submit" value="변경">
			</form>




		</div>


	</body>
</html>

		<script>
      function initMap() {
		var uluru = {lat: <?php echo $board['latitude'];?>, lng: <?php echo $board['longitude'];?>};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCReof11Wu4Q0332Rp6-P6yPxJm4wk1G2E&callback=initMap">
    </script>