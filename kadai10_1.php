<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/utility.php";

//POST METHOD かチェック
if( $_SERVER[ "REQUEST_METHOD" ] !== "POST" ){
    redirect( "kadai06_1.php" );
}

//類似メソッドがある かと DELETE METHOD かチェック
$_method = filter_input(INPUT_POST , "_method");
if( ! $_method || $_method !== "DELETE" ){
    redirect( "kadai06_1.php" );
}

var_dump($_POST);

try{
    $productCode = filter_input(INPUT_POST , "product_code");

    $db = new mysqli( DB_HOST , DB_USER , DB_PASS , DB_NAME );
    if( $db -> connect_error ){
        $db -> set_charset("utf8");
    }

    $table = TB_PRODUCT;
    $sql = "DELETE FROM {$table} WHERE code = ? ";
    $stmt = $db -> prepare( $sql );
    $stmt -> bind_param( "s" , $productCode );
    $stmt -> execute();
    $db -> close();
    redirect( "kadai06_1.php" );

}catch( Exception $error ){
    print $error -> getMessage();
}