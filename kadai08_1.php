<?php

require_once "config.php";
require_once "utility.php";


//---------------
//1. DB接続
//---------------
//mysqli(HOST , USER_NAME , PASSWORD , DB_NAME)の順番
$db = new mysqli("localhost" , "ndhuynh" , "eccMyAdmin" , "ndhuynh");

//セッションの開始の有効化
session_start();
var_dump($_SESSION);


try {
  //DBへの接続チェック
  if($db -> connect_error){
    throw new Exception( "DB Connect Error" );
  } 

  //DBとのデータの送受信で使用する文字エンコードの指定
  $db -> set_charset("utf8");

  //---------------
  //2. SQLの準備
  //---------------
 // 2. SQLの準備
  var_dump($_GET);
 // emptyは空っぽかどうか 空っぽでtrue
 // if (!empty($_GET["category"])) {
 //   $categoryId = $_GET["category"];
 // } else {
 //   $categoryId = 0;
 // }
  $categoryId = filter_input(INPUT_GET, "category");

  $where = "";
  if ($categoryId) {
    $where = "WHERE category_id = {$categoryId}";
  }
 $sql = "SELECT * FROM php1_products {$where}";

 // SQLの実行
  if (!$result = $db->query($sql)) {
    throw new Exception("SQL Query Error >> {$sql}");
  }

  while ($row = $result->fetch_object()) {
    $products[] = $row;
  }
 // var_dump($products);

 // すべてのカテゴリーレコードを取得するSQL
 $sql = "SELECT * FROM php1_categories";
 // SQLの実行
  $result = $db->query($sql);
  while ($row = $result->fetch_object()) {
    $categories[] = $row;
  }
 // var_dump($categories);

  $db->close();
} 
catch (Exception $error) {
  print $error->getMessage();
  }




?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- link -->
    <link href="asset/styles/style.css" rel="stylesheet">

    <!-- script -->
    <script src="https://cdn.tailwindcss.com"></script>
    <title>php1 - kadai08_1</title>
</head>

<body class="bg-slate-50">
    <div class="wrapper box-border">

        <header class="bg-teal-500">
            <div class="container mx-auto px-2 py-5">
                <h1 class="text-l text-white mb-6"><a href="">サーバーサイドスクリプト演習１</a></h1>
                <h2 class="text-3xl text-white">データベース処理</h2>
            </div>
            <!--/.container-->
        </header>

        <main>
            <div class="container w-full h-full mx-auto px-2 py-20">

                <div class="mb-10">
                    <h3 class="text-xl border-b-2 border-teal-400 pb-2 mb-5">商品の新規登録</h3>

                    <!-- エラーメッセージ -->
                    <p class="text-red-600"><?= old( "message" ) ?></p>

                </div>

                <div class="product-wrap px-5 py-10 shadow-md">
                    <h4 class="font-bold mb-5">商品情報</h4>

                    <form action="kadai08_2.php" method="POST">

                        <div class="flex flex-col md:flex-row mb-10">
                            <div class="flex-grow mr-0 md:mr-10 mb-5 md:mb-0">
                                <div class="mb-5">
                                    <div class="flex flex-col w-6/12">
                                        <label for="product_code"
                                            class="text-gray-500 text-left uppercase tracking-wider">code</label>
                                        <input type="text" name="product_code" id="product_code"
                                            class="px-2 py-2 border rounded-md outline-none focus:border-green-200"
                                            value="<?= $_SESSION[ "request" ][ "product_code" ] ?>">
                                    </div>
                                </div>

                                <div class="flex justify-between mb-5">
                                    <div class="flex flex-col flex-grow mr-10">
                                        <label for="category"
                                            class="text-gray-500 text-left uppercase tracking-wider">category</label>
                                        <select name="" id="category"
                                            class="bg-white px-2 py-2 border  rounded-md outline-none focus:border-green-200">
                                            <option value="商品カテゴリーのID">商品カテゴリーを選択してください</option>

                                            <?php foreach($categories as $category): ?>
                                            <option value="<?= $category->id ?>"
                                                <?php if ($_SESSION["request"]["category"] == $category->id) : ?>
                                                selected <?php endif ?>>
                                                <?= $category -> name ?>
                                            </option>
                                            <?php endforeach ?>

                                            <!-- <option value="商品カテゴリーのID">商品カテゴリーの名前</option> -->

                                        </select>
                                    </div>
                                    <div class="flex flex-col w-4/12">
                                        <label for="price"
                                            class="text-gray-500 text-left uppercase tracking-wider">price</label>
                                        <input type="text" name="price" id="price"
                                            class="px-2 py-2 border rounded-md outline-none focus:border-green-200"
                                            value="<?= $_SESSION[ "request" ][ "price" ] ?>">
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <label for="name"
                                        class="text-gray-500 text-left uppercase tracking-wider">name</label>
                                    <input type="text" name="name" id="name"
                                        class="px-2 py-2 border rounded-md outline-none focus:border-green-200"
                                        value="<?= $_SESSION[ "request" ][ "name" ] ?>">
                                </div>
                            </div>

                            <div class="flex flex-col items-stretch flex-grow">
                                <label for="description"
                                    class="text-gray-500 text-left uppercase tracking-wider">description</label>
                                <textarea name="description" id="description"
                                    class="w-full h-full text-lg px-2 bg-gray-100 py-2 border rounded-md"
                                    disabled></textarea>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end">
                            <a href="kadai06_1.php"
                                class="text-white text-center leading-10 bg-gray-600 px-10 mr-0 sm:mr-5 hover:bg-gray-500 rounded-md">一覧へ戻る</a>
                            <button type="submit"
                                class="order-first sm:order-1 text-white text-center leading-10 bg-pink-600 px-10 mb-2 sm:mb-0 hover:bg-pink-500 rounded-md">登録する</button>
                        </div>
                    </form>

                </div>
                <!--/.product-wrap-->

            </div>
            <!--/.container-->
        </main>

    </div>
    <!--/.wrapper-->
</body>

</html>