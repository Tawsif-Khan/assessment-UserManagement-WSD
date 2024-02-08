
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-item-right ">
        <button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#createUserModal">
            Create User
        </button>

        </div>
        <div>
        <h3>User List</h3>

        </div>
        <div>
            <input type="text" placeholder="Search by name or email" id="keyword"/>
            <button class="btn btn-primary" onclick="search()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
               
                <!-- Add more rows for other users -->
            </tbody>
        </table>
    </div>
    <?php include('create_user.php');?>
    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
