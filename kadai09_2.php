<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/utility.php";

// request method POST チェック
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    redirect("kadai06_1.php");
}

// 疑似メソッドがあるかとPUTかをチェック
$_method = filter_input(INPUT_POST, "_method");
if (!$_method || $_method !== "PUT") {
    redirect("kadai06_1.php");
}

try {
    $productCode = filter_input(INPUT_POST, "product_code");
    $name = filter_input(INPUT_POST, "name");
    $price = filter_input(INPUT_POST, "price");
    $categoryId = filter_input(INPUT_POST, "category", FILTER_VALIDATE_INT);

    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        throw new Exception("DB connect Error");
    }
    $db->set_charset("utf8");

    // トランザクションの開始
    $db->begin_transaction();

    $table = TB_PRODUCT;
    // UPDATE SQL
    $sql = "UPDATE {$table} SET code = ?, name = ?, price = ?, category_id = ? WHERE code = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ssiis", $productCode, $name, $price, $categoryId, $productCode);
    $stmt->execute();

    $db->commit();
    redirect("kadai07_1.php?product_code={$productCode}");
} catch (Exception $error) {
    $db->rollback();
    print $error->getMessage();
}