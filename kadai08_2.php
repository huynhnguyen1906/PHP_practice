<?php 

require_once "config.php";
require_once "utility.php";


//Request Method が POST かをチェック
if( $_SERVER[ "REQUEST_METHOD" ] !== "POST" ){
    redirect( "kadai08_1.php" );
}

//セッションの開始の有効化
session_start();
$_SESSION ["request"] = $_POST;

try{

    //POSTデータを取り出す
    $productCode = filter_input( INPUT_POST , "product_code" );
    $name        = filter_input( INPUT_POST , "name" );
    $categoryId  = filter_input( INPUT_POST , "categoryId" );
    $price       = filter_input( INPUT_POST ,  "price" , FILTER_VALIDATE_INT );

    //db
    $db = new mysqli( DB_HOST , DB_USER , DB_PASS,DB_NAME );

    $db -> set_charset( "utf8" );

    if( ! ($productCode && $name && $categoryId)){
        throw new Exception( "入力エラー" );
    }

    //product code の重複チェック
    //SELECT文でproduct_codeでレコードを抽出
    $table = TB_PRODUCT;
    $sql = "SELECT * FROM {$table} WHERE code = ? ";
    $stmt = $db -> prepare($sql);
    $stmt -> bind_param("s" , $product_code);
    $stmt -> execute();
    $result = $stmt -> get_result();

    //抽出したレコードが1件あった場合は、重複のため例外エラー
    if( $result -> num_rows ){
        throw new Exception( "商品コードが重複しています" );
    }

    //プリペアードステートメントを閉じる
    $stmt -> close();

    //トランザクションの開始
    $db -> begin_transaction();

    $table = TB_PRODUCT;
    $sql = "
    INSERT INTO {$table}( code , name , price , categoryId )
    VALUES ( ? , ? , ? , ? )
    ";

    //プリペアードステートメントの準備
    $stmt = $db -> prepare($sql);
    //ステートメントのラベルに値をバインド
    $stmt -> bind_param( "ssii" , $productCode , $name , $price , $categoryId );

    //ステートメントの実行
    $stmt -> execute();

    //トランザクションの内容をコミット
    $db -> commit();

    $db -> close();

    redirect( "kadai07_1.php?product_code={$productCode}" );
}
catch( Exception $error ){
    //トランザクションの内容をロールバック
    $db -> rollback();
    $_SESSION[ "message" ] = $error -> getMessage();
    redirect( "kadai08_1.php" );

}