<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($subject === '' || $description === '') {
        $error = 'Заполните обязательные поля!';
    } else {
        $newTicket = [
            'id' => uniqid('TID-'),
            'subject' => $subject,
            'category' => $category,
            'priority' => $priority,
            'description' => $description,
            'status' => 'new', // Начальный статус по ТЗ
            'created_at' => date('Y-m-d H:i:s')
        ];

        save_ticket($newTicket);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать заявку</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Создать новую заявку</h1>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Тема:</label>
            <input type="text" name="subject" required>
        </div>
        <div class="form-group">
            <label>Категория:</label>
            <select name="category">
                <option value="Техническая">Техническая</option>
                <option value="Финансовая">Финансовая</option>
                <option value="Общая">Общая</option>
            </select>
        </div>
        <div class="form-group">
            <label>Приоритет:</label>
            <select name="priority">
                <option value="Низкий">Низкий</option>
                <option value="Средний">Средний</option>
                <option value="Высокий">Высокий</option>
            </select>
        </div>
        <div class="form-group">
            <label>Описание проблемы:</label>
            <textarea name="description" rows="5" required></textarea>
        </div>
        <button type="submit">Отправить заявку</button>
    </form>
    <p><br><a href="index.php">← Назад к списку</a></p>
</div>
</body>
</html>