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

// pak waar producten zijn hoger dan 0
$products = array_filter($productInstance->getAllProducts(), function($product) {
    return $product['aantal'] > 0;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        width: 80%;
    }

    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
        width: 30%;
        margin: 10px 0;
    }

    .product {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 15px 1px rgba(0, 0, 0, 0.2);
        text-align: center;
        transition: all 0.3s;
    }

    .product h3 {
        margin: 10px 0;
    }

    .product:hover {
        box-shadow: 0px 0px 20px 1px rgba(0, 0, 0, 0.3);
    }
    </style>
</head>
<body>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <a href="view_product.php?id=<?= $product['id'] ?>" class="product-link">
                <div class="product">
                    <h3><?= $product['naam'] ?></h3>
                    <p><?= $product['beschrijving'] ?></p>
                    <p>Prijs: $<?= number_format($product['prijs'], 2) ?></p>
                    <p>Aantal: <?= $product['aantal'] ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</body>
</html>
