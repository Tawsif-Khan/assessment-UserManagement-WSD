<?php

include './lib/Database.php';
include_once './lib/Session.php';

class AuthController
{

    private $db;
    private $validate;

    public function __construct()
    {
        $this->db = new Database();
        $this->validate = new Validator($this->db);
    }

    public function login($data)
    {

        try {
            $email = $data['email'];
            $password = $data['password'];

            if ($this->validate->isEmpty([$email, $password])) {
                $this->validate->setErrorAlert('Email or password cannot be empty !');
                return $this->validate->getMessage();
            }

            if (!$this->validate->isValidEmail($email)) {
                return $this->validate->getMessage();
            }

            if (!$this->validate->checkExistEmail($email)) {
                $this->validate->setErrorAlert('Email did not Found, use Register email or password please !');
                return $this->validate->getMessage();
            } else {

                $logResult = $this->checkCredentials($email, $password);

                if (isset($logResult['error'])) {
                    throw new Exception("Something went wrong!");
                }

                if ($logResult) {

                    Session::init();
                    Session::set('login', true);
                    Session::set('id', $logResult->id);
                    Session::set('role', $logResult->role);
                    Session::set('email', $logResult->email);
                    Session::set('username', $logResult->username);
                    Session::set('logMsg', $this->validate->getSuccessAlert('You are Logged In Successfully !'));
                    echo "<script>location.href='index.php';</script>";

                } else {
                    $this->validate->setErrorAlert('Email or Password did not Matched !');
                    return $this->validate->getMessage();
                }

            }
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'message' => $e->getMessage(),
            ];
        }

    }

    public function checkCredentials($email, $password)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            return $this->verifyPassword($password, $user->password) ? $user : null;
        } catch (Throwable $e) {
            // Handle the error or exception
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

}
