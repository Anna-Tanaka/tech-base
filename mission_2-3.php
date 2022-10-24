<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_2-3</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="str" placeholder="コメント">
        <input type="submit" name="submit">
    </form>
    <?php
        $str = $_POST["str"];
        if (!empty($str)) {
            $fp = fopen("mission_2-3.txt","a");
            fwrite($fp, $str.PHP_EOL);
            fclose($fp);
        }
        
        if(file_exists("mission_2-3.txt")){
            $comments= file("mission_2-3.txt",FILE_IGNORE_NEW_LINES);
            foreach($comments as $comment){
                if ($comment == "できた"){
                    echo "おめでとう！<br>";
                }elseif ($comment == "失敗"){
                    echo "残念。また頑張ろう<br>";
                }else{
                    echo $comment . "を送信しました<br>";
                }
            }
        }
    ?>
</body>
</html>
