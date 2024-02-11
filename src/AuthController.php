<?php

include './lib/Database.php';
include_once './lib/Session.php';

class AuthController
{

    private $db;
    private $validate;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = new Database();
        $this->validate = new Validator($this->db);
    }


    /**
     * login
     *
     * @param  mixed $data
     * @return array
     */
    public function login($data): array
    {

        try {
            $email = $data['email'];
            $password = $data['password'];

            if ($this->validate->isEmpty([$email, $password])) {
                $this->validate->setErrorAlert('Email or password cannot be empty !');
                return ['message' => $this->validate->getMessage()];
            }

            if (!$this->validate->isValidEmail($email)) {
                return ['message' => $this->validate->getMessage()];
            }

            if (!$this->validate->checkExistEmail($email)) {
                $this->validate->setErrorAlert('Email did not Found, use Register email or password please !');
                return ['message' => $this->validate->getMessage()];
            } else {

                $logResult = $this->checkCredentials($email, $password);

                // if (isset($logResult['error'])) {
                //     throw new Exception("Something went wrong!");
                // }

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
                    return ['message' => $this->validate->getMessage()];
                }
            }
        } catch (Throwable $e) {
            // Handle the error or exception

            return [
                'message' => $this->validate->getErrorAlert(),
            ];
        }
    }

    // Checks email and password    
    /**
     * checkCredentials
     *
     * @param  string $email
     * @param  string $password
     * @return mixed
     */
    public function checkCredentials($email, $password): mixed
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
                'error' =>
                $this->validate->getErrorAlert(),
            ];
        }
    }

    /**
     * verifyPassword
     *
     * @param  string $password
     * @param  string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash): bool
    {
        return password_verify($password, $hash);
    }
}
