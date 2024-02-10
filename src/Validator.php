<?php

class Validator
{

    private $message;
    private $trimmedData = [];

    public function __construct(private $db)
    {
    }

    public function isValidUser($data)
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
        } elseif (!$this->isValieUsername($username)
            || !$this->isValidPasswrod($password)
            || !$this->isValidEmail($email)
        ) {

            return false;
        }
        return true;
    }

    public function checkExistEmail($email)
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

    public function checkExistEmailToOthers($email, $userId)
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

    public function isEmpty($fields)
    {
        foreach ($fields as $field) {
            if ($field == "") {
                return true;
            }
        }
        return false;
    }

    public function isValieUsername($username)
    {
        if (strlen($username) < 3) {
            $this->setErrorAlert('Username is too short, at least 3 Characters !');
            return false;
        }
        return true;
    }

    public function isValidEmail($email)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setErrorAlert('Invalid email address !');
            return false;
        }

        return true;
    }

    public function isValidPasswrod($password)
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

    public function setErrorAlert($message)
    {
        $this->message = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error !</strong>
        ' . $message . '</div>';
    }

    public function getSuccessAlert($message)
    {
        return '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success !</strong> ' . $message . ' !</div>';
    }

    public function getErrorAlert()
    {
        $this->setErrorAlert('Something went wrong !');
        return $this->getMessage();
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTrimmedData()
    {
        return $this->trimmedData;
    }

}
