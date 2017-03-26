<?php

$userlog_email = $_COOKIE["email"];
$userlog_password = base64_decode($_COOKIE["password"]);

if (!empty($userlog_email) && !empty($userlog_password)) {
  include 'include/PDO.php';

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

$id = $_GET['id'];

include 'include/PDO.php';

$reslut_id = $pdo -> query("SELECT * FROM opa_member where id= '".$id."'");
$rows = $reslut_id->fetchAll();
$men = $rows[0];

$file = $_FILES["file"];

switch ($file['type']) {
  case 'image/jpeg':
    $file_type = true;
    break;
  case '':
    $file_type = true;
    break;
  default:
    $file_type = false ;
    break;
}

if (isset($_POST['send']) && $file_type) {

  class member{
  public $email;
  public $password;
  public $name;
  public $nickname;
  public $address;
  public $enabled;
  public $authority;
  }

  $new_data = new member;
  $new_data->email = $_POST['email'];
  $new_data->password = $_POST['password'];
  $new_data->name = $_POST['name'];
  $new_data->nickname = $_POST['nickname'];
  $new_data->address = $_POST['address'];
  $new_data->enabled = $_POST['enabled'];
  $new_data->authority = $_POST['authority'];

  $reslut = $pdo -> query("SELECT * FROM opa_member where email= '".$new_data->email."'");
  $rows = $reslut->fetchAll();
  $old_data = $rows[0];

  if ( $new_data->password != $old_data['password']) {
    $new_data->password = md5($new_data->password);
  }

  $new_data->enabled = ($new_data->enabled == "on") ? 1 : 0 ;
  $new_data->authority = ($new_data->authority == "on") ? 1 : 0 ;

  $statement = $pdo->prepare("UPDATE opa_member SET password = :password, name = :name, nickname = :nickname, address = :address, enabled = :enabled, authority = :authority WHERE email = :email");

  $statement->execute(array(
    "email" => $new_data->email ,
    "password" => $new_data->password ,
    "name" => $new_data->name ,
    "nickname" => $new_data->nickname ,
    "address" => $new_data->address ,
    "enabled" => $new_data->enabled ,
    "authority" => $new_data->authority
  ));

  if ($file['type'] != '') {
  $src = imagecreatefromjpeg($_FILES['file']['tmp_name']);
  $src_w = imagesx($src);
  $src_h = imagesy($src);

  if($src_w > $src_h){
    $thumb_w = 90;
    $thumb_h = intval($src_h / $src_w * 90);
  }else{
    $thumb_h = 90;
    $thumb_w = intval($src_w / $src_h * 90);
  }

  $thumb = imagecreatetruecolor($thumb_w, $thumb_h);

  imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);

  imagejpeg($thumb, "thumb/".$old_data['id'].".jpg");
  }
  header("location:admin.php");
}

$pdo = NULL;
?>
<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>管理頁面-會員資料修改</title>
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
    <div class="container">
      <form action="user_updata.php" method="POST" enctype="multipart/form-data" class="form-signin" style="margin-top: -50px;">
        <h2 class="form-signin-heading text-center"><i class="fa fa-user" aria-hidden="true"></i>  會員資料修改</h2><br>
        <div class="form-group">
          <label for="email">電子信箱(帳號)</label>
          <input type="email" class="form-control" name="" placeholder="輸入e-mail" value="<?php echo $men['email'];  ?>" disabled>
          <input type="hidden" name="email" value="<?php echo $men['email'];  ?>">
        </div>
        <div class="form-group">
          <label for="password">密碼</label>
          <input type="text" class="form-control" name="password" placeholder="輸入密碼" value="<?php echo $men['password'];  ?>" >
        </div>
        <div class="form-group">
          <label for="name">會員名稱</label>
          <input type="text" class="form-control" name="name" placeholder="輸入會員名稱" value="<?php echo $men['name'];  ?>">
        </div>
        <div class="form-group">
          <label for="nickname">暱稱</label>
          <input type="text" class="form-control" name="nickname" placeholder="輸入暱稱" value="<?php echo $men['nickname'];  ?>">
        </div>
        <div class="form-group">
          <label for="address">地址</label>
          <input type="text" class="form-control" name="address" placeholder="輸入地址" value="<?php echo $men['address'];  ?>">
        </div>
        <div class="form-group">
          <div class="checkbox alert alert-danger">
            <label>
              <input type="checkbox" name="authority" 
                <?php if ($men['authority'] == 1) {echo 'checked';}?>> 是否登記為網站管理員(最高權限)
            </label>
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox alert alert-success">
            <label>
              <input type="checkbox" name="enabled"
                <?php if ($men['enabled'] == 1) {echo 'checked';}?>> 帳號啟用(手動開通)
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="img">照片上傳</label>
          <input type="file" id="file" name="file" accept=".jpg">
          <p class="help-block">限JPG格式照片。</p>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="myBtn" name="send">送出資料</button>
        </br>
        <a class="btn btn-lg btn-warning btn-block" href="admin.php" role="button">取消返回</a>
      </form>
    </div>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>