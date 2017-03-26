<?php
$userlog_email = $_COOKIE["email"];
$userlog_password = base64_decode($_COOKIE["password"]);

if (!empty($userlog_email) && !empty($userlog_password)) {
  include "include/PDO.php";

  $reslut = $pdo -> query("SELECT * FROM opa_member where email= '".$userlog_email."'");
	$rows = $reslut->fetchAll();
	$user = $rows[0];
  if ( $user['authority'] != 1 ) {
  	$pdo = NULL;
    header("location:index.php");
  }
}else{
	$pdo = NULL;
  header("location:index.php");
}
$pdo = NULL;

if (isset($_POST['logout'])) {
	$day = strtotime("-1 days");
  setcookie("email","",$day);
  setcookie("password","",$day);
  header("location:index.php");
}
?>
<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>管理頁面</title>
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
    <div class="container-fluid"><h1>
    <img class="img-circle" src="thumb/<?php echo $user['id']; ?>.jpg" alt="照片" width="140" height="140">
    	<?php echo $user['nickname'];?>,您好~歡迎回來管理頁面。</h1>
    	<hr>
    	<div class="row">
    		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
    			<a href="user_insert.php" type="button" class="btn btn-primary btn-block">新增會員</a>
    		</div>
    		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
    		<form action="" method="POST">
    			<button type="submit" class="btn btn-success btn-block" name="logout">登出</button>
    		</form>
    		</div>
    	</div>
    	<hr>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
						<th>#編號</th>
						<th>帳號</th>
						<th>密碼</th>
						<th>姓名</th>
						<th>綽號</th>
						<th>地址</th>
						<th>開通</th>
						<th>權限</th>
						<th>認證碼</th>
						<th>功能鈕</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include("include/PDO.php");
						$reslut = $pdo -> query("SELECT * FROM opa_member ORDER BY id DESC");
						$reslut->setFetchMode(PDO::FETCH_ASSOC);
						$rows = $reslut -> fetchall();

						foreach ($rows as $key => $value) {
							echo '<tr>';
							foreach ($value as $label => $val) {
								echo '<td>'.$val.'</td>';
							}
							echo'<td><a class="btn btn-primary" href="user_updata.php?id='.$value['id'].'" role="button">修改</a> <a class="btn btn-danger" href="user_del.php?id='.$value['id'].'" role="button">刪除</a></td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
    </div>
    </div>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</body>
</html>