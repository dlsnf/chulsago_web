<?php

$get_ip = $_SERVER['REMOTE_ADDR'];

$stamp = time();
$get_date = date('Y-m-d H:i:s', $stamp);

$get_key = content($_POST['key']);
$get_seq = content($_POST['seq']);
$get_name = content($_POST['nuri']);

//특수문자 제거함수
function content($text){
 $text = strip_tags($text);
 $text = htmlspecialchars($text);
 $text = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $text);
 return $text;
}

$boardList = array();

$board['seq'] = $get_key;
$board['name'] = "nuri";
$board['age'] = "25";
array_push($boardList, $board);

$board['seq'] = "2";
$board['name'] = "nuri2";
$board['age'] = "26";
array_push($boardList, $board);

if ( $get_key == "nuri" ){
	echo json_encode($boardList);
}else{
	echo "Key error";
}

//echo "nuri";

/*

$boardList = array();

while( $row3 = mysql_fetch_array($result3) ) {
	$board['like_c'] = $row3['count'];
	array_push($boardList, $board);
}


if($result) {
	echo json_encode($boardList);
} else {
	echo "처리하지 못했습니다.";
}
*/
?>