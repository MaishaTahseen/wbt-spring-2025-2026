<?php
$fnameErr = $lnameErr = $emailErr = $companyErr = $genderErr = "";
$fname = $lname = $email = $company = $gender = "";

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // First Name
    if (empty($_POST["fname"])) {
        $fnameErr = "First Name is required";
    } else {
        $fname = cleanInput($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
            $fnameErr = "Only letters and white space allowed";
        }
    }

    // Last Name
    if (empty($_POST["lname"])) {
        $lnameErr = "Last Name is required";
    } else {
        $lname = cleanInput($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $lnameErr = "Only letters and white space allowed";
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


    // Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = cleanInput($_POST["gender"]);
    }


 

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Me</title>
    <link rel="stylesheet" type="text/css" href="contact.css">

</head>
<body>
    <header>
        <nav>
            <ul id = navbar>
                <li><h4><a href="../index.html">Portfolio</a></h4></li>
                <li><h4><a href="educations.html">Education</a></h4></li> 
                <li><h4><a href="experience.html">Experience</a></h4></li>    
                <li><h4><a href="projects.html">Projects</a></h4></li>
            </ul>
        </nav>
    </header>
    <h1>Contact Me</h1>
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <fieldset>
    <legend>Information for contact</legend>
    <p><span style="color:red">* indicates required field</span></p>

        <table class="form-table">

        <tr>
            <td>First Name<span style="color:red">*</span></td>
            <td>
            <input type="text" name="fname" value="<?= $fname ?>">
            <span class="error"><?= $fnameErr ?></span>
            </td>
        </tr>

        <tr>
            <td>Last Name<span style="color:red">*</span></td>
            <td>
            <input type="text" name="lname" value="<?= $lname ?>">
            <span class="error"><?= $lnameErr ?></span>
            </td>
        </tr>

        <tr>
            <td>Gender<span style="color:red">*</span></td>
            <td>
            <input type="radio" name="gender" value="male" <?= ($gender=="male")?"checked":"" ?>> Male
            <input type="radio" name="gender" value="female" <?= ($gender=="female")?"checked":"" ?>> Female
            <span class="error"><?= $genderErr ?></span>
            </td>
        </tr>

        <tr>
            <td>Email<span style="color:red">*</span></td>
            <td>
            <input type="text" name="email" value="<?= $email ?>">
            <span class="error"><?= $emailErr ?></span>
            </td>
        </tr>

        <tr>
            <td>Company</td>
            <td>
            <input type="text" name="company">
            </td>
        </tr>

        <tr>
            <td>Reason of Contact</td>
        <td>
            <select name="roc">
                <option value="">Select</option>
                <option value="Project">Project</option>
                <option value="Thesis" >Thesis</option>
                <option value="Job" >Job</option>
            </select>

            </td>
        </tr>

        <tr>
        <td>Topics</td>
            <td>
            <input type="checkbox" name="topics" value="webedev" > Web Dev
            <input type="checkbox" name="topics" value="mobdev"> Mobile Dev
            <input type="checkbox" name="topics" value="aiml"> AI/ML
            </td>
        </tr>

        <tr>
            <td>Consultation Date</td>
            <td>
            <input type="date" name="cdate" >
            </td>
        </tr>

        <tr>
            <td>Message</td>
            <td>
            <textarea name="message"></textarea>
            </td>
        </tr>

        <tr>
            <td>
            <input type="submit" value="Register">
            </td>
            <td>
                <input type="reset">
            </td>
        </tr>

        </table>
    </fieldset>
    </form>
    <footer>
        <a href="https://github.com/MaishaTahseen" target="_blank"><img src="../Data/GitHub-Logo.png" title="MaishaTahseen/GithHub" height="50"></a>
        <a href="https://www.linkedin.com/in/maishatahseen25/" target="_blank"><img src="../Data/linkedin-logo.png" title="MaishaTahseen/LinkedIn" height="50"></a>
    </footer>
</body>
</html>