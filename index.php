<?php
include 'includes/header.php';
include 'includes/process.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container mt-5">
        <nav class="navbar navbar-expand-md card-header">
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=logout"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <?php
        if (Session::get('role') === 'admin') { ?>
            <button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#createUserModal">
                Create User
            </button>
        <? } ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h3>User List</h3>

                </div>
                <form method="get" action="">
                    <input type="text" placeholder="Search by name or email" id="keyword" name="keyword" value="<?php echo $keyword ?? ''; ?>" />
                    <button class="btn btn-primary" type="submit" name="search">Search</button>
                </form>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <?php
                            if (Session::get('role') === 'admin') { ?><th>Action</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($users['data'] as $user) {
                        ?>
                            <tr>
                                <td><?php echo $user->username; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo $user->role; ?></td>
                                <?php
                                if (Session::get('role') === 'admin') { ?>
                                    <td><a href="edit_user.php?id=<?php echo $user->id; ?>">
                                            <button class="btn btn-info">edit</button></a>
                                        <button class="btn btn-danger" onclick="deleteUser(<?php echo $user->id; ?>)">Delete</button>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        <!-- Add more rows for other users -->
                    </tbody>

                </table>
                <div>
                    <?php

                    $totalPages = $users['pagination']['totalPages'];
                    $page = $users['pagination']['page'];
                    echo '<div class="pagination">5 Items per page &nbsp&nbsp&nbsp';
                    $prevPage = max(1, $page - 1);
                    $nextPage = min($totalPages, $page + 1);
                    ?>
                    <?php
                    // Display link to first page
                    if ($page > 1) {
                        echo '<a class="page" href="?page=1">First</a> ';
                    }

                    // Display ellipsis if needed
                    if ($page > 4) {
                        echo "<span>...</span> ";
                    }

                    // Display links to pages surrounding the current page
                    for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++) {
                        if ($i == $page) {
                            echo "<span>$i</span> ";
                        } else {
                            echo '<a style="border: solid 1px #847484" href="?page=' . $i . '">' . $i . '</a> ';
                        }
                    }

                    // Display ellipsis if needed
                    if ($page < $totalPages - 3) {
                        echo "<span>...</span> ";
                    }

                    // Display link to last page
                    if ($page < $totalPages) {
                        echo '<a class="page" href="?page=' . $totalPages . '">Last</a> ';
                    }

                    echo "</div>";

                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php include 'create_user.php'; ?>
    <!-- Bootstrap JS and jQuery (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function deleteUser(id) {
            var confirmDelete = confirm("Are you sure you want to delete this user?");
            if (confirmDelete) {
                let currentUrl = window.location.href;

                // Check if the URL already contains parameters
                if (currentUrl.indexOf('?') !== -1) {
                    // If parameters already exist, append the new parameter
                    currentUrl += '&action=&remove=' + id;
                } else {
                    // If no parameters exist, add the new parameter with a '?' separator
                    currentUrl += '?action=&remove=' + id;
                }

                // Navigate to the modified URL
                window.location.href = currentUrl;
                header('location:index.php?action=&remove=')

            }
        }
    </script>
</body>

</html>