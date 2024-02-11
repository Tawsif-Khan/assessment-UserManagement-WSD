<?php

class Validator
{

    private $message;
    private $trimmedData = [];

    /**
     * __construct
     *
     * @param  mixed $db
     * @return void
     */
    public function __construct(private $db)
    {
    }

    /**
     * isValidUser
     *
     * @param  mixed $data
     * @return bool
     */
    public function isValidUser($data): bool
    {
        foreach ($data as $key => $value) {
            $this->trimmedData[$key] = ($key === 'password' || $key === 'new_password') ? $value : trim($value);
        }
        $username = $this->trimmedData['username'];
        $email = $this->trimmedData['email'];
        $password = $this->trimmedData['password'];
        $role = $this->trimmedData['role'];

        if ($this->isEmpty([$username, $email, $password, $role])) {
            $this->setErrorAlert('Please, User Registration field must not be Empty !');
            return false;
        } elseif (
            !$this->isValieUsername($username)
            || !$this->isValidPasswrod($password)
            || !$this->isValidEmail($email)
        ) {

            return false;
        }
        return true;
    }

    /**
     * checkExistEmail
     *
     * @param  mixed $email
     * @return bool
     */
    public function checkExistEmail($email): bool
    {
        $sql = "SELECT email from  users WHERE email = ?";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $this->setErrorAlert('This email already exists. Try new email !');
            return true;
        } else {
            return false;
        }
    }

    /**
     * checkExistEmailToOthers
     *
     * @param  mixed $email
     * @param  mixed $userId
     * @return bool
     */
    public function checkExistEmailToOthers($email, $userId): bool
    {
        $sql = "SELECT email from  users WHERE email = ? AND id != ? ";
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([$email, $userId]);
        if ($stmt->rowCount() > 0) {
            $this->setErrorAlert('This email already exists. Try new email !');
            return true;
        } else {
            return false;
        }
    }

    /**
     * isEmpty
     *
     * @param  mixed $fields
     * @return bool
     */
    public function isEmpty($fields): bool
    {
        foreach ($fields as $field) {
            if ($field == "") {
                return true;
            }
        }
        return false;
    }

    /**
     * isValieUsername
     *
     * @param  mixed $username
     * @return bool
     */
    public function isValieUsername($username): bool
    {
        if (strlen($username) < 3) {
            $this->setErrorAlert('Username is too short, at least 3 Characters !');
            return false;
        }
        return true;
    }

    /**
     * isValidEmail
     *
     * @param  mixed $email
     * @return bool
     */
    public function isValidEmail($email): bool
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setErrorAlert('Invalid email address !');
            return false;
        }

        return true;
    }

    /**
     * isValidPasswrod
     *
     * @param  mixed $password
     * @return bool
     */
    public function isValidPasswrod($password): bool
    {

        if (!preg_match("#[0-9]+#", $password)) {
            $this->setErrorAlert('Your Password Must Contain At Least 1 Number !');
            return false;
        } else if (!preg_match("#[a-z]+#", $password)) {
            $this->setErrorAlert('Your Password Must Contain At Least 1 Number !');
            return false;
        } elseif (strlen($password) < 5) {
            $this->setErrorAlert('Password at least 6 Characters !</div>');
            return false;
        }

        return true;
    }

    /**
     * setErrorAlert
     *
     * @param  mixed $message
     * @return void
     */
    public function setErrorAlert($message): void
    {
        $this->message = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error !</strong>
        ' . $message . '</div>';
    }

    /**
     * getSuccessAlert
     *
     * @param  mixed $message
     * @return string
     */
    public function getSuccessAlert($message): string
    {
        return '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success !</strong> ' . $message . ' !</div>';
    }

    /**
     * getErrorAlert
     *
     * @return string
     */
    public function getErrorAlert(): string
    {
        $this->setErrorAlert('Something went wrong !');
        return $this->getMessage();
    }

    /**
     * getMessage
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }


    /**
     * getTrimmedData
     *
     * @return array
     */
    public function getTrimmedData(): array
    {
        return $this->trimmedData;
    }
}
