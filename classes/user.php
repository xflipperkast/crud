<?php

class User
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function addUser(array $data)
    {
        try {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

            $stmt = $this->db->prepare("INSERT INTO users 
                (username, password, voornaam, tussenvoegsel, achternaam, adres, postcode, telefoon) 
                VALUES 
                (:username, :password, :voornaam, :tussenvoegsel, :achternaam, :adres, :postcode, :telefoon)"
            );

            $stmt->execute([
                ':username' => $data['username'],
                ':password' => $data['password'],
                ':voornaam' => $data['voornaam'],
                ':tussenvoegsel' => $data['tussenvoegsel'],
                ':achternaam' => $data['achternaam'],
                ':adres' => $data['adres'],
                ':postcode' => $data['postcode'],
                ':telefoon' => $data['telefoon']
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Error adding user.");
        }
    }

    public function deleteUser(int $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Error deleting user.");
        }
    }

    public function updateUser(array $data)
    {
        try {
            $password = empty($data['password']) 
                        ? $this->getPassword($data['id']) 
                        : password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

            $stmt = $this->db->prepare(
                "UPDATE users SET 
                    username = :username, 
                    password = :password, 
                    voornaam = :voornaam, 
                    tussenvoegsel = :tussenvoegsel, 
                    achternaam = :achternaam, 
                    adres = :adres, 
                    postcode = :postcode, 
                    telefoon = :telefoon 
                WHERE id = :id"
            );

            $stmt->execute([
                ':username' => $data['username'],
                ':password' => $password,
                ':voornaam' => $data['voornaam'],
                ':tussenvoegsel' => $data['tussenvoegsel'],
                ':achternaam' => $data['achternaam'],
                ':adres' => $data['adres'],
                ':postcode' => $data['postcode'],
                ':telefoon' => $data['telefoon'],
                ':id' => $data['id']
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Error updating user.");
        }
    }

    private function getPassword(int $id)
    {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Error retrieving users.");
        }
    }

    public function login(string $username, string $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return true;
        } else {
            return false;
        }
    }
    public function handlePostRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $this->addUser([
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'voornaam' => $_POST['voornaam'],
                    'tussenvoegsel' => $_POST['tussenvoegsel'],
                    'achternaam' => $_POST['achternaam'],
                    'adres' => $_POST['adres'],
                    'postcode' => $_POST['postcode'],
                    'telefoon' => $_POST['telefoon']
                ]);
            } elseif (isset($_POST['delete'])) {
                $this->deleteUser($_POST['id']);
            } elseif (isset($_POST['update'])) {
                $this->updateUser([
                    'id' => $_POST['id'],
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'voornaam' => $_POST['voornaam'],
                    'tussenvoegsel' => $_POST['tussenvoegsel'],
                    'achternaam' => $_POST['achternaam'],
                    'adres' => $_POST['adres'],
                    'postcode' => $_POST['postcode'],
                    'telefoon' => $_POST['telefoon']
                ]);
            }
        }
    }

}

?>
