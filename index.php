<?php

date_default_timezone_set("Asia/tokyo");

$comment_array = array();//配列に保存する
$pdo = null;
$stmt = null;
$error_messages = array();

// <!-- DB接続 --> 
try{
  $pdo = new PDO('mysql:host=localhost;dbname=bbs-ytb', "hoge", "higepiyo4545");
} catch(PDOException $e) {
 echo $e ->getmessage();
}

//フォームを打ち込んだ時
if(!empty($_POST["submitbutton"])){

//名前のcheck
if(empty($_POST["username"])){
  echo "名前を入力してください";
  $error_messages["username"] =  "名前を入力してください";
}
//コメントのcheck
if(empty($_POST["commnet"])){
  echo "コメントを入力してください";
  $error_messages["comment"] = "名前を入力してください";
}


if(empty($error_messages)){
  $postdate = date("Y-m-d H:i:s");

  try{
    $stmt = $pdo->prepare("INSERT INTO `bbs-table` (`username`, `comment`, `PostDate`) VALUES ( :username, :comment,  :postdate);");
    $stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
    $stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
    $stmt->bindParam(':postdate', $postdate, PDO::PARAM_STR);

    $stmt->execute();
  }catch(PDOException $e) {
    echo $e ->getmessage();
   }
}

};


// DBからコメントデータを取得する
$sql = "SELECT * FROM `bbs-table`;";
$comment_array  = $pdo->query($sql);
//DBの接続を閉じる
$pdo = null;
?>
<!-- 接続の仕方https://blanche-toile.com/web/mysql-mariadb-create-user -->

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1 class="title">PHPで掲示板アプリ</h1>
  <hr> 
  <div class="boardWrapper">
    <section>
      <?php foreach ($comment_array as $comment):?>
      <article>
        <div class="wrapper">
          <div class="nameArea">
            <span>名前: </span>
            <p class="username"><?php echo $comment["username"];?></p>
            <time><?php echo $comment["PostDate"];?></time>
          </div>
          <p class="comment"><?php echo $comment["comment"];?></p>
        </div>
      </article>
      <?php endforeach;?>
    </section>
    <form class="formWrapper" method="POST">
      <div>
        <input type="submit" value ="書き込む" name="submitbutton">
        <label for="">名前:</label>
        <input type="text" name="username">
        <div>
           <textarea class="commentTextArea" name="comment"></textarea>
        </div>
      </div>
    </form>
  </div>
  
</body>
</html>