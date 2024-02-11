# Assessment-UserManagement-WSD

## Chy Mohammed Tawsif Khan

#### tawsif.online@gmail.com

#### Clone this repository

```
git clone https://github.com/Tawsif-Khan/assessment-UserManagement-WSD.git
```

#### Create a database with the following name

```
user_management
```

#### Configure the `config/config.php` file

```
define('DB_HOST', 'localhost');
define('DB_NAME', 'user_management'); // database name
define('DB_USER', 'root'); // database user
define('DB_PASS', '********'); // database password

```

#### Run this command to start project

```
php -S localhost:8000
```

#### Migrate the database. Hit the following URL

```
http://localhost:8000/migration.php
```

#### Then hit this URL

```
http://localhost:8000
```

## Features

- Authentication
  - Email and password empty validation
  - Email validation
  - Login
  - Logout
- Create user
  - Empty input validation
  - Email validation
  - Email uniqueness validation
  - Password restriction
  - If submission failes, then keep the input values
- User list
  - Search by username and email
  - Empty search
  - Edit user
  - Delete user
- Edit user

  - Empty input validation
  - Email validation
  - Email uniqueness validation
  - Password restriction
  - If submission failes, then keep the input values

- Bonus
  - Only admins can add, edit and delete users
  - included paginations
- Others
  - Input validations
  - Error handling
  - Well structured code

#### Note: This can be more optimised. and I believe this is the best optimised within this time period.

### Happy Coding :)
