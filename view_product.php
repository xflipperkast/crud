<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); 
}

require_once 'classes/database.php';
require_once 'classes/product.php';

$dbInstance = (new Database())->getConnection();
$productInstance = new Product($dbInstance);

// kijk of er een id in url zit
if (!isset($_GET['id'])) {
    exit('No product ID provided.');
}

// info van product in id
$product = $productInstance->getProductById($_GET['id']);

if (!$product) {
    exit('Product not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $product['naam'] ?></title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 24px;
        color: #333;
    }

    p {
        font-size: 16px;
        line-height: 1.5;
        color: #666;
    }

    .price {
        font-weight: bold;
        color: #ff5733;
    }

    .available {
        color: #007700;
    }
</style>

<body>
    <div class="container">
        <h1><?= $product['naam'] ?></h1>
        <p><?= $product['beschrijving'] ?></p>
        <p class="price">Prijs: <?= $product['prijs'] ?></p>
        <p class="available">Aantal beschikbaar: <?= $product['aantal'] ?></p>
    </div>
</body>
</html>
