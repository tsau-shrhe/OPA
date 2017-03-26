<?php
  $userlog_email = $_POST['email'];
  $enabled = $_POST['enabled'];

  include 'include/PDO.php';

  $reslut = $pdo -> query("SELECT * FROM opa_member where email= '".$userlog_email."'");
  $userdata = $reslut->fetchAll();

  if ($enabled == $userdata[0]['code']) {
    $statement = $pdo -> query("UPDATE opa_member SET enabled = '1' WHERE email = '".$userlog_email."'");
    $statement->execute();
    $con = '<div class="alert alert-success text-center" role="alert"><strong><h1>開通成功!!</h1></strong></br><a href="index.php">點我回登入頁</a></div>';
  }else{
    $con = '<div class="alert alert-danger text-center" role="alert"><strong><h1>開通失敗!!</h1></strong></br><a href="index.php">點我回登入頁</a></div>';
  }

  $pdo = NULL;
?>
<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>開通頁面</title>
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