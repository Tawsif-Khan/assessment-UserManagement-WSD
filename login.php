<?php
$filepath = realpath(dirname(__FILE__));
include_once $filepath . "/lib/Session.php";
Session::init();
spl_autoload_register(function ($classes) {

    include 'src/' . $classes . ".php";
});


$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {

    $response = $auth->login($_POST);
    if (isset($response['message'])) {
        echo $response['message'];
    }
}

$logout = Session::get('logout');
if (isset($logout)) {
    echo $logout;
}
Session::checkLogin();

if (Session::get('migration') === true) {
    Session::set('migration', false);
    echo $auth->getValidator()->getSuccessAlert('Database Migration is successfull and an Admin is created');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="email" class="form-control" value="admin@admin.com" placeholder="Enter your username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" value="password" placeholder="Enter your password">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>