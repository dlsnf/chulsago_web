<?php

include "common.php";


session_start(); // 세션을 시작헌다.


$logout = $_SESSION['id'];




if($_SESSION['id']!=NULL)
{
	session_destroy();
?>
<script>
//alert("로그아웃 되었습니다.");
location.href="<?=$url?>";
</script>
<?php
}else{
	session_destroy();
?>
<script>
//alert("로그아웃 되었습니다.");
location.href="<?=$url?>";
</script>
<?php
}
?>
