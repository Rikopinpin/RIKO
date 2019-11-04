<html>
<head>
<meta charset="utf-8">
<title>mission_5-2</title>
</head>
<body>


<form method="POST" action="">

<h1>入力フォーム</h1>
<?php
//データベースへの接続
$dsn='mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql="CREATE TABLE IF NOT EXISTS riko"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."time char(50),"
."password char(20)"
.");";
$stmt=$pdo->query($sql);


$editname='';
    $editcomment='';
    $editnum='';
if(empty($_POST["editnumber"])){
  echo"名前:<input type='text' name='name' >
  コメント:<input type='text' name='comment' >
  password:<input type='text' name='pass'>
  <input type='submit' value='送信'><br>";
}
else{
        $pass=$_POST["editpass"];
        $id=$_POST["editnumber"];
        $editnum=$_POST["editnumber"];
        $sql = 'SELECT * FROM riko where id='.$id;
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        
            $editname=$row['name'];
            $editcomment=$row['comment'];

    echo"名前:<br><input type='text' name='name' value=$editname><br>";
    echo"コメント:<br><input type='text' name='comment' value=$editcomment><br><br>";
    echo"<input type='hidden' name='hiddennum' value=$editnum><br>";
    echo"パスワード設定:(編集実行中にここは入力しないでください。)<br>";
    echo"<input type='text' name='password'><br>";
    echo"<input type='submit' name='送信'><br>";
  }
 }
 


?>
</form>
<form method="POST" action="">
<h1>削除フォーム</h1>
<input type="text" name="delete"><br>
パスワード:<br>
<input type="text" name="deletepass"><br>
<input type="submit" value="削除">
</form>

<form method="POST" action="">
<h1>編集フォーム</h1>

<input type="text" name="editnumber"><br>
パスワード:<br>
<input type="text" name="editpass"><br>
<input type="submit" value="編集">
</form>


<?php
//編集
 if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["hiddennum"])){
        $id = $_POST["hiddennum"];;
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $sql = 'update riko set name=:name,comment=:comment where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();        
    }


//削除機能
 if(!empty($_POST["delete"])&&!empty($_POST["deletepass"])){
$pass=$_POST["deletepass"];
 $id=$_POST["delete"];
 $sql='delete from riko where id=:id';
 $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
    }
 

//新規投稿
    elseif(isset($_POST["comment"],$_POST["name"],$_POST["pass"])){
 $sql=$pdo->prepare("INSERT INTO riko (name, comment,pass,nitiji) VALUES (:name, :comment, :pass, cast(now() as datetime))");
 $sql->bindParam(':name',$name,PDO::PARAM_STR);
 $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
 $sql->bindParam(':pass',$pass,PDO::PARAM_STR);
 $name=$_POST["name"];
 $comment=$_POST["comment"];
 $pass=$_POST["pass"];
 $sql->execute();
}
    
$sql = 'SELECT * FROM riko';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['nitiji'].'<br>';
echo "<hr>";
};

        

?>
</body>
</html>