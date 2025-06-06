<?php
include_once 'functions.php';

class MyUser
{
    private mysqli|null|false $mysqli;

    public function __construct() {
        $this->mysqli = dbLink();
    }

    function getPasswordFromDB($username) {
        $query = $this->mysqli->prepare("SELECT password FROM users WHERE email = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        if ($result->num_rows != 1) {
            return false;
        } else {
            return $rows[0]["password"];
        }
    }

    /**
     * @throws Exception
     */
    function verifyPassword($username, $password): bool
    {
        $pepper = getConfigVariable("PEPPER");
        $password_peppered = hash_hmac("sha256", $password, $pepper);
        $password_from_db = $this->getPasswordFromDB($username);
        if (!$password_from_db) {
            return false;
        }
        return password_verify($password_peppered, $password_from_db);
    }

    /**
     * @throws Exception
     */
    function getDetails($username): false|array
    {
        $query = $this->mysqli->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $row['role'] = getRoleNameById($row['role_id']);
            unset($row['password']);
            unset($row['role_id']);
            return $row;
        }
        return false;
    }

    function login($username, $password): bool
    {
        try {
            if ($this->verifyPassword($username, $password)) {
                session_start();
                $_SESSION['cs_project_loggedin'] = $this->getDetails($username);
                $_SESSION['controls'] = getHash($_SERVER['HTTP_USER_AGENT'] . $_SERVER ['REMOTE_ADDR']);
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    function logout(): void
    {
        session_unset();
        setcookie("PHPSESSID", "", time() - 3600, "/");
        session_destroy();
    }

    function create($first_name, $last_name, $email, $password): bool
    {
        try {
            $passwordHash = getPasswordHash($password);
        } catch (Exception $e) {
            return false;
        }
        $query = $this->mysqli->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $first_name, $last_name, $email, $passwordHash);
        return $query->execute();
    }

}