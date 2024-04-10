<link rel="stylesheet" href="./assets/styles/ProductsSection.component.css">

<section class="products-section">
  <div class="products-cards">
    <?php
    $ruta_json = APPPATH . 'Json/products.json';
    $jsonData = file_get_contents($ruta_json);
    $products = json_decode($jsonData, true);

    if (count($products["allProducts"]) != 0) {
      foreach ($products["allProducts"] as $product) {
    ?>
        <div class="card">
          <img src="<?= $product["img"] ?>">
          <div class="card-body">
            <h5 class="text-center"><?= $product["title"] ?></h5>
            <div class="text-center pb-1">
              <?php
              $stars = $product["stars"];
              $wholeStars = floor($stars);
              $halfStar = ($stars - $wholeStars) >= 0.5;

              for ($i = 0; $i < $wholeStars; $i++) echo '<i class="bi bi-star-fill"></i> ';
              if ($halfStar) echo '<i class="bi bi-star-half"></i> ';
              for ($i = 0; $i < (5 - $wholeStars - ($halfStar ? 1 : 0)); $i++) echo '<i class="bi bi-star"></i> ';
              ?>
            </div>
            <div class="prices text-center">
              <?php
              if ($product["discount"] > 0) echo '<span class="prev-price">$' . $product["price"] . '</span>'
              ?>
              <span>$ <?= number_format($product["price"] * (1 - $product["discount"] / 100), 3); ?></span>
              <?php
              if ($product["discount"] > 0) echo '<div class="text-success">' . $product["discount"] . '% de descuento</div>';
              ?>
            </div>
          </div>
        </div>
    <?php
      }
    }
    ?>
  </div>
</section>