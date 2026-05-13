<?php
include 'db.php';

$result = $conn->query("SELECT id, filename, filecontent FROM uploads ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uploaded Files</title>
    
</head>
<body>
<h2>Uploaded Files</h2>

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <h3><?php echo htmlspecialchars($row['filename']); ?></h3>
        <pre style="background:#f0f0f0;padding:10px;">
<?php
    // Detect if it's a text file
    $mime = mime_content_type_from_blob($row['filecontent']);
    if (str_starts_with($mime, 'text')) {
        echo htmlspecialchars($row['filecontent']);
    } else {
        echo "(Binary file cannot be displayed as text)";
    }
?>
        </pre>
        <hr>
    <?php endwhile; ?>
<?php else: ?>
    <p>No files uploaded yet.</p>
<?php endif; ?>

<p><a href="upload.php">Upload Another File</a></p>
</body>
</html>

<?php
// Helper function (if PHP version < 8.1)
function mime_content_type_from_blob($blob) {
    $tmpfile = tempnam(sys_get_temp_dir(), 'blob');
    file_put_contents($tmpfile, $blob);
    $mime = mime_content_type($tmpfile);
    unlink($tmpfile);
    return $mime;
}
?>
