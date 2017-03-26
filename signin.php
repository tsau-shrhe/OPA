<?php

	$userlog_email = $_POST['email'];
	$userlog_password = md5($_POST['password']);

	include 'include/PDO.php';

	$reslut = $pdo -> query("SELECT * FROM opa_member where email= '".$userlog_email."'");

	if ($reslut->rowCount() == 0) {
		$con = '<div class="alert alert-danger text-center" role="alert"><strong><h2>登入帳戶資料有誤!!</h2></strong><a href="index.php">點我回登入頁</a></div>';
	}else{
		$userdata = $reslut->fetchAll();
		if ($userlog_password == $userdata[0]['password']) {
			if ($userdata[0]['enabled'] == 0 ) {
				$con = '<div class="alert alert-info text-center" role="alert"><strong><h2>該帳戶尚未開通!!</h2></strong>			<form action="enabled.php" method="POST">
					<input type="hidden" name="email" value="'.$userlog_email.'">
					<input type="text" name="enabled" placeholder="驗證碼" required>
					<button class="btn btn-lg btn-primary " type="submit" >送出開通</button>
			</div>
			</form></div>';
			}else{
				$day = strtotime("+1 days");
    		setcookie("email",$userlog_email,$day);
    		setcookie("password",base64_encode($userlog_password),$day);
				if ($userdata[0]['authority'] == 1) {
					header("location:admin.php");
				}else{
					$con = '<div class="col-lg-4">
          <img class="img-circle" src="thumb/'.$userdata[0]["id"].'.jpg" alt="照片" width="140" height="140">
          <h2>'.$userdata[0]["nickname"].'</h2>
          <p>您好!!歡迎回來~</p>
          <form action="" method="POST" ><input class="btn btn-success" type="submit" name="logout" value="登出"  /></form></p>
        </div>';
				}
			}

		}else{
			$con = '<div class="alert alert-danger text-center" role="alert"><strong><h2>登入帳戶資料有誤!!</h2></strong><a href="index.php">點我回登入頁</a></div>';
		}
	}

	if (isset($_POST["logout"])){
  	$day = strtotime("-1 day");
  	setcookie("email","",$day);
  	setcookie("password","",$day);
  	header("location:index.php");
	}
$pdo = NULL;
?>
<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>登入資訊頁</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/signin.css" rel="stylesheet">
  <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
  <link href="fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  <body>
    <div class="container">
			<?php
				echo($con);
			?>
    </div>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</body>
</html>