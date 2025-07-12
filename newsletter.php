<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: login.php");
    exit;
}
include 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Newsletter</title>

    <!-- Bootstrap & CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/newsletter.css">

    <!-- Select2 for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- CKEditor 4 (No API key needed) -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
</head>
<body>
<div class="container mt-4">

    <!-- Header and Logos -->
    <div class="text-center mb-4">
        <img src="images/coles_logo.png" alt="Coles Logo" style="height: 70px;">
        <h3 class="mt-3">Coles College of Business - Alumni Newsletter</h3>
        <hr>
    </div>

    <!-- Newsletter Form -->
    <form method="POST" action="send_newsletter.php" enctype="multipart/form-data">
        
        <!-- Recipients Dropdown -->
        <div class="form-group">
            <label for="recipients">Select Recipients</label>
            <select class="form-control" id="recipients" name="recipients[]" multiple required>
                <option value="all">All Alumni</option>
                <?php
                $res = mysqli_query($conn, "SELECT alumniID, fName, lName FROM alumni");
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<option value='{$row['alumniID']}'>{$row['fName']} {$row['lName']}</option>";
                }
                ?>
            </select>
            <small class="form-text text-muted">Start typing a name to search alumni.</small>
        </div>

        <!-- Subject -->
        <div class="form-group">
            <label for="headline">Subject</label>
            <input type="text" name="headline" id="headline" class="form-control" required>
        </div>

        <!-- CKEditor Newsletter Description -->
        <div class="form-group">
            <label for="description">Newsletter Content</label>
            <textarea name="description" id="description" class="form-control"></textarea>
            <small class="form-text text-muted">Use formatting tools to style your message.</small>
        </div>

        <!-- Optional External Link -->
        <div class="form-group">
            <label for="link">External Link (optional)</label>
            <input type="url" name="link" id="link" class="form-control">
        </div>

        <!-- File Upload -->
        <div class="form-group">
            <label for="newsletter_file">Attach Newsletter File (PDF)</label>
            <input type="file" name="newsletter_file" id="newsletter_file" class="form-control-file" accept="application/pdf">
        </div>

        <!-- Buttons -->
        <button type="submit" class="btn btn-yellow">Send Newsletter</button>
        <a href="dashboard.php" class="btn btn-cancel ml-2">Cancel</a>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    // Initialize Select2
    $('#recipients').select2({
        placeholder: "Search or select alumni...",
        width: '100%'
    });

    // Initialize CKEditor
    CKEDITOR.replace('description', {
        height: 300,
        on: {
            instanceReady: function (evt) {
                const editor = evt.editor;
                const logoHTML = `
                    <div style="text-align:right; margin-top:30px;">
                        <img src="images/coles_logo.png" alt="Coles Logo" style="height:60px;">
                    </div>`;
                editor.setData(logoHTML);
            }
        }
    });
});
</script>

</body>
</html>
