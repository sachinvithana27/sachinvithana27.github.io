<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli('localhost', 'root', 's23010772@ousl.lk', 'campus_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'login') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($username == 'admin' && $password == '12345') {
                $_SESSION['loggedin'] = true;
                header('Location: index.php?page=home');
                exit();
            } else {
                echo "Invalid login credentials.";
            }
        } elseif ($action == 'register') {
            $nic = $_POST['nic'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $tel_no = $_POST['tel_no'];
            $course = $_POST['course'];

            $sql = "INSERT INTO students (nic, name, address, tel_no, course) VALUES ('$nic', '$name', '$address', '$tel_no', '$course')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } elseif ($action == 'search') {
            $nic = $_POST['nic'];
            $sql = "SELECT * FROM students WHERE nic='$nic'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "NIC: " . $row["nic"]. " - Name: " . $row["name"]. " - Address: " . $row["address"]. " - Tel No: " . $row["tel_no"]. " - Course: " . $row["course"]. "<br>";
                }
            } else {
                echo "0 results";
            }
        } elseif ($action == 'update') {
            $nic = $_POST['nic'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $tel_no = $_POST['tel_no'];
            $course = $_POST['course'];

            $sql = "UPDATE students SET name='$name', address='$address', tel_no='$tel_no', course='$course' WHERE nic='$nic'";
            if ($conn->query($sql) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } elseif ($action == 'delete') {
            $nic = $_POST['nic'];
            $sql = "DELETE FROM students WHERE nic='$nic'";
            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Website</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="navbar">
        <a href="?page=home">Home</a>
        <a href="?page=login">Login</a>
        <a href="?page=register">Register</a>
        <a href="?page=search">Search</a>
        <a href="?page=update">Update</a>
        <a href="?page=delete">Delete</a>
    </div>

    <div class="container">
        <?php
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == 'login') {
                ?>
                <h2>Login Page</h2>
                <form method="post">
                    <input type="hidden" name="action" value="login">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br><br>
                    <input type="submit" value="Login">
                </form>
                <?php
            } elseif ($page == 'register') {
                ?>
                <h2>Student Registration</h2>
                <form method="post">
                    <input type="hidden" name="action" value="register">
                    <label for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" required><br><br>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required><br><br>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea><br><br>
                    <label for="tel_no">Tel. No:</label>
                    <input type="text" id="tel_no" name="tel_no" required><br><br>
                    <label for="course">Course:</label>
                    <input type="text" id="course" name="course" required><br><br>
                    <input type="submit" value="Register">
                </form>
                <?php
            } elseif ($page == 'search') {
                ?>
                <h2>Search Student</h2>
                <form method="post">
                    <input type="hidden" name="action" value="search">
                    <label for="nic">Enter NIC:</label>
                    <input type="text" id="nic" name="nic" required><br><br>
                    <input type="submit" value="Search">
                </form>
                <?php
            } elseif ($page == 'update') {
                ?>
                <h2>Update Student</h2>
                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <label for="nic">Enter NIC:</label>
                    <input type="text" id="nic" name="nic" required><br><br>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name"><br><br>
                    <label for="address">Address:</label>
                    <textarea id="address" name="address"></textarea><br><br>
                    <label for="tel_no">Tel. No:</label>
                    <input type="text" id="tel_no" name="tel_no"><br><br>
                    <label for="course">Course:</label>
                    <input type="text" id="course" name="course"><br><br>
                    <input type="submit" value="Update">
                </form>
                <?php
            } elseif ($page == 'delete') {
                ?>
                <h2>Delete Student</h2>
                <form method="post">
                    <input type="hidden" name="action" value="delete">
                    <label for="nic">Enter NIC:</label>
                    <input type="text" id="nic" name="nic" required><br><br>
                    <input type="submit" value="Delete">
                </form>
                <?php
            }
        } else {
            ?>
            <h2>Matrix Graduate School</h2>
            <img src="logo.png" alt="Matrix Graduate School Logo" style="display: block; margin: 0 auto; width: 200px;">
            <p>Welcome to the Matrix Graduate School website. This website allows you to manage student information efficiently.</p>
            <?php
        }
        ?>
    </div>
</body>
</html>