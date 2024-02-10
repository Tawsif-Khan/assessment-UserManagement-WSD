<?php
include 'includes/header.php';
include 'includes/process.php';

$user = $userController->get($_GET['id']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="username" class="form-control" value="<?php echo $response['old_data']['username'] ?? $user->username;?>"  required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>                                    
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $response['old_data']['email'] ?? $user->email;?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>                                    
                <input type="password" id="password" name="new_password" class="form-control" >
                <input type="hidden" id="old_password" name="password" class="form-control" value="<?php echo $user->password;?>" required>
                <input type="hidden" id="userId" name="userId" class="form-control"  value="<?php echo $user->id;?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="admin" <?php echo (($response['old_data']['role'] ?? $user->role) == 'admin') ? 'selected':'';?>>Admin</option>
                    <option value="user" <?php echo (($response['old_data']['role'] ?? $user->role) == 'user') ? 'selected':'';?>>User</option>
                </select>
            </div>
            <button type="submit" name="updateUser" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
