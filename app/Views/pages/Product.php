<?php extract($sneaker) ?>

<?= $this->extend('Layout') ?>

<?= $this->section('css') ?>
<link rel='stylesheet' href='<?= base_url() ?>assets/styles/Product.component.css'>
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= $brand . " " . $model ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class='product-section'>
  <?php if (session()->has('msg')) : ?>
    <div class="notification <?= session('msg.type') ?>">
      <div class="notification__body"><?= session('msg.body') ?></div>
    </div>
  <?php endif ?>
  <div class='img-product'>
    <img src='<?= base_url() ?>assets/img/sneakers/<?= $img ?>' alt='<?= $brand . " " . $model ?>'>
  </div>
  <form class="product-body" action="<?= base_url("add_cart") ?>" method="post" aria-autocomplete="none">
    <input type="hidden" name="id_sneaker" value="<?= $id_sneaker ?>">
    <?php $discount2 = number_format($discount, 0); ?>
    <?php if ($discount > 0) echo "<div class='text-success fw-bold'>$discount2% de descuento</div>" ?>
    <h1 class="fw-bold"><?= $brand . " " . $model ?></h1>
    <div class='prices fw-bold'>
      <?php if ($discount > 0) : ?>
        <span class='prev-price'>$ <?= number_format($price, 0, ',', '.'); ?></span>
      <?php endif ?>
      <span class='price'>$ <?= number_format($price * (1 - $discount / 100), 0, ',', '.'); ?></span>
    </div>
    <p><?= $description ?></p>
    <?php if (count($stock) > 0) : ?>
      <div class='sizes'>
        <label>
          <select class="form-select mb-2" name="size">
            <?php foreach ($stock as $s) : ?>
              <?php extract($s) ?>
              <?php if ($quantity > 0) : ?>
                <option value="<?= $size ?>"><?= $size ?></option>
              <?php endif ?>
            <?php endforeach ?>
          </select>
        </label>
      </div>
      <button class='btn-product px-4 py-2'><i class='bi bi-cart4'></i>Agregar al carrito</button>
    <?php else : ?>
      <div class="size text-center">
        <strong>No hay talles disponibles</strong>
      </div>
    <?php endif ?>
    <a href='<?= base_url('commercialization/#payments-methods') ?>' class='text-black py-4 text-center'>Ver metodos de pago</a>
  </form>
</section>
<?= $this->endSection() ?>