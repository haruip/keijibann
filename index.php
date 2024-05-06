<?php
 
 $comment_array = array();
date_default_timezone_set("Asia/Tokyo");




//DB接続
try {
     $pdo = new PDO('mysql:host=localhost;dbname=bbs-yoroigou', "root", "");
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (!empty($_POST["submitButton"])) {
    $postDate = date("Y-m-d H:i:s");
    if (empty($_POST["username"])) {
        echo "名前を入力してください";
    }
    if (empty($_POST["comment"])) {
        echo "コメントを入力してください";
    }
try {
    $stmt = $pdo->prepare("INSERT INTO `bbstable` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate);");
    $stmt->bindParam(':username', $_POST["username"], PDO::PARAM_STR);
    $stmt->bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
    $stmt->bindParam(':postDate', $postDate, PDO::PARAM_STR); 
    $stmt->execute();   
} catch(PDOException $e) {
  echo $e->getMessage();
}

   }

//DBから取得
$sql = "SELECT `id`, `username`, `comment`, `postDate` FROM bbstable;";
$comment_array = $pdo->query($sql);

//DBの接続を閉じる
$pdo = null;

?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="title">phpで掲示板作ってみたお</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach($comment_array as $comment) : ?>
         <article>
            <div class="wrapper">
                <div class="nameArea">
                    <span>名前:</span>
                    <p class="username"><?php echo $comment["username"]; ?></p>
                    <time>:<?php echo $comment["postDate"]; ?></time>
                </div>
                <p class="comment"><?php echo $comment["comment"]; ?></p>
            </div>
         </article>
         <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label for="">名前:</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
</div>
</body>
</html>
