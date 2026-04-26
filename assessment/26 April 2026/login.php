<?php
$username = $password = "";
$usernameErr = $passwordErr = "";
$user = "maisha@gmail.com";
$pass = "maisha123";
$result = "";

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required.";
    } else {
        $username = cleanInput($_POST["username"]);
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $usernameErr = "Invalid username format. Username is your email address.";
        }
    }

    // Password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required.";
    } else {
        $password = cleanInput($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long.";
        }
    }

    // Login Result
    if (empty($usernameErr) && empty($passwordErr)) {
        if ($username === $user && $password === $pass) {
            $result = "Login Successful!";
        } else {
            $result = "Wrong Credentials";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 20px;
            color: #222;
        }

        main {
            max-width: 500px;
            margin: 40px auto;
        }

        h1 {
            text-align: center;
            color: #111;
            margin-bottom: 20px;
        }

        fieldset {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        label {
            display: block;
            margin-bottom: 6px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .error-msg {
            color: #b91c1c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
            min-height: 18px;
        }

        .result-msg {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            text-align: center;
        }

        .subtitle {
        text-align: left;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 20px;
        color: #444;
        }

        input[type="submit"] {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background: #2563eb;
            transition: background 0.2s;
        }

        input[type="submit"]:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome Back</h1>
    </header>

    <main>
        <form method="POST" action="">
            <fieldset>
                <p class="subtitle">Log in to continue</p>

                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your email"
                    value="<?= $username ?>">

                <span class="error-msg">
                    <?= $usernameErr ?>
                </span>

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password">

                <span class="error-msg">
                    <?= $passwordErr ?>
                </span>

                <input type="submit" value="Login">

                <span class="result-msg">
                    <?= $result ?>
                </span>

            </fieldset>
        </form>
    </main>

</body>

</html>