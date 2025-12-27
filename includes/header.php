<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Epicenter Music</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="css/estilos.css">

  <style>
    body {
      background-color: #121212;
      color: #fff;
    }

    .sidebar {
      background: linear-gradient(180deg, #1e1e2f, #111);
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      padding: 20px;
      border-right: 1px solid #333;
      z-index: 1000;
    }

    .sidebar a {
      color: #ccc;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #292941;
      color: #fff;
    }

    .main-content {
      margin-left: 260px;
      padding: 20px;
    }

    .nav-link.active {
      background-color: #292941 !important;
    }
  </style>
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">
