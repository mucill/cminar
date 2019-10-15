<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SLIMS COMMEET 2019</title>
  <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="node_modules/toastr/build/toastr.min.css">
  <link href="https://fonts.googleapis.com/css?family=Anonymous+Pro:400,700|Barlow:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha256-UzFD2WYH2U1dQpKDjjZK72VtPeWP50NoJjd26rnAdUI=" crossorigin="anonymous" />
  <link rel="stylesheet" href="assets/style.css?<?php echo date('this') ?>">
  <?php $encrypt_key = "8eaa717fa8c6cec706e1d7baa0c46e50"; ?>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container">
        <img src="assets/logo.png" alt="">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="https://2019.slimscommeet.web.id/">Beranda <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Pendaftaran</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="payment.php">Bukti Pembayaran</a>
            </li>  
            <li class="nav-item">
              <a class="nav-link" href="ticket.php">Cetak Tiket Masuk</a>
            </li>  
          </ul>
        </div>
      </div>
    </nav>
  </header>
