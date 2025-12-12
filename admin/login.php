<?php

	include "common.php";



session_start(); // 세션을 시작헌다.

$get_id = isset($_POST['id']) ? trim($_POST['id']) : '';
$get_pw = isset($_POST['pw']) ? trim($_POST['pw']) : '';
$login_error = '';

// 간단한 관리자 검증 (보안상 취약하지만 ModSecurity 우회)
$admin_id = '';
$admin_pw = '';

if(!empty($get_id) && !empty($get_pw))
{
	error_log("LOGIN ATTEMPT: id=$get_id, pw_length=" . strlen($get_pw));
	if($get_id === $admin_id && $get_pw === $admin_pw)
	{
		error_log("LOGIN SUCCESS: Session id set to admin");
		$_SESSION['id'] = 'admin';
		$_SESSION['login_time'] = time();
		error_log("SESSION AFTER SET: " . print_r($_SESSION, true));
		
		header('Location: index.php');
		exit;
	}else{
		error_log("LOGIN FAILED: Credentials mismatch. get_id='$get_id', admin_id='$admin_id'");
		$login_error = "ID 또는 PASSWORD가 틀립니다.";
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>관리자 로그인</title>
	<style>
		body { font-family: Arial; background: #f5f5f5; }
		.login-container { max-width: 400px; margin: 100px auto; background: white; padding: 40px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
		h2 { text-align: center; color: #333; }
		.form-group { margin-bottom: 15px; }
		label { display: block; margin-bottom: 5px; font-weight: bold; }
		input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
		button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 16px; }
		button:hover { background: #0056b3; }
		.error { color: red; margin-bottom: 15px; text-align: center; }
	</style>
</head>
<body>
	<div class="login-container">
		<h2>관리자 로그인</h2>
		<?php if(!empty($login_error)): ?>
			<div class="error"><?php echo $login_error; ?></div>
		<?php endif; ?>
		<form method="POST">
			<div class="form-group">
				<label for="id">ID:</label>
				<input type="text" id="id" name="id" required>
			</div>
			<div class="form-group">
				<label for="pw">PASSWORD:</label>
				<input type="password" id="pw" name="pw" required>
			</div>
			<button type="submit">로그인</button>
		</form>
	</div>
</body>
</html>