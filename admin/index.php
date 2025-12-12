<?php

	include "common.php";
	include "dbcon.php";

	session_start(); // 세션을 시작헌다.

	$get_pin_type = isset($_GET['pin_type']) ? $_GET['pin_type'] : 'chulsa';


	if(isset($get_pin_type)){
	}else{
		$get_pin_type = "chulsa";
		//echo "ZZ";
	}


	if( $get_pin_type == "chulsa" ){
		$menu_color1 = "white;font-weight: bold;";
		$menu_color2 = "black;font-weight: 700;";
	}else if( $get_pin_type == "food" ){
		$menu_color1 = "black;font-weight: 700;";
		$menu_color2 = "white;font-weight: bold;";
	}

	
	//echo $url_abs;
	if(isset($_SESSION['id']) && $_SESSION['id'] == 'admin')
	{
		
		$query = "SELECT PR.*, UR.name, PC.status FROM pin_report as PR LEFT JOIN user as UR ON UR.seq = PR.pin_user_seq LEFT JOIN pin_" . $get_pin_type . " as PC ON PC.seq = PR.pin_seq WHERE PR.pin_type = '$get_pin_type' ORDER BY PR.seq DESC";

		$result = mysql_query($query, $conn);

		if( !$result ) {
			echo "Failed to list query index";
			$isSuccess = FALSE;
		}

		$boardList = array();

		while( $row = mysql_fetch_array($result) ) {
			$board['seq'] = $row['seq'];
			$board['pin_seq'] = $row['pin_seq'];
			$board['pin_type'] = $row['pin_type'];
			$board['pin_user_seq'] = $row['pin_user_seq'];
			$board['reporter_seq'] = $row['reporter_seq'];
			$board['body'] = nl2br(strip_tags($row['body']));
			$board['ip'] = $row['ip'];
			$board['date_'] = $row['date_'];
			$board['status'] = $row['status'];

			array_push($boardList, $board);
		}

	}else{

	}


	


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>출사누리-ADMIN</title>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<!--<meta name="viewport" content="width=1200, initial-scale=0.5,minimum-scale=1.0,maximum-scale=2.0,user-scalable=yes">-->
<meta name="viewport" content="width=1200, maximum-scale=2.0, user-scalable=yes">
		<!-- 키워드 태그 -->
		<!-- 키워드 태그 -->
		<meta name="description" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비">
		<meta name="keywords" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비">
		<meta name="author" content="광덕제일기초">
		<meta property="og:image" content="../images/logo_main_min.png"/>
		<meta property="og:title" content="gwangteok.com"/>
		<meta property="og:description" content="광덕제일기초,광덕,제일기초,건설,건축,건축장비" />

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

<?php
	if($_SESSION['id'] != 'admin')
	{
?>
		<div class="login" >

			<div class="login_box">
				<span class="title">출사누리 관리자 로그인</span>

				<div class="login_form">
					
					<form name="form_1" action="login.php" method="POST">
						<span class="info">ID : </span><input type="text" name="id" class="id" placeholder="ID" value="" maxlength="100" autocomplete="off" required="">
						<span class="info">PW : </span><input type="password" name="pw" class="pw" placeholder="PASSWORD" value="" maxlength="100" autocomplete="off" required="">

						<input type="submit" class="sm" value="LOGIN">
					</form>

				</div>
			</div>

		</div>


<?php
	}else{

		
?>
	<div class="top">

		<span>관리자님 환영합니다!</span><span class="logout" onclick="location.href='logout.php';">LOGOUT</span>

	</div>


	<div class="wrap">

		<div class="menu">
			<ul>
				<li onclick="location.href='http://samplusil.cafe24.com:8080/chulsago/admin/?pin_type=chulsa'"><span style="color:<?=$menu_color1?>">신고 관리(chulsa)</span></li>
				<li onclick="location.href='http://samplusil.cafe24.com:8080/chulsago/admin/?pin_type=food'"><span style="color:<?=$menu_color2?>">신고 관리(food)</span></li>
				<li><span>준비중</span></li>
				<li><span>준비중</span></li>
			</ul>
		</div>


		<div class="content">

			<table>
				<thead>
					<tr >
						<th>seq</th>
						<th>핀_seq</th>
						<th>핀_type</th>
						<th>사용자_seq</th>
						<th>신고자_seq</th>
						<th>내용</th>
						<th>ip</th>
						<th>날짜</th>
						<th>상태</th>
						<th>비고</th>
					</tr>
				</thead>

				<tbody>
					<?php
						// $count = 1;
						 foreach($boardList as $board) {


					?>
					<tr style="cursor:pointer;" onclick="location.href='detail.php?pin_seq=<?php echo $board['pin_seq'];?>&pin_type=<?php echo $board['pin_type'];?>';">
						<td><?=$board['seq']?></td>
						<td style="font-weight:bold;"><?=$board['pin_seq']?></td>
						<td><?=$board['pin_type']?></td>
						<td><?=$board['pin_user_seq']?></td>
						<td><?=$board['reporter_seq']?></td>
						<td style="text-align:left; padding:10px;"><?=$board['body']?></td>
						<td><?=$board['ip']?></td>
						<td><?=$board['date_']?></td>
						<td style='font-weight:bold; <?php
							if ($board['status'] == "delete"){
								echo "color:red;";
							}else if ($board['status'] == "able"){
								echo "color:green;";
							}
						?>
						''><?=$board['status']?></td>
						<td></td>
					</tr>
					<?php
						//$count = $count + 1 ;
						}
					?>
				</tbody>

			</table>

		</div>

<script>

function board_delete(seq, photo)
{
	
	if (confirm("정말로 삭제 하시겠습니까?")) { 
		location.replace("delete_db.php?seq="+seq+"&photo="+photo);
	}else {

	}	

}


//파일업로드관련
var bug_index = new Array();


function file_change(obj)
{
	//id 값 구하기
	var id = obj.id;
	//alert(id);


	
	//부모의 div 클래스명 구하기 ex) file_div_1
	var parent_name = $('#'+id).closest("div").attr("class").split(" ")[1];
	//alert(parent_name);

	//부모의 div 클래스명의 인덱스값 구하기
	var parent_name_index = parent_name.split("_")[2] - 1;
	//alert(parent_name_index);

	//alert(parent_name_index);
	

	$("."+parent_name+" #img_preview").attr('src','');
	$("."+parent_name+" .file_status").text('');
	$("."+parent_name+" .file_size").text('');

	if( $('#'+id).val() != '' )
	{

		$("."+parent_name+" .file_label").css({'opacity':'0.5','filter':'alpha(opacity=50)'});

		var file_way = $('#'+id).val().split("\\"); // dlsnf.png ex)file_way[2]

		var file_name = $('#'+id)[0].files[0].name; // dlsnf.png 

		var file_name_arr = file_way[2].split("."); // dlsnf png

		//var file_name = file_name_arr[0]; //dlsnf

		var file_type = file_name_arr[file_name_arr.length - 1].toLowerCase(); // png 소문자 변환

		var file_size = $('#'+id)[0].files[0].size/1024/1000;
		file_size = file_size.toFixed(2);

		//alert(file_name);

		//alert( file_name );
		//alert( file_type );
	
		if( file_type == 'jpg' ||  file_type == 'jpeg' ||  file_type == 'png' ||  file_type == 'bmp'||  file_type == 'gif' )
		{
			bug_index[parent_name_index] = true;	

			//alert("이미지 파일입니다.");

			$("."+parent_name+" #img_preview").css({'display':'block'});
			$("."+parent_name+" .file_status").text(file_name);
			$("."+parent_name+" .file_size").text(file_size + 'MB');			

		}else{
			bug_index[parent_name_index] = false;

			$("."+parent_name+" #img_preview").css({'display':'none'}); $('#'+id).val('');

			$("."+parent_name+" .file_label").css({'opacity':'1','filter':'alpha(opacity=100)'});	
			
			$("."+parent_name+" .file_status").text('');
			$("."+parent_name+" .file_size").text('');

			alert("jpg, png, bmp, gif (이미지) 파일만 업로드 할수 있습니다.");
					
		}

		if(file_size > 20 ){

			bug_index[parent_name_index] = false;

			$("."+parent_name+" #img_preview").css({'display':'none'}); $('#'+id).val('');

			$("."+parent_name+" .file_label").css({'opacity':'1','filter':'alpha(opacity=100)'});	
			
			$("."+parent_name+" .file_status").text('');
			$("."+parent_name+" .file_size").text('');

			alert("첨부파일 용량 제한을 초과하였습니다. ( 20MB )");
		}
	
	}

}

$(window).ready(function(){

	//파일 사진 업로드 미리보기 초기화	

	//파일 갯수 구하기
	var file_lenth = $(".file_div").length;

	//초기화	
	for(var i = 1; i <= file_lenth ; i++)
	{
		bug_index[i-1] = false;

		var opt = {
			img: $("#img_preview_"+i),
			w: 96,
			h: 96
		};
		
		
		$('#chosenFile_'+i).setPreview(opt,i);
	}

});

$.fn.setPreview = function(opt,index){
    "use strict"
    var defaultOpt = {
        inputFile: $(this),
        img: null,
        w: 200,
        h: 200
    };

    $.extend(defaultOpt, opt);

    var previewImage = function(){


        if (!defaultOpt.inputFile || !defaultOpt.img) return;
 
        var inputFile = defaultOpt.inputFile.get(0);
        var img       = defaultOpt.img.get(0);

        //alert(defaultOpt.img.get(0).value);
 
        // FileReader
        if (window.FileReader) {
            // image 파일만
            if (!inputFile.files[0].type.match(/image\//)) return;
 
            // preview
            try {
                var reader = new FileReader();
                reader.onload = function(e){
                    img.src = e.target.result;
                    img.style.width  = defaultOpt.w+'px';
                    img.style.height = defaultOpt.h+'px';
                    img.style.display = '';
                }
                reader.readAsDataURL(inputFile.files[0]);
            } catch (e) {
                // exception...
            }
        // img.filters (MSIE)
        } else if (img.filters) {
            inputFile.select();
            inputFile.blur();
            var imgSrc = document.selection.createRange().text;
 
            img.style.width  = defaultOpt.w+'px';
            img.style.height = defaultOpt.h+'px';
            img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enable='true',sizingMethod='scale',src=\""+imgSrc+"\")";            
            img.style.display = '';
        // no support
        } else {
            // Safari5, ...
        }
    };
 
    // onchange
    $(this).change(function(){
		if (bug_index[index-1])
		{
			//alert(index);
			previewImage();
		}
        
    });
};




</script>
		<div class="content_write" style="display:none;">

			<h1>--- 사진 등록하기 ---</h1>
			<h1>( 가로사진, 2000px 이상만 )</h1>

			<form name="form_1" action="upload.php" method="POST"  accept-charset="utf-8" ENCTYPE="multipart/form-data">

				<span class="info">제목 : <br></span><input type="text" name="model_title" class="model_title" placeholder="제목" value="" maxlength="100" autocomplete="off" required=""><br>
				<span class="info">설명 : <br></span><textarea name="model_textarea" class="inputbox model_textarea" placeholder="내용" value="" maxlength="10000" autocomplete="off" required=""></textarea>

				<div class="input_file">
					<h2>사진을 등록하세요</h2>
					<div class="input_file_body">
						<div class="input_file_upload">
							<span class="info"><span>사진 1</span></span>
							<span class="sl">
								
								<div class="file_div file_div_1">	
									<img id="img_preview_1" class="img_preview
									" width="96px" height="96px" style="z-index:3; display:none;">

									<label class="file_label_click" for="chosenFile_1"></label>
									<label class="file_label" for="chosenFile_1">+</label>
									
									<input type="file" id="chosenFile_1" name="chosenFile_1"  accept="image/*" class="chosenFile" onchange="file_change(this);">

									<span class="file_status"></span>
									<span class="file_size"></span>
								
								</div>	

							</span>					    			
						</div>
					</div><!-- input_file_body -->


				</div><!-- input_file -->



				<input type="submit" class="sm" value="등록">

			</form>

		</div>

	</div>

<?php
}
?>
	</div>


	</body>
</html>
		