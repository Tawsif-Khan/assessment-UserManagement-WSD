<!-- Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" id="name" name="username" class="form-control" placeholder="Enter your name" value="<?php echo $response['old_data']['username'] ?? "";?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" value="<?php echo $response['old_data']['email'] ?? "";?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control">
                            <option value="admin" <?php echo $response['old_data']['role']?? 'admin' == 'admin' ? 'selected':'';?>>Admin</option>
                            <option value="user" <?php echo $response['old_data']['role'] ?? 'admin' == 'user' ? 'selected':'';?>>User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addUser">Create User</button>
                </form>
            </div>
        </div>
    </div>
</div>

