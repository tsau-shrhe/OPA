<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>登入</title>
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
      <form action="signin.php" method="post" class="form-signin">
        <h2 class="form-signin-heading text-center"><i class="fa fa-user" aria-hidden="true"></i> 會員登入</h2>
        <label for="inputEmail" class="sr-only">帳號</label>
        <input type="email" name="email" class="form-control" placeholder="信箱" required autofocus>
        <label for="inputPassword" class="sr-only">密碼</label>
        <input type="password" name="password" class="form-control" placeholder="密碼" required>
        <div class="checkbox">
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">登入</button>
        </br>
        <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        		<a href="registered.php">加入會員</a> |
        	</div>
        </div>
      </form>
    </div>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</body>
</html>