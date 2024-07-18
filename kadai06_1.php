  <?php

function queryDatabase($db, $table, $categoryId = null, $name = null) {
    $sql = "SELECT * FROM {$table} WHERE 1=1";
    
    if ($categoryId !== null && $categoryId !== '') {
        $sql .= " AND category_id = " . intval($categoryId);
    }
    
    if ($name !== null && $name !== '') {
        $sql .= " AND name LIKE '%" . $db->real_escape_string($name) . "%'";
    }

    if (!$result = $db->query($sql)) {
        throw new Exception("SQL Query Error >> {$sql}");
    }

    $data = [];
    while ($row = $result->fetch_object()) {
        $data[] = $row;
    }

    return $data;
}

$db = new mysqli("localhost", "ndhuynh", "eccMyAdmin", "ndhuynh");

try {
    if ($db->connect_error) {
        throw new Exception("DB Connect Error");
    }

    $db->set_charset("utf8");

    $productsTable = "php1_products";
    $categoriesTable = "php1_categories";

    $categoryId = isset($_GET['category']) ? $_GET['category'] : null;
    $name = isset($_GET['name']) ? $_GET['name'] : null;
    // var_dump($categoryId);
    $products = [];
    
    $products = queryDatabase($db, $productsTable, $categoryId, $name);
    $categories = queryDatabase($db, $categoriesTable);

    // var_dump($products);
    // var_dump($categories);

    $db->close();
} catch (Exception $error) {
    $errorMessage = $error->getMessage();
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
      <title>php1 - kadai06_1</title>
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
                  <div class="flex justify-between items-end border-b-2 border-green-400 pb-3 mb-10">
                      <h3 class="text-xl">登録商品一覧</h3>
                      <a href="kadai08_1.php"
                          class="text-white text-center leading-10 bg-pink-600 px-10 hover:bg-pink-500 rounded-md">新規登録</a>
                  </div>

                  <div class="flex flex-col md:flex-row justify-between items-start">

                      <div class="w-full md:w-3/12 h-80 bg-white mb-10 md:mb-0 p-5 shadow-md">
                          <form action="kadai06_1.php" method="GET" class="h-full">

                              <div class="flex flex-col justify-between h-full">
                                  <div>
                                      <div class="border-b border-gray-300 border-dashed mb-4 pb-6">
                                          <label for="name"
                                              class="text-gray-500 text-xs uppercase tracking-wider">name</label>
                                          <input type="text" name="name" id="name"
                                              class="w-full h-10 px-3 text-sm border-2 border-gray-200 rounded-md outline-none focus:border-green-200"
                                              value="" placeholder="product name">
                                      </div>

                                      <div>
                                          <label for="category"
                                              class="text-gray-500 text-xs uppercase tracking-wider">category</label>
                                          <select name="category" id="category"
                                              class="bg-white w-full h-10 px-3 text-sm border-2 border-gray-200 rounded-md outline-none focus:border-green-200">
                                              <option value="">すべての商品</option>
                                              <?php foreach($categories as $key=> $category): ?>
                                              <option value="<?php echo $category->id; ?>"
                                                  <?php if ($categoryId == $category->id) echo 'selected'; ?>>
                                                  <?php echo $category->name; ?>
                                              </option>

                                              <?php endforeach; ?>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="flex justify-center">
                                      <button type="submit"
                                          class="w-40 h-10 text-white text-lg bg-indigo-600 hover:bg-indigo-500 rounded-md">検索</button>
                                  </div>
                              </div>

                          </form>
                      </div>


                      <div class="w-full md:w-8/12 bg-white">
                          <!-- <div class="flex justify-end mb-10">
            <a href="#" class="block w-40 h-10 text-white text-center leading-10 bg-pink-600 hover:bg-pink-500 rounded-md">新規登録</a>
          </div> -->

                          <table class="w-full table-fixed">
                              <thead>
                                  <tr
                                      class="bg-gray-100 text-gray-500 text-xs text-left uppercase tracking-wider border-b border-gray-300">
                                      <th class="w-2/12 h-6 font-medium px-6 py-3">code</th>
                                      <th class="w-6/12 h-6 font-medium px-6 py-3">name</th>
                                      <th class="w-2/12 h-6 font-medium px-6 py-3">price</th>
                                      <th class="w-2/12 h-6 text-center font-medium px-6 py-3">setting</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php foreach($products as $key=> $product): ?>
                                  <tr class="tracking-wider border-b border-gray-200 hover:bg-gray-100 ">
                                      <td class="h-10 px-6 py-5">
                                          <?php echo $product -> code ?>
                                      </td>
                                      <td class="h-10 px-6 py-5">
                                          <?php echo $product -> name ?>
                                      </td>
                                      <td class="h-10 px-6 py-5">
                                          <?php echo $product -> price ?>
                                      </td>
                                      <td class="h-10 text-center px-6 py-5">
                                          <a href="#" class="text-pink-600 hover:text-pink-400">詳細</a>
                                      </td>
                                  </tr>
                                  <?php endforeach; ?>
                              </tbody>
                          </table>
                      </div>

                      <!-- エラーメッセージ -->
                      <div>
                          <p class="text-xl">
                              <? $error ?>
                          </p>
                      </div>

                  </div>

              </div>
              <!--/.container-->
          </main>

      </div>
      <!--/.wrapper-->
  </body>

  </html>