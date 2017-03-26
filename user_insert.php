<?php

$userlog_email = $_COOKIE["email"];
$userlog_password = base64_decode($_COOKIE["password"]);

if (!empty($userlog_email) && !empty($userlog_password)) {
  include("include/PDO.php");

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

class member{
  public $email;
  public $password;
  public $name;
  public $nickname;
  public $address;
}

$member = new member;
$member->email = $_POST['email'];
$member->password = md5($_POST['password']);
$member->name = $_POST['name'];
$member->nickname = $_POST['nickname'];
$member->address = $_POST['address'];
$member->authority = $_POST['authority'];
$file = $_FILES["file"];

switch ($file['type']) {
  case 'image/jpeg':
    $file_type = true;
    break;
  default:
    $file_type = false ;
    break;
}

if (isset($_POST['send']) && $file_type) {

  include 'include/PDO.php';

  $reslut_email = $pdo -> query("SELECT * FROM opa_member where email= '".$member->email."'");

  switch ($reslut_email->rowCount()) {
    case '0':
      $email_ck = true;
      break;
    case '1':
      $email_ck = false ;
      break;
  }

  if ($email_ck) {

  $reslut_id = $pdo -> query("SELECT MAX(id) FROM opa_member");
  $rows = $reslut_id->fetchAll();
  foreach($rows as $i){
  $id = $i['MAX(id)'] + 1;
  }

  for ($i=0; $i < 3 ; $i++) {
    $code .= rand(1,9);
  }

  $authority = ($member->authority == "on") ? 1 : 0 ;

  $statement = $pdo->prepare("INSERT INTO opa_member(id, email,  password, name, nickname, address, enabled, authority, code) VALUES (:id, :email,  :password, :name, :nickname, :address, :enabled, :authority, :code)");

  $statement->execute(array(
    "id" => $id ,
    "email" => $member->email ,
    "password" => $member->password ,
    "name" => $member->name ,
    "nickname" => $member->nickname ,
    "address" => $member->address ,
    "enabled" => 1 ,
    "authority" => $authority,
    "code" => $code
  ));

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

  imagejpeg($thumb, "thumb/".$id.".jpg");

}
header("location:admin.php");
}

  $pdo = NULL;
?>
<!DOCTYPE html>
	<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>管理頁面-新增會員</title>
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
<?PHP
  if (isset($_POST['send']) && $file_type && $email_ck) {
    echo '<div class="alert alert-success text-center" role="alert"><strong>註冊成功!!</strong></div>';
    echo '</br>';
  }
  if (isset($_POST['send']) && !$file_type) {
    echo '<div class="alert alert-danger text-center" role="alert"><strong><h2>註冊資料有誤</h2></strong>照片是必要上傳且為JPG圖片檔案</div>';
    echo '</br>';
  }
  if ( isset($_POST['send']) && $file_type && !$email_ck) {
    echo '<div class="alert alert-danger text-center" role="alert"><strong><h2>註冊資料有誤</h2></strong>該E-Mail已被註冊使用。</div>';
    echo '</br>';
  }
?>
    <div class="container">
      <form action="user_insert.php" method="post" enctype="multipart/form-data" class="form-signin" style="margin-top: -50px;">
        <h2 class="form-signin-heading text-center"><i class="fa fa-user" aria-hidden="true"></i>  新增會員</h2><br>
        <div class="form-group">
          <label for="email">電子信箱(帳號)*</label>
          <input type="email" class="form-control" name="email" placeholder="輸入e-mail" value="<?php echo $member->email ;  ?>" required>
        </div>
        <div class="form-group">
          <label for="password">密碼*</label>
          <input type="password" class="form-control" name="password" placeholder="輸入密碼" required>
        </div>
        <div class="form-group">
          <label for="name">會員名稱*</label>
          <input type="text" class="form-control" name="name" placeholder="輸入會員名稱" value="<?php echo $member->name ;  ?>" required>
        </div>
        <div class="form-group">
          <label for="nickname">暱稱*</label>
          <input type="text" class="form-control" name="nickname" placeholder="輸入暱稱" value="<?php echo $member->nickname ;  ?>" required>
        </div>
        <div class="form-group">
          <label for="address">地址*</label>
          <input type="text" class="form-control" name="address" placeholder="輸入地址" value="<?php echo $member->address ;  ?>" required>
        </div>
        <div class="form-group ">
          <div class="checkbox alert alert-danger">
            <label>
              <input type="checkbox" name="authority"> 是否登記為網站管理員(最高權限)
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="img">會員照片上傳*</label>
          <input type="file" id="file" name="file" accept=".jpg">
          <p class="help-block">*必須的且限JPG格式照片。</p>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="myBtn" name="send">送出資料</button>
        </br>
        <a class="btn btn-lg btn-warning btn-block" href="admin.php" role="button">取消返回</a>
        </br>
        </br>
      </form>
    </div>
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>