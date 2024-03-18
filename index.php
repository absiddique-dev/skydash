<?php
session_start();
include("./includes/conn.php")
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin - AssamTet</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="images/newsclez-logo.png" />
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="box">
        <h2>Admin - AssamTet</h2>
        <form method="POST">
            <div class="inputBox">
                <input type="text" name="email" required="">
                <label for="">Username</label>
            </div>
            <div class="inputBox">
                <input type="password" name="password" required="">
                <label for="">Password</label>
            </div>
            <input type="submit" name="submit" value="Login">
        </form>
        <?php
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `authentication` WHERE `email` = '$email' AND `password` = '$password'"));

            if ($user) {
                //Login success
                $_SESSION['email'] = $email;
        ?>
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Login Successful',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.replace("dashboard.php")
                        }
                    })
                    
                </script>
            <?php
            } else {
            ?>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid login details',
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    })
                </script>
        <?php
            }
        }
        ?>
    </div>
</body>

</html>