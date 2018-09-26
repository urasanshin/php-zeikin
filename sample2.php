<!--完成品-->

<?php


// mysql:host=ホスト名;dbname=データベース名;charset=文字エンコード
$dsn = 'mysql:host=localhost;dbname=php_zeikin;charset=utf8';

// データベースのユーザー名
$user = 'root';

// データベースのパスワード
$password = 'orenomysql';

// tryにPDOの処理を記述
try {

// PDOインスタンスを生成
    $pdo = new PDO($dsn, $user, $password);
    $dbh = new PDO($dsn, $user, $password);
    //$kaishi = $_GET['kaishi'];
    //$zeizei = $_GET['zeizei'];
//    $kaishi = "2010-20-20";
//    $zeizei = "35%";

    $stmt = $pdo -> prepare("INSERT INTO shouhi2 (id, 開始日付, 税率, 削除) VALUES (:id, :kaishi, :zeizei, '削除')");
    $stmt->bindParam(':kaishi', $kaishi, PDO::PARAM_STR);
    $stmt->bindParam(':zeizei', $zeizei, PDO::PARAM_INT);
    $stmt->bindValue(':id',  $id, PDO::PARAM_INT);
    $id = NULL;
    $kaishi = $_POST['kaishi'];
    $zeizei = $_POST['zeizei'];
    $stmt->execute();
    $pdoo = new PDO($dsn, $user, $password);

// PDOインスタンスを生成
    $stmt2 = $pdoo->prepare("DELETE FROM shouhi2 WHERE id = :delete_id");
    $stmt2->bindParam(':delete_id', $id, PDO::PARAM_INT);
    $id = $_POST['idid'];
    $stmt2->execute();
//    $days = '2010-3-3';
//    $zeiritsu = '7%';
//    $sql = "INSERT INTO shouhi (開始日付, 税率) VALUES ($days, $zeiritsu)";

// SQLステートメントを実行し、結果を変数に格納

// エラー（例外）が発生した時の処理を記述
} catch (PDOException $e) {

// エラーメッセージを表示させる
    echo 'データベースにアクセスできません！' . $e->getMessage();

// 強制終了
    exit;

}
$sql = "SELECT * FROM shouhi2";
if (isset($dbh))
{$stmt = $dbh->query($sql);}

?>
<br><div align=center>消費税設定の一覧</div><br>
<table border='1' align="center">
    <tr><th>開始日付</th><th>税率</th><th>操作</th></tr>

    <?php
    // foreach文で配列の中身を一行ずつ出力
    foreach($stmt as $row){
        ?>

        <tr>
            <td><?php echo $row['開始日付']; ?></td>
            <td><?php echo $row['税率']; ?>%</td>
            <form action="sample2.php" method="post">
                <td><input type="submit" value="<?php echo $row['削除']; ?>">
                    <input type="hidden" value="<?=$row['id']?>" name="idid">
            </form></td>
        </tr>
        <?php
    }
    ?></table>


<form action="sample2.php" method="post" style="text-align: center">
    <input type="date" name="kaishi" maxlength="30" value="">
    <input type="text" name="zeizei" maxlength="30" value=""><input type="submit" value="登録"></form>


<?php
if (isset($_POST['money']) && $_POST['money'] === ''); {
    $zei= $_POST['money'];
    $zeikomi3 = $zei * 1.03;
    $zeikomi5 = $zei * 1.05;
    $zeikomi8 = $zei * 1.08;
    $total3 = (int)$zeikomi3;
    $total5 = (int)$zeikomi5;
    $total8 = (int)$zeikomi8;
    $errar = "";
}



//$today = date("Y/m/d");
$target_day = $_POST['days'];
if(strtotime($target_day) < strtotime("1989-04-01") and strtotime($target_day) >strtotime("0001-01-01") ){
    $kekka = "3%";
}else if(strtotime($target_day) > strtotime("1989-03-31") and strtotime($target_day) < strtotime("2014-04-01")){
    $kekka = "5%";
}else if(strtotime($target_day)> strtotime("2014-04-02")){
    $kekka = "8%";
}
else if (strtotime($target_day)<'0000-00-00'){
    $kekka = "";
}
//?>
<!---->
<form action="sample2.php" method="post" style="text-align: center">
     <div align="center">消費税計算</div>
    <input type="date" name="days" maxlength="30" value="">
    <input type="text" name="money" maxlength="30" value="">
    <input type="submit" value="計算">
    <br>
    <form action="sample2.php" method="get">
        <input type="text" name="kekka" style="text-align: right;" value=" <?php
        if($kekka === "3%"){

            echo $total3;
        }
        else if($kekka === "5%"){
            echo $total5;
        }
        else if($kekka === "8%"){
            echo $total8;
        }
        else if($kekka === ""){
            echo $errar;
        }
        ?>">円（税込）
    </form>
