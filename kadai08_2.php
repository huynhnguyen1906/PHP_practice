<?php

// phpだけ書くときは閉じタグ不要、ないほうがいい、最後は一行空白

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/utility.php";

// REQUEST METHOD が POST かをチェック
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("kadai08_1.php");
}

// セッションの開始
session_start();
$_SESSION["request"] = $_POST;

try {
    // postデータを取り出す、なかったらnullを返す
    $productCode = filter_input(INPUT_POST, "product_code");
    $name        = filter_input(INPUT_POST, "name");
    $categoryId  = filter_input(INPUT_POST, "category");
    $price       = filter_input(INPUT_POST, "price", FILTER_VALIDATE_INT);
    // ↑数字以外拒否するように書くこと増やす

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $db->set_charset("utf8");

    if (!($productCode && $name && $categoryId)) {
        throw new Exception("入力エラー");
    }

    // product code の重複チェック
    // SELECT文で product_code でレコードを抽出
    $table = TB_PRODUCT;
    $sql = "SELECT * FROM {$table} WHERE code = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $productCode);
    $stmt->execute();
    $result = $stmt->get_result();

    // 抽出したレコードが1件あった場合は、重複のため例外エラー
    if ($result->num_rows) {
        throw new Exception("商品コードが重複しています");
    }

    // プリペアードステートメントを閉じる
    $stmt->close();

    // トランザクションを開始
    $db->begin_transaction();

    $sql = "
        INSERT INTO {$table}(code,name,price,category_id)
        VALUES(?,?,?,?)
    ";

    // プリペアードステートメントの準備
    $stmt = $db->prepare($sql);
    // ステートメントのラベルに値を設定
    $stmt->bind_param("ssii", $productCode, $name, $price, $categoryId);

    //ステートメントを実行
    $stmt->execute();

    // トランザクションの内容をコミット
    $db->commit();

    $db->close();

    redirect("kadai07_1.php?product_code={$productCode}");
} catch (Exception $error) {
    // トランザクションの内容をロールバック
    $db->rollback();
    $_SESSION["message"] = $error->getMessage();
    redirect("kadai08_1.php");
}