<?php
$lNameErr = $fNameErr = $emailErr = $contactErr = $passErr = "";
$fName = $lName = $email = $pass = $contact = "";

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // First Name
        if (empty($_POST["firstName"])) {
            $fNameErr = "First Name is required";
        } else {
            $fName = cleanInput($_POST["firstName"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $fName)) {
                $fNameErr = "Only letters and white space allowed";
            }
        }

        // Last Name
        if (empty($_POST["lastName"])) {
            $lNameErr = "Last Name is required";
        } else {
            $lName = cleanInput($_POST["lastName"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $lName)) {
                $lNameErr = "Only letters and white space allowed";
            }
        }

        // Email 
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = cleanInput($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        // Contact
        if (empty($_POST["contact"])) {
            $contactErr = "Contact is required";
        } else {
            $contact = cleanInput($_POST["contact"]);
            if (!preg_match("/^[0-9]*$/", $contact)) {
                $contactErr = "Only numbers allowed";
            }
        }

        // Password 
        if (empty($_POST["pass"])) {
            $passErr = "Password is required";
        } else {
            $pass = cleanInput($_POST["pass"]);
            if (strlen($pass) < 8) {
                $passErr = "minimum 8 characters required";
            }

        }
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
</head>

<body>
    <header>
        <h1>Sign Up</h1>
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

            legend {
                font-size: 1.2rem;
                font-weight: bold;
                padding: 0 10px;
                color: #2563eb;
            }

            label {
                display: block;
                margin-bottom: 6px;
                margin-top: 15px;
                font-weight: bold;
                font-size: 14px;
            }

            input[type="text"],
            input[type="password"],
            input[type="email"] {
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
    </header>
    <main>
        <form method="post">
            <fieldset>

                <label for="firstName">First Name </label>
                <input type="text" id="firstName" name="firstName" placeholder="Enter your first name"
                    value="<?= $fName ?>">
                <span style="color:red; display:block;">
                    <?= $fNameErr ?>
                </span>


                <label for="lastName">Last Name </label>
                <input type="text" id="lastName" name="lastName" placeholder="Enter your last name"
                    value="<?= $lName ?>">
                <span style="color:red; display:block;">
                    <?= $lNameErr ?>
                </span>

                <label for="contact">Contact No </label>
                <input type="text" id="contact" name="contact" placeholder="Enter your contact no"
                    value="<?= $contact ?>">
                <span style="color:red; display:block;">
                    <?= $contactErr ?>
                </span>

                <label for="email">Email </label>
                <input type="text" id="email" name="email" placeholder="Enter your email" value="<?= $email ?>">
                <span style="color:red; display:block;">
                    <?= $emailErr ?>
                </span>


                <label for="pass">Password </label>
                <input type="text" id="pass" name="pass" placeholder="Enter your password" value="<?= $pass ?>">
                <span style="color:red; display:block;">
                    <?= $passErr ?>
                </span>


                <input type="submit" value="Register">
            </fieldset>
        </form>
    </main>
</body>

</html>