<?php
// ================================================================
// MODELS - All DB access using procedural mysqli + prepared stmts
// ================================================================

/* ------------------- Admin ------------------- */
function authAdmin($conn, $username, $password) {
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

/* ----------------- Teacher ----------------- */
function getTeachers($conn) {
    $r = mysqli_query($conn, "SELECT id, name, contact, username FROM teachers ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getTeacher($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, contact, username FROM teachers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function addTeacher($conn, $name, $contact, $username, $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn,
        "INSERT INTO teachers (name, contact, username, password) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $contact, $username, $hash);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updateTeacher($conn, $id, $name, $contact, $username) {
    $stmt = mysqli_prepare($conn,
        "UPDATE teachers SET name = ?, contact = ?, username = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $contact, $username, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deleteTeacher($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM teachers WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function searchTeachers($conn, $term) {
    $like = '%' . $term . '%';
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, contact, username FROM teachers
         WHERE name LIKE ? OR username LIKE ? OR contact LIKE ?
         ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}

function authTeacher($conn, $username, $password) {
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, username, password FROM teachers WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

function teacherUsernameExists($conn, $username, $excludeId = null) {
    if ($excludeId) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM teachers WHERE username = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, 'si', $username, $excludeId);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM teachers WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

/* ------------------- Course ------------------- */
function getCourses($conn) {
    $r = mysqli_query($conn, "SELECT id, name, coursecode, credits, fees FROM courses ORDER BY id DESC");
    return mysqli_fetch_all($r, MYSQLI_ASSOC);
}

function getCourse($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, name, coursecode, credits, fees FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $row;
}

function addCourse($conn, $name, $coursecode, $credits, $fees, $teacherId) {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO courses (name, coursecode, credits, fees, teacher_id) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssidi', $name, $coursecode, $credits, $fees, $teacherId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function updateCourse($conn, $id, $name, $coursecode, $credits, $fees) {
    $stmt = mysqli_prepare($conn,
        "UPDATE courses SET name = ?, coursecode = ?, credits = ?, fees = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssidi', $name, $coursecode, $credits, $fees, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function deleteCourse($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function searchCourses($conn, $term) {
    $like = '%' . $term . '%';
    $stmt = mysqli_prepare($conn,
        "SELECT id, name, coursecode, credits, fees FROM courses
         WHERE name LIKE ? OR coursecode LIKE ?
         ORDER BY id DESC");
    mysqli_stmt_bind_param($stmt, 'ss', $like, $like);
    mysqli_stmt_execute($stmt);
    $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $rows;
}
?>