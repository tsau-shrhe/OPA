<?PHP
$dsn = 'mysql:host="伺服器";dbname="資料表名稱";';
$pdo = new PDO($dsn, '帳號', '密碼');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->query("SET NAMES 'utf8'");
?>