<?php

//절대 url 값 구하기 ex)http://samplusil.cafe24.com/
$url_abs = "http://".$_SERVER['HTTP_HOST']."/";

//현재 url 값 구하기 ex)http://samplusil.cafe24.com/bbs/
$url_temp = explode("/","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$url_count = count($url_temp);

$url = '';
for($i = 0; $i < $url_count -1 ; $i++)
{
	$url .= $url_temp[$i]."/";
}

//echo $url;

?>


