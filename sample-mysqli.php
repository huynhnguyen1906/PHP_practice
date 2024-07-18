<?php
//sample-mysqli.php

    //---------------
    //1. DB接続
    //---------------
//mysqli(HOST , USER_NAME , PASSWORD , DB_NAME)の順番
$db = new mysqli("localhost" , "ndhuynh" , "eccMyAdmin" , "ndhuynh");

try {

    //DBへの接続をチェック
    if( $db -> connect_error ){
        throw new Exception( "DB Connect Error");
    }

    //DBとのデータの送受信で使用する文字エンコードの指定
    $db -> set_charset("utf8");

    //---------------
    //2. SQLの準備と実行
    //---------------
    $table = "php1_zip";
    $sql = "SELECT * FROM {$table} LIMIT 100";

    //SQLの実行
    if( ! $result = $db -> query($sql) ){
        throw new Exception( "SQL Query Error >> {$sql} " );
    }
    var_dump($result);

    //---------------
    //3. SQLの結果を整形
    //---------------
    $address =[];
    while( $row = $result -> fetch_object() ){
        $address[] = $row; 
    }

    var_dump($address);

    //---------------
    //4. SQLの接続を閉じる
    //---------------
    $db -> close();

}
catch(Exception $error){
    print $error -> getMessage();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP1 - DB</title>
</head>

<body>
    <h1>PHPでデータベースを扱う</h1>
    <table>
        <tr>
            <th>#</th>
            <th>zip</th>
            <th>pref</th>
            <th>city</th>
            <th>town</th>
        </tr>
        <?php foreach($address as $key => $row): ?>
        <tr>
            <td>
                <?php echo $key + 1; ?>
            </td>
            <td>
                <?php echo $row -> zip; ?>
            </td>
            <td>
                <?php echo $row -> pref; ?>
            </td>
            <td>
                <?php echo $row -> city; ?>
            </td>
            <td>
                <?php echo $row -> town; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>