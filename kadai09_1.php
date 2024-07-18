<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/utility.php";

$message = "";

$productCode = filter_input(INPUT_GET, "product_code");
var_dump($productCode);
try {
  $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  // DBへの接続をチェック
  if ($db->connect_error) {
    throw new Exception("DB Connect Error");
  }

  // DBとのデータの送受信で使用する文字のエンコードの指定
  $db->set_charset("utf8");

  $table = TB_PRODUCT;
  $sql = "SELECT * FROM {$table} WHERE code = ?";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("s", $productCode);
  $stmt->execute();

  $result = $stmt->get_result();

  $product = $result->fetch_object();
  var_dump($product);

  $stmt->close();

  $table = TB_CATEGORY;
  $sql = "SELECT * FROM {$table}";
  $stmt = $db->prepare($sql);
  $stmt->execute();

  $result = $stmt->get_result();

  $categories = [];
  while ($row = $result->fetch_object()) {
    $categories[] = $row;
  }

  $db->close();
} catch (Exception $error) {
  $message = $error->getMessage();
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
    <title>php1 - kadai09_1</title>
</head>

<body class="bg-slate-50">
    <div class="wrapper box-border">

        <header class="bg-teal-500">
            <div class="container mx-auto px-2 py-5">
                <h1 class="text-l text-white mb-6"><a href="#">サーバーサイドスクリプト演習１</a></h1>
                <h2 class="text-3xl text-white">データベース処理</h2>
            </div>
            <!--/.container-->
        </header>

        <main>
            <div class="container w-full h-full mx-auto px-2 py-20">

                <div class="mb-10">
                    <h3 class="text-xl border-b-2 border-green-400 pb-2 mb-5">登録商品の編集</h3>

                    <!-- エラーメッセージ -->
                    <p class="text-red-600"><?= $message ?></p>

                </div>

                <div class="product-wrap px-5 py-10 shadow-md">
                    <h4 class="font-bold mb-5">商品情報</h4>

                    <form action="kadai09_2.php" method="POST">
                        <input type="hidden" name="product_code" value="<?= $product->code ?>">
                        <input type="hidden" name="_method" value="PUT">

                        <div class="flex flex-col md:flex-row mb-10">
                            <div class="flex-grow mr-0 md:mr-10 mb-5 md:mb-0">
                                <div class="mb-5">
                                    <div class="flex flex-col w-6/12">
                                        <label for="product_code"
                                            class="text-gray-500 text-left uppercase tracking-wider">code</label>
                                        <p class="bg-white px-2 py-2 border rounded-md outline-none">
                                            <?= $product->code ?></p>
                                    </div>
                                </div>

                                <div class="flex justify-between mb-5">
                                    <div class="flex flex-col flex-grow mr-10">
                                        <label for="category"
                                            class="text-gray-500 text-left uppercase tracking-wider">category</label>
                                        <select name="category"
                                            class="bg-white px-2 py-2 border  rounded-md outline-none focus:border-green-200">
                                            <?php foreach ($categories as $category) : ?>
                                            <option value="<?= $category->id ?>"
                                                <?php if ($product->category_id == $category->id) : ?> selected
                                                <?php endif ?>>
                                                <?= $category->name ?>
                                            </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="flex flex-col w-4/12">
                                        <label for="price"
                                            class="text-gray-500 text-left uppercase tracking-wider">price</label>
                                        <input type="text" name="price" id="price"
                                            class="px-2 py-2 border rounded-md outline-none focus:border-green-200"
                                            value="<?= $product->price ?>">
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <label for="name"
                                        class="text-gray-500 text-left uppercase tracking-wider">name</label>
                                    <input type="text" name="name" id="name"
                                        class="px-2 py-2 border rounded-md outline-none focus:border-green-200"
                                        value="<?= $product->name ?>">
                                </div>
                            </div>

                            <div class="flex flex-col items-stretch flex-grow">
                                <label for="description"
                                    class="text-gray-500 text-left uppercase tracking-wider">description</label>
                                <textarea class="w-full h-full text-lg px-2 bg-gray-100 py-2 border rounded-md"
                                    disabled></textarea>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end">
                            <a href="kadai06_1.php"
                                class="text-white text-center leading-10 bg-gray-600 px-10 mr-0 sm:mr-5 hover:bg-gray-500 rounded-md">戻る</a>
                            <button type="submit"
                                class="order-first sm:order-1 text-white text-center leading-10 bg-pink-600 px-10 mb-2 sm:mb-0 hover:bg-pink-500 rounded-md">更新する</button>
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