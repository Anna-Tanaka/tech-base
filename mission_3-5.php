<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3</title>
</head>
<body>
    
    <?php
        $filename = "mission_3-5.txt";
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $password = $_POST["password"]; //フォームに入力されたパスワード、各投稿と一緒に保存
        
        if (file_exists($filename)) {
            $number = count(file($filename))+1; 
        } else {
            $number = 1;
        }
        
        $results = $number ."<>".$name ."<>".$comment ."<>".$date ."<>".$password. "<>";
        
        $edit = $_POST["edit"]; //編集番号記入フォーム
        $editNo = $_POST["editNo"]; //hiddenで隠れる部分
        
        //新規投稿、投稿と一緒にパスワードを記入
        if (!empty($name) && !empty($comment) &&empty($editNo)){
            $fp = fopen($filename,"a");
            fwrite($fp, $results.PHP_EOL);
            fclose($fp);
        }
        
        //編集番号入力、投稿時のパスワードと一致した時のみ既存の投稿をフォームに表示
        $edipass = $_POST["edipass"];
        if (!empty($edit)) { 
            $e_lines = file($filename);
            for ($i = 0; $i < count($e_lines); $i++){
                $ediCon = explode("<>", $e_lines[$i]);
                    if ($ediCon[0] == $edit && $ediCon[4] == $edipass) {  //投稿番号と編集番号が一致、かつパスワードが一致したら名前とコメント、パスワード取得
                        $editnum = $ediCon[0];
                        $editname = $ediCon[1]; 
                        $editcomment = $ediCon[2]; 
                        $editPass = $ediCon[4]; //value属性を用いてこれを投稿フォームに表示
                    }
            }
        }
        
        //編集実行
        if (!empty($name) && !empty($comment) && !empty($editNo)){
            $e_array = file($filename);
            $fp = fopen( $filename, "w"); 
            for ($i = 0; $i < count($e_array); $i++){
                $ediData = explode("<>", $e_array[$i]);
                    if ($ediData[0] == $editNo ) { //投稿番号と編集番号が一致したら             
                        fwrite($fp, $editNo. "<>". $name. "<>". $comment. "<>". $date. "<>".$password.PHP_EOL); //ファイルに書き込む内容を差し替える
                    } else { //一致しなければそのまま書き込む
                        fwrite($fp, $e_array[$i].PHP_EOL);
                    }
            }
            fclose($fp);
        }
        
         //削除、投稿時のパスワードと一致したときのみ
        $delete = $_POST["delete"];
        $delpass = $_POST["delpass"];
        if (!empty($delete)){
            $d_lines = file($filename,FILE_IGNORE_NEW_LINES); 
            $fp = fopen( $filename, "w"); 
            for ($i = 0; $i < count($d_lines); $i++){ 
                $delCon = explode("<>",$d_lines[$i]); 
                    if ($delCon[0] == $delete && $delCon[4] == $delpass) { //投稿番号と$deleteが一致、かつパスワードが一致すれば削除
                        echo "コメントを削除しました";
                    }else { //投稿番号と$deleteが一致しないorパスワードが一致しない時
                        fwrite($fp, $d_lines[$i].PHP_EOL);
                    }
            }
            fclose($fp);
        }
   
    ?>
        
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value= "<?php if(!empty($editname)) {echo $editname;} ?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($editcomment)) {echo $editcomment;} ?>">
        <br>
        <input type="hidden" name="editNo" value="<?php if(!empty($editnum)) {echo $editnum;} ?>">
        <input type="text" name="password" value="<?php if(!empty($editPass)) {echo $editPass;} ?>" placeholder="パスワードを設定">
        <input type="submit" name="submit">
        <br>
        <br>
        <input type="text" name="delete" placeholder="投稿番号">
        <input type="text" name="delpass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        <input type="text" name="edit" placeholder="投稿番号">
        <input type="text" name="edipass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    
    <?php
        //表示
        if (file_exists($filename)) {
            $lines = (file($filename,FILE_SKIP_EMPTY_LINES));
            foreach($lines as $line){
                $content = explode("<>",$line);
                if(count($content) > 2){
                    echo $content[0];
                    echo $content[1];
                    echo $content[2];
                    echo $content[3] . "<br>";
                }
            }
        }
    ?>
</body>
</html>
