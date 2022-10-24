<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
    
    <?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    //DB接続
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルを作成（CREATE文）、カラムを作成（id(投稿番号),name,comment,date,password）
    $sql = "CREATE TABLE IF NOT EXISTS boards"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date char(32),"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql); //クエリメソッド。引数（$sql）に指定したSQL文をデータベースに対して実行
    
    //変数に値を代入
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $delete = $_POST["delete"];
    $edit = $_POST["edit"]; //編集フォーム
    $editNo = $_POST["editNo"]; //hiddenで隠す
    $password = $_POST["password"];
    $delpass = $_POST["delpass"];
    $edipass = $_POST["edipass"];
    
    //新規投稿機能(INSERT文。DB内のテーブルを読み込み、POSTで受け取った内容を書き込み)
    if (!empty ($name) && !empty ($comment) && empty ($editNo) && !empty($password)){
        $sql = $pdo -> prepare("INSERT INTO boards (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        //bindParam()関数は、プレースホルダーに値をバインドする
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> execute (); //execute()関数でバインドが確定するため、必ず記述
    }
    
    //投稿番号を$row['id']などとして表現するため
    $sql = 'SELECT * FROM boards';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    //削除機能（DELETE文）
    foreach($results as $row){
        if ($row['id'] == $delete && $row['password'] == $delpass){ //インデックス配列ではなく、$変数['文字列']の連想配列で呼び出す
            $sql = 'delete from boards where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    //編集フォームに表示
    foreach($results as $row){
        if (!empty($edit) && $row['id'] == $edit && $row['password'] == $edipass) { //投稿番号と編集フォームに入力した番号が同じなら
                $editnum = $row['id']; //value属性を用いて既存の投稿をフォームに表示
                $editname = $row['name']; 
                $editcomment = $row['comment'];
                $editpass = $row['password'];
        }
    }
    
    //編集実行（UPDATE文）
    if (!empty($name) && !empty($comment) && !empty($editNo)) {
        $id = $editNo; //変更する投稿番号
        $sql = 'UPDATE boards SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':password',$password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    ?>
    
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value= "<?php if(!empty($editname)) {echo $editname;} ?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($editcomment)) {echo $editcomment;} ?>">
        <input type="text" name="password" placeholder="パスワードを作成" value= "<?php if(!empty($editpass)) {echo $editpass;} ?>">
        <br>
        <input type ="hidden" name="editNo" placeholder="編集する番号表示" value="<?php if(!empty($editnum)) {echo $editnum;} ?>">
        <input type="submit" name="submit">
        <br>
        <input type="text" name="delete" placeholder="削除したい投稿番号">
        <input type="text" name="delpass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        <input type="text" name="edit" placeholder="編集したい投稿番号">
        <input type="text" name="edipass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    
    <?php
    //表示機能（SELECT文）
    $sql = 'SELECT * FROM boards';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
    ?>
    
</body>
</html>