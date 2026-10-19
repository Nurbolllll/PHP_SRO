<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../src/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        die('Ошибка безопасности: неверный CSRF-токен!');
    }

    $ticketId = $_POST['ticket_id'] ?? '';
    $newStatus = $_POST['status'] ?? '';

    if ($ticketId && in_array($newStatus, ['new', 'in_progress', 'closed'], true)) {
        if (update_ticket_status($ticketId, $newStatus)) {
            $message = 'Статус заявки успешно обновлен!';
        }
    }
}

$tickets = get_tickets();
$csrfToken = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель оператора</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f9; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #343a40; color: white; }
        select { padding: 5px; }
        button { background: #28a745; color: white; border: none; padding: 6px 12px; cursor: pointer; border-radius: 4px; }
        .success { color: green; margin-bottom: 10px; font-weight: bold; }
        .nav { margin-bottom: 20px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Панель оператора</h1>
    <div class="nav">
        <a href="index.php">← На главную</a>
    </div>

    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Тема</th>
            <th>Категория</th>
            <th>Приоритет</th>
            <th>Описание</th>
            <th>Текущий статус</th>
            <th>Действие</th>
        </tr>
        <?php if (empty($tickets)): ?>
            <tr><td colspan="7">Заявок нет.</td></tr>
        <?php else: ?>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['id']) ?></td>
                    <td><?= htmlspecialchars($ticket['subject']) ?></td>
                    <td><?= htmlspecialchars($ticket['category']) ?></td>
                    <td><?= htmlspecialchars($ticket['priority']) ?></td>
                    <td><?= htmlspecialchars($ticket['description']) ?></td>
                    <td><b><?= htmlspecialchars($ticket['status']) ?></b></td>
                    <td>
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($ticket['id']) ?>">
                            <select name="status">
                                <option value="new" <?= $ticket['status'] === 'new' ? 'selected' : '' ?>>new</option>
                                <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>in_progress</option>
                                <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>closed</option>
                            </select>
                            <button type="submit">Изменить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
</body>
</html>