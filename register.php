<?php
require_once 'classes/database.php';
require_once 'classes/user.php';

$dbInstance = (new Database())->getConnection();
$userInstance = new User($dbInstance);

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $voornaam = $_POST['voornaam'];
    $tussenvoegsel = $_POST['tussenvoegsel'];
    $achternaam = $_POST['achternaam'];
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $telefoon = $_POST['telefoon'];

    if ($userInstance->register($username, $password, $voornaam, $tussenvoegsel, $achternaam, $adres, $postcode, $telefoon)) {
        $success = "Registration successful!";
    } else {
        $error = "Registration failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
<body>
    <form action="" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="voornaam">Voornaam:</label>
        <input type="text" name="voornaam" required><br>
        <label for="tussenvoegsel">Tussenvoegsel:</label>
        <input type="text" name="tussenvoegsel"><br>
        <label for="achternaam">Achternaam:</label>
        <input type="text" name="achternaam" required><br>
        <label for="adres">Adres:</label>
        <input type="text" name="adres" required><br>
        <label for="postcode">Postcode:</label>
        <input type="text" name="postcode" required><br>
        <label for="telefoon">Telefoon:</label>
        <input type="text" name="telefoon" required><br>
        <input type="submit" value="Register">
        <?php if(!empty($error)) echo "<p>$error</p>"; ?>
        <?php if(!empty($success)) echo "<p>$success</p>"; ?>
    </form>
</body>
</html>
