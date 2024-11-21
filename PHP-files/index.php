<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Network</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #333;
            background-color: #f4f4f9;
        }

        /* Banner Section */
        .banner {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            margin-bottom: 20px;
            color: #fff;
        }

        .banner-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(70%);
            z-index: -1;
        }

        .overlay {
            z-index: 1;
            max-width: 50%;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            font-weight: bold;
            color: #fff;
        }

        /* Navigation */
        nav {
            background-color: #0073e6;
            padding: 10px 20px;
            width: 100%;
            text-align: center;
            position: relative;
        }

        nav ul {
            list-style: none;
            display: inline-flex;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            margin: 0 15px;
            padding: 10px 0;
            display: block;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #ffd700;
        }

        /* Dropdown Menu */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #0073e6;
            min-width: 160px;
            display: none;
            flex-direction: column;
            z-index: 1;
        }

        .dropdown a {
            padding: 10px 20px;
            text-align: left;
        }

        nav ul li:hover .dropdown {
            display: flex;
        }

        .dropdown a:hover {
            background-color: #005bb5;
            color: #fff;
        }

        /* Admin Login Section */
        .admin-login {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 300px;
            text-align: center;
            z-index: 1;
        }

        .admin-login h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #0073e6;
        }

        .admin-login input[type="text"],
        .admin-login input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .admin-login button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #0073e6;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }

        .admin-login button:hover {
            background-color: #005bb5;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            h1 {
                font-size: 1.8em;
            }

            nav ul {
                flex-direction: column;
            }

            nav ul li {
                margin: 5px 0;
            }

            nav ul li a {
                margin: 0;
            }

            .dropdown {
                position: static;
            }
        }
    </style>
</head>
<body>

    <!-- Banner Section -->
    <div class="banner">
        <img src="university.jpeg" alt="University Image" class="banner-image">
        <div class="overlay">
            <h1>Welcome to the Alumni Network</h1>
        </div>
        <!-- Admin Login Section -->
        <div class="admin-login" id="admin-login">
    <h2>Admin Login</h2>
    <form action="admin.php" method="post" onsubmit="return validateLogin()">
        <input type="text" id="admin_username" name="admin_username" placeholder="Admin Username" required>
        <input type="password" id="admin_password" name="admin_password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
    </div>


    <script>
    function validateLogin() {
        // Get the values of the username and password fields
        const username = document.getElementById("admin_username").value;
        const password = document.getElementById("admin_password").value;

        // Check if the username is 'admin' and password is 'pass'
        if (username === "admin" && password === "pass") {
            return true; // Allow the form to submit
        } else {
            alert("Invalid username or password. Please try again.");
            return false; // Prevent the form from submitting
        }
    }
</script>

    <!-- Navigation Section -->
    <nav>
        <ul>
            <li><a href="register.php">Register</a></li>
            <li>
                <a href="#">Login &#9662;</a>
                <div class="dropdown">
                    <a href="login.php">Alumnus</a>
                    <a href="coord_login.php">Coordinator</a>
                </div>
            </li>
        </ul>
    </nav>

</body>
</html>
