<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アニマル掲示板</title>
</head>
<body>
    <h1>\動物としておしゃべりする掲示板/</h1>
    <p>たまには人間を辞めてみよう！</p>

    <?php
        // DB接続設定

        
        //データベースの作成
        //     $sql = "CREATE TABLE IF NOT EXISTS bullteinboard"
        // 	." ("
        // 	. "id INT AUTO_INCREMENT PRIMARY KEY,"
        // 	. "name char(32),"
        // 	. "comment TEXT,"
        // 	. "date char(32),"
        // 	. "password char(32)"
        // 	.");";
        // 	$stmt = $pdo->query($sql);

    	//  データベースのテーブル一覧を表示
        // 	$sql ='SHOW TABLES';
        // 	$result = $pdo -> query($sql);
        // 	foreach ($result as $row){
        // 		echo $row[0];
        // 		echo '<br>';
        // 	}
        // 	echo "<hr>";
        
        //データベースの構成詳細を確認する
    //     $sql ='SHOW CREATE TABLE bullteinboard';
	    // $result = $pdo -> query($sql);
	    // foreach ($result as $row){
		  //  echo $row[1];
	    // }
	    // echo "<hr>";
	    
	    
            
                
        //投稿フォームに記載する初期値を代入する
        $num_value = 0;
        $name_value = "ゴリラ";
        $comment_value = "ウホウホ";
        //$password_value = "banana23";
        
        //編集か新規投稿かを判別するフラグ
        $number = $_POST["number"];
        //削除、編集フォームで受け取ったパスワードを変数に代入
        $del_password = $_POST["d_password"];
        $edi_password = $_POST["e_password"];
        
        //削除フォームから受け取った削除するレスの番号を変数に代入
        $d_num = $_POST["d_num"];
        
        //編集フォームから受け取った編集するレスの番号を変数に代入
        $e_num = $_POST["e_num"];
        
        
        
        //投稿処理
        if(!empty($_POST["name"]) && !empty($_POST["comment"])){
            //編集モードか新規投稿モードか分岐
            if(!empty($number)){
                //編集モード
                $id = $number; //変更する投稿番号
	            $name = $_POST["name"];
            	$comment = $_POST["comment"]; 
            	$sql = 'UPDATE bullteinboard SET name=:name,comment=:comment WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
            	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
            	$stmt->execute();
            	
            } else {
                //新規投稿モード
                $sql = $pdo -> prepare("INSERT INTO bullteinboard (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
    	        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
            	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
            	//投稿フォームから受け取った名前とコメントと投稿番号、パスワードを変数に代入
            	//時刻はタイムスタンプを利用し変数に代入
            	$name = $_POST["name"];
            	$comment = $_POST["comment"]; 
            	$date = date("Y/m/d H:i:s");
            	$password = $_POST["password"];
            	$sql -> execute();    
            }
            

        }
        
        //削除処理
        if(!empty($d_num)){
            $id = $d_num;
            $sql = 'SELECT * FROM bullteinboard WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
		        //$rowの中にはテーブルのカラム名が入る
		        if(!empty($row['password']) && $del_password == $row['password']){
                    $sql = 'delete from bullteinboard where id=:id';
                	$stmt = $pdo->prepare($sql);
                	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
                	$stmt->execute();   
                } else {
                    echo "エラー！投稿にパスワードが設定されていないか、間違っています！";
                }
            }
            
        }
        
        
        
        //指定した投稿番号のレスをフォームに投稿させる処理（編集用）
        if(!empty($e_num)){
            $id = $e_num;
            $sql = 'SELECT * FROM bullteinboard WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
		        //$rowの中にはテーブルのカラム名が入る
		        if(!empty($row['password']) && $edi_password == $row['password']){
                    $num_value = $row['id'];
		            $name_value = $row['name'];
		            $comment_value = $row['comment'];   
                } else {
                    echo "エラー！投稿にパスワードが設定されていないか、間違っています！";
                }
	        }    
        }
        
        
    
    ?>
    <br>
    <strong>投稿フォーム</strong>
    <form action="" method="post">
        <input type="hidden" name="number"  value="<?php if(isset($num_value)) {echo $num_value;} ?>">
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($name_value)) {echo $name_value;} ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($comment_value)) {echo $comment_value;} ?>">
        <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($password_value)) {echo $password_value;} ?>">
        <input type="submit" name="submit">
    </form>
    
    <strong>削除フォーム</strong>
    <form action="" method="post">
        <input type="number" name="d_num" placeholder="削除する番号">
        <input type="text" name="d_password" placeholder="パスワード" value="<?php if(isset($d_password_value)) {echo $d_password_value;} ?>">
        <input type="submit" name="delete" value="削除する">
    </form>
    
    <strong>編集フォーム</strong>
    <form action="" method="post">
        <input type="number" name="e_num" placeholder="編集する投稿番号">
        <input type="text" name="e_password" placeholder="パスワード" value="<?php if(isset($e_password_value)) {echo $e_password_value;} ?>">
        <input type="submit" name="edit" value="編集する">
    </form>
    <?php
        // DB接続設定

    	
    	//ブラウザ出力処理
    	$sql = 'SELECT * FROM bullteinboard';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
		    //$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].' ';
		    echo $row['name'].' ';
		    echo $row['comment'].' ';
		    echo $row['date'].' ';
		    echo $row['password'].'<br>';
	    }
    ?>
    
    
    
</body>
</html>