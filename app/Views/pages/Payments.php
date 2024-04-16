<?= $this->extend('Layout') ?>

<?= $this->section('css') ?>
<link rel='stylesheet' href='/assets/styles/Payment.component.css'>
<?= $this->endSection() ?>

<?= $this->section('title') ?>Metodos de Pago<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main>
  <?php include('components/Payment.php') ?>
</main>
<?= $this->endSection() ?>