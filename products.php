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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['delete'])) {
        $productInstance->deleteProduct($_POST['id']);
    } elseif(isset($_POST['update'])) {
        $productInstance->updateProduct($_POST['id'], $_POST['naam'], $_POST['beschrijving'], $_POST['prijs'], $_POST['aantal']);
    } elseif(isset($_POST['add_product'])) {
        $naam = $_POST['naam'];
        $beschrijving = $_POST['beschrijving'];
        $prijs = floatval($_POST['prijs']);
        $aantal = intval($_POST['aantal']);
        $productInstance->addProduct($naam, $beschrijving, $prijs, $aantal);
    }
}


$products = $productInstance->getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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

        div {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px 1px rgba(0, 0, 0, 0.2);
            width: 50%;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type=text], input[type=number], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 0;
        }

        input[type=submit]:hover {
            background-color: #45a049;
        }

        p {
            color: red;
            margin-top: 10px;
        }

        p.success {
            color: green;
        }

        textarea {
            width: calc(100% - 20px);
            height: 100px;
            resize: none;
            margin: 10px;
        }

    </style>
</head>
<body>
    <div id="add-product-section" class="active-section">
        <h2>Add New Product</h2>
        <form action="" method="post">
            <label for="naam">Naam:</label>
            <input type="text" name="naam" id="naam" required>
            
            <label for="beschrijving">Beschrijving:</label>
            <textarea name="beschrijving" id="beschrijving" required></textarea>
            
            <label for="prijs">Prijs:</label>
            <input type="number" name="prijs" id="prijs" step="0.01" required>
            
            <label for="aantal">Aantal:</label>
            <input type="number" name="aantal" id="aantal" required>
            
            <input type="submit" name="add_product" value="Add Product">
        </form>
        <?php if(!empty($error)) echo "<p>$error</p>"; ?>
        <?php if(!empty($success)) echo "<p class='success'>$success</p>"; ?>
    </div>

    <div id="edit-remove-section">
        <h2>Edit/Remove Products</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Beschrijving</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <form method="post">
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><input type="text" name="naam" value="<?= $product['naam'] ?>"></td>
                    <td><textarea name="beschrijving"><?= $product['beschrijving'] ?></textarea></td>
                    <td><input type="number" name="prijs" step="0.01" value="<?= $product['prijs'] ?>"></td>
                    <td><input type="number" name="aantal" value="<?= $product['aantal'] ?>"></td>
                    <td>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="submit" name="delete" value="Delete">
                        <input type="submit" name="update" value="Update">
                    </td>
                </tr>
            </form>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
