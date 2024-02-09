<?php

include './lib/Database.php';
include_once './lib/Session.php';


class UserController{

  // Db Property
  private $db;

  // Db __construct Method
  public function __construct(){
    $this->db = new Database();
  }


  // Check Exist Email Address Method
  public function checkExistEmail($email){
    $sql = "SELECT email from  users WHERE email = ?";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->rowCount()> 0) {
      return true;
    }else{
      return false;
    }
  }

  


  // User Registration Method
  public function userRegistration($data){
    $username = $data['username'];
    $email = $data['email'];
    $role = $data['role'];
    $password = $data['password'];

    $checkEmail = $this->checkExistEmail($email);

    if ($username == "" || $email == "" || $password == "" || $role == "") {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Please, User Registration field must not be Empty !</div>';
        return $msg;
    }elseif (strlen($username) < 3) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Username is too short, at least 3 Characters !</div>';
        return $msg;
    }elseif(strlen($password) < 5) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Password at least 6 Characters !</div>';
        return $msg;
    }elseif(!preg_match("#[0-9]+#",$password)) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Your Password Must Contain At Least 1 Number !</div>';
        return $msg;
    }elseif(!preg_match("#[a-z]+#",$password)) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Your Password Must Contain At Least 1 Number !</div>';
        return $msg;
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Invalid email address !</div>';
        return $msg;
    }elseif ($checkEmail == TRUE) {
      $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>Error !</strong> Email already Exists, please try another Email... !</div>';
        return $msg;
    }else{

      $sql = "INSERT INTO users( username, email, password, role) VALUES(:username, :email, :password, :role)";
      $stmt = $this->db->pdo->prepare($sql);
      $stmt->bindValue(':username', $username);
      $stmt->bindValue(':email', $email);
      $stmt->bindValue(':password', $this->hashPassword($password));
      $stmt->bindValue(':role', $role);
      $result = $stmt->execute();
      if ($result) {
        $msg = '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success !</strong> Wow, you have Registered Successfully !</div>';
          return $msg;
      }else{
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Something went Wrong !</div>';
          return $msg;
      }



    }

  }


  // Select All User Method
  public function selectAllUserData(){
    $sql = "SELECT * FROM users ORDER BY id DESC";
    $stmt = $this->db->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }




    // Get Single User Information By Id Method
    public function getUserInfoById($userid){
      $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
      $stmt = $this->db->pdo->prepare($sql);
      $stmt->bindValue(':id', $userid);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);
      if ($result) {
        return $result;
      }else{
        return false;
      }
    }



  //
  //   Get Single User Information By Id Method
    public function updateUserByIdInfo($userid, $data){
      $username = $data['username'];
      $email = $data['email'];
      $role = $data['role'];



      if ($username == ""|| $email == "") {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Input Fields must not be Empty !</div>';
          return $msg;
        }elseif (strlen($username) < 3) {
          $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Username is too short, at least 3 Characters !</div>';
            return $msg;
        }elseif (filter_var($email, FILTER_VALIDATE_EMAIL === FALSE)) {
        $msg = '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error !</strong> Invalid email address !</div>';
          return $msg;
      }else{

        $sql = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
          $stmt= $this->db->pdo->prepare($sql);
          $stmt->bindValue(':username', $username);
          $stmt->bindValue(':email', $email);
          $stmt->bindValue(':role', $role);
          $stmt->bindValue(':id', $userid);
        $result =   $stmt->execute();

        if ($result) {
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-success alert-dismissible mt-3" id="flash-msg">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Success !</strong> Wow, Your Information updated Successfully !</div>');



        }else{
          echo "<script>location.href='index.php';</script>";
          Session::set('msg', '<div class="alert alert-danger alert-dismissible mt-3" id="flash-msg">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Error !</strong> Data not inserted !</div>');


        }


      }


    }



    function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
    }




}