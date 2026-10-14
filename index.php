
<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if ($title !== "" && $content !== "") {
        $stmt = $pdo->prepare(
            "INSERT INTO notes (title, content) VALUES (?, ?)"
        );
        $stmt->execute([$title, $content]);

        header("Location: index.php");
        exit;
    }
}

$stmt = $pdo->query(
    "SELECT * FROM notes ORDER BY created_at DESC"
);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 750px;
            margin: 40px auto;
            padding: 20px;
            background: #f4f6f9;
        }
        input, textarea, button {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin: 8px 0;
        }
        textarea { min-height: 120px; }
        button {
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }
        .note {
            background: white;
            padding: 16px;
            margin: 15px 0;
            border-radius: 8px;
            overflow-wrap: anywhere;
        }
        .content { white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>My Notes</h1>

    <form method="POST">
        <input type="text" name="title"
               placeholder="Note title" maxlength="255" required>

        <textarea name="content"
                  placeholder="Write your note here..."
                  required></textarea>

        <button type="submit">Save Note</button>
    </form>

    <h2>Saved Notes</h2>

    <?php foreach ($notes as $note): ?>
        <div class="note">
            <h3><?= htmlspecialchars($note["title"], ENT_QUOTES, "UTF-8") ?></h3>

            <p class="content"><?= htmlspecialchars($note["content"], ENT_QUOTES, "UTF-8") ?></p>

            <small><?= htmlspecialchars($note["created_at"], ENT_QUOTES, "UTF-8") ?></small>
        </div>
    <?php endforeach; ?>
</body>
</html>
