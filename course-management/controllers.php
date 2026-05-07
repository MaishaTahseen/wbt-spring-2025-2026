<?php
// ================================================================
// CONTROLLERS - request handling + role-based logic
// ================================================================

/* ============== Login ============== */
function loginCtrl($conn) {
    $error = '';
    $prefill = $_COOKIE['remember_user'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if ($u === '' || $p === '') {
            $error = 'Please fill in both fields.';
        } else {
            // Try admin first
            $admin = authAdmin($conn, $u, $p);
            if ($admin) {
                $_SESSION['user'] = [
                    'id' => $admin['id'], 'username' => $admin['username'],
                    'name' => 'Administrator', 'role' => 'admin'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=admin');
                exit;
            }
            // Then teacher
            $teacher = authTeacher($conn, $u, $p);
            if ($teacher) {
                $_SESSION['user'] = [
                    'id' => $teacher['id'], 'username' => $teacher['username'],
                    'name' => $teacher['name'], 'role' => 'teacher'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=teacher');
                exit;
            }
            $error = 'Invalid username or password.';
        }
    }

    require 'views/login.php';
}

/* ============== Register (teacher self-registration) ============== */
function registerCtrl($conn) {
    $error = $success = '';
    $old = ['name' => '', 'contact' => '', 'username' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $old = compact('name', 'contact', 'username');

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (teacherUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addTeacher($conn, $name, $contact, $username, $password)) {
                $success = 'Account created! You can now log in.';
                $old = ['name' => '', 'contact' => '', 'username' => ''];
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }

    require 'views/register.php';
}

/* ============== Admin Dashboard (manages teachers) ============== */
function adminCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;  // when set, view shows Edit form instead of Add form

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (teacherUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addTeacher($conn, $name, $contact, $username, $password)) {
                header('Location: index.php?page=admin&msg=added');
                exit;
            }
            $error = 'Failed to add teacher.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id       = intval($_GET['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');

        // ===== NULL VALIDATION on UPDATE =====
        if ($name === '' || $contact === '' || $username === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } elseif (teacherUsernameExists($conn, $username, $id)) {
            $error = 'That username is used by another teacher.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } else {
            if (updateTeacher($conn, $id, $name, $contact, $username)) {
                header('Location: index.php?page=admin&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        }
    }

    /* --- Show edit form (GET) --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getTeacher($conn, $id);
    }

    /* --- Delete (GET) --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deleteTeacher($conn, $id);
        header('Location: index.php?page=admin&msg=deleted');
        exit;
    }

    $teachers = getTeachers($conn);
    require 'views/admin.php';
}

/* ============== Teacher Dashboard (manages courses) ============== */
function teacherCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name       = trim($_POST['name'] ?? '');
        $coursecode = trim($_POST['coursecode'] ?? '');
        $credits    = trim($_POST['credits'] ?? '');
        $fees       = trim($_POST['fees'] ?? '');

        if ($name === '' || $coursecode === '' || $credits === '' || $fees === '') {
            $error = 'All fields are required.';
        } elseif (!ctype_digit($credits) || intval($credits) < 0) {
            $error = 'Credits must be a non-negative whole number.';
        } elseif (!is_numeric($fees) || floatval($fees) < 0) {
            $error = 'Fees must be a non-negative number.';
        } else {
            $teacherId = $_SESSION['user']['id'];
            if (addCourse($conn, $name, $coursecode, intval($credits), floatval($fees), $teacherId)) {
                header('Location: index.php?page=teacher&msg=added');
                exit;
            }
            $error = 'Failed to add course.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id         = intval($_GET['id'] ?? 0);
        $name       = trim($_POST['name'] ?? '');
        $coursecode = trim($_POST['coursecode'] ?? '');
        $credits    = trim($_POST['credits'] ?? '');
        $fees       = trim($_POST['fees'] ?? '');

        // ===== NULL VALIDATION on UPDATE =====
        if ($name === '' || $coursecode === '' || $credits === '' || $fees === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'name' => $name, 'coursecode' => $coursecode,
                        'credits' => $credits, 'fees' => $fees];
        } elseif (!ctype_digit($credits) || intval($credits) < 0) {
            $error = 'Credits must be a non-negative whole number.';
            $editing = ['id' => $id, 'name' => $name, 'coursecode' => $coursecode,
                        'credits' => $credits, 'fees' => $fees];
        } elseif (!is_numeric($fees) || floatval($fees) < 0) {
            $error = 'Fees must be a non-negative number.';
            $editing = ['id' => $id, 'name' => $name, 'coursecode' => $coursecode,
                        'credits' => $credits, 'fees' => $fees];
        } else {
            if (updateCourse($conn, $id, $name, $coursecode, intval($credits), floatval($fees))) {
                header('Location: index.php?page=teacher&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'name' => $name, 'coursecode' => $coursecode,
                        'credits' => $credits, 'fees' => $fees];
        }
    }

    /* --- Show edit form --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getCourse($conn, $id);
    }

    /* --- Delete --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deleteCourse($conn, $id);
        header('Location: index.php?page=teacher&msg=deleted');
        exit;
    }

    $courses = getCourses($conn);
    require 'views/teacher.php';
}
?>