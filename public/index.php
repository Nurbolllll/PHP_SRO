<?php
declare(strict_types=1);
require_once __DIR__ . '/../src/functions.php';

$tickets = get_tickets();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Система заявок в поддержку</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        a.button { display: inline-block; padding: 10px 15px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
<h1>Список обращений в поддержку</h1>
<p>
    <a href="create.php" class="button">Создать новую заявку</a>
    <a href="operator.php" class="button" style="background: #6c757d;">Панель оператора</a>
</p>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Категория</th>
            <th>Приоритет</th>
            <th>Статус</th>
            <th>Дата создания</th>
        </tr>
        <?php if (empty($tickets)): ?>
            <tr><td colspan="6">Заявок пока нет.</td></tr>
        <?php else: ?>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id']) ?></td>
                    <td><?= htmlspecialchars($ticket['subject']) ?></td>
                    <td><?= htmlspecialchars($ticket['category']) ?></td>
                    <td><?= htmlspecialchars($ticket['priority']) ?></td>
                    <td><?= htmlspecialchars($ticket['status']) ?></td>
                    <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
</body>
</html>