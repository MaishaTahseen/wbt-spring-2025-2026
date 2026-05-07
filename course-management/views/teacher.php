<?php $user = $_SESSION['user']; $isEdit = !empty($editing); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Teacher Dashboard &mdash; Course Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="app-body">

<!-- Navbar -->
<header class="navbar">
    <div class="navbar-inner">
        <a class="brand" href="index.php?page=teacher">
            <span class="brand-icon">&#128218;</span>
            <span>CourseSys</span>
        </a>
        <div class="nav-user">
            <span class="user-pill">
                <span class="user-avatar"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                <span class="user-meta">
                    <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                    <span class="user-role">Teacher</span>
                </span>
            </span>
            <a href="index.php?page=logout" class="btn-logout">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Courses</h1>
            <p class="page-sub">Add, edit, search and remove courses in the catalog</p>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <?php $messages = ['added' => 'Course added successfully.',
                           'updated' => 'Course updated successfully.',
                           'deleted' => 'Course deleted successfully.'];
              $msg = $messages[$_GET['msg']] ?? null; ?>
        <?php if ($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ============ Add / Edit Form ============ -->
    <div class="card form-card">
        <h3 class="card-title">
            <?= $isEdit ? '&#9998; Edit Course (#' . intval($editing['id']) . ')' : '+ Add New Course' ?>
        </h3>
        <form method="POST"
              action="index.php?page=teacher&action=<?= $isEdit ? 'update&id=' . intval($editing['id']) : 'add' ?>"
              class="form" novalidate>
            <div class="field-row">
                <div class="field">
                    <label for="name">Course Name</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($editing['name'] ?? '') ?>"
                           placeholder="e.g. Introduction to PHP" required>
                </div>
                <div class="field">
                    <label for="coursecode">Course Code</label>
                    <input type="text" id="coursecode" name="coursecode"
                           value="<?= htmlspecialchars($editing['coursecode'] ?? '') ?>"
                           placeholder="e.g. CS101" required>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label for="credits">Credits</label>
                    <input type="number" id="credits" name="credits" min="0"
                           value="<?= htmlspecialchars($editing['credits'] ?? '') ?>"
                           placeholder="0" required>
                </div>
                <div class="field">
                    <label for="fees">Fees ($)</label>
                    <input type="number" id="fees" name="fees" step="0.01" min="0"
                           value="<?= htmlspecialchars($editing['fees'] ?? '') ?>"
                           placeholder="0.00" required>
                </div>
            </div>
            <div class="form-actions">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=teacher" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Course</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary">Save Course</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- ============ Courses Table ============ -->
    <div class="card">
        <div class="card-toolbar">
            <div class="search-wrap">
                <span class="search-icon">&#128269;</span>
                <input type="text" id="searchInput" class="search-input"
                       placeholder="Search by name or code...">
            </div>
            <span class="badge" id="resultCount"><?= count($courses) ?> total</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Credits</th>
                        <th>Fees</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (empty($courses)): ?>
                        <tr><td colspan="6" class="empty">No courses yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($courses as $i => $course): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($course['name']) ?></td>
                                <td><?= htmlspecialchars($course['coursecode']) ?></td>
                                <td><?= htmlspecialchars($course['credits']) ?></td>
                                <td>$<?= number_format($course['fees'], 2) ?></td>
                                <td class="text-right">
                                    <a class="btn-sm btn-edit"
                                       href="index.php?page=teacher&action=edit&id=<?= $course['id'] ?>">Edit</a>
                                    <a class="btn-sm btn-delete"
                                       href="index.php?page=teacher&action=delete&id=<?= $course['id'] ?>"
                                       onclick="return confirm('Delete this course?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer class="footer">&copy; <?= date('Y') ?> Course Management System</footer>

<!-- =========== Inline AJAX search =========== -->
<script>
(function () {
    var input    = document.getElementById('searchInput');
    var body     = document.getElementById('tableBody');
    var counter  = document.getElementById('resultCount');
    var timer;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function render(rows) {
        if (!rows.length) {
            body.innerHTML = '<tr><td colspan="6" class="empty">No matching results.</td></tr>';
            counter.textContent = '0 results';
            return;
        }
        var html = '';
        rows.forEach(function (b, i) {
            html +=
                '<tr>' +
                    '<td>' + (i + 1) + '</td>' +
                    '<td>' + esc(b.name) + '</td>' +
                    '<td>' + esc(b.coursecode) + '</td>' +
                    '<td>' + esc(b.credits) + '</td>' +
                    '<td>$' + parseFloat(b.fees).toFixed(2) + '</td>' +
                    '<td class="text-right">' +
                        '<a class="btn-sm btn-edit" href="index.php?page=teacher&action=edit&id=' + b.id + '">Edit</a>' +
                        '<a class="btn-sm btn-delete" href="index.php?page=teacher&action=delete&id=' + b.id +
                        '" onclick="return confirm(\'Delete this course?\')">Delete</a>' +
                    '</td>' +
                '</tr>';
        });
        body.innerHTML = html;
        counter.textContent = rows.length + (input.value.trim() ? ' results' : ' total');
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            fetch('index.php?page=ajax&type=course&q=' + encodeURIComponent(input.value.trim()),
                  { credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(render)
                .catch(function (e) { console.error(e); });
        }, 200);
    });
})();
</script>

</body>
</html>