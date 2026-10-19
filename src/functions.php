<?php
declare(strict_types=1);

// Путь к файлу с данными
function get_data_file_path(): string {
    return __DIR__ . '/../var/data/tickets.json';
}

// Получить все заявки из JSON
function get_tickets(): array {
    $file = get_data_file_path();
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

// Сохранить новую заявку
function save_ticket(array $ticket): void {
    $tickets = get_tickets();
    $tickets[] = $ticket;
    
    file_put_contents(
        get_data_file_path(), 
        json_encode($tickets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}
// Генерация CSRF-токена
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Проверка CSRF-токена
function verify_csrf_token(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

// Обновление статуса заявки по ID
function update_ticket_status(string $id, string $newStatus): bool {
    $tickets = get_tickets();
    $updated = false;

    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket['status'] = $newStatus;
            $updated = true;
            break;
        }
    }
    unset($ticket);

    if ($updated) {
        file_put_contents(
            get_data_file_path(), 
            json_encode($tickets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }

    return $updated;
}