<?php

//$alumniList = mysqli_fetch_assoc(mysqli_query($conn, "SELECT fname, lname FROM alumni"))['alumni'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Newsletter</title>

    <!-- Self-hosted TinyMCE -->
    <script src="js/tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#message',
            plugins: 'lists link',
            toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | link',
            menubar: false,
            height: 200
        });
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            width: 400px;
            margin: 50px auto;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input[type="text"], select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        .button-group {
            margin-top: 20px;
            text-align: center;
        }
        .button-group input {
            padding: 10px 20px;
            margin: 0 10px;
        }
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="logo">
    <img src="images/owl ksu logo.png" alt="KSU Logo" class="hero-logo" />
</div>

<h2>Alumni Newsletter</h2>

<form action="process_form.php" method="post">
    <label for="recipients">Recipients:</label>

    <!-- 
    <label for="recipients">Recipients:</label>
    <select name="recipients[]" id="recipients" multiple size="5" required>    
    <?php foreach ($recipients as $recipient): ?>
        <option value="<?= htmlspecialchars($recipient['email']) ?>">
            <?= htmlspecialchars($recipient['name']) ?> (<?= htmlspecialchars($recipient['email']) ?>)
        </option>
    <?php endforeach; ?>
    </select>
    <small>Hold Ctrl/Cmd to select multiple recipients</small>
    
    -->
    <select name="recipients" id="recipients" required>
        <option value="">Select ...</option>
        <option value="all">All Alumni</option>
        <option value="recent">Recent Graduates</option>
        <option value="donors">Donors</option>
    </select>

    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject" required>

    <label for="message">Message:</label>
    <textarea id="message" name="message"></textarea>

    <div class="button-group">
        <input type="submit" value="SEND">
        <input type="reset" value="CLEAR">
    </div>
</form>

</body>
</html>
