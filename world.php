<?php
$host = 'localhost';
$username = 'lab5_user';
$password = getenv('WORLD_DB_PASSWORD');
$dbname = 'world';

if ($password === false && is_readable(__DIR__ . '/world.local.php')) {
    $password = require __DIR__ . '/world.local.php';
}

header('Content-Type: text/html; charset=UTF-8');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, (string) $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query('SELECT name, head_of_state FROM countries ORDER BY name');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    http_response_code(500);
    exit('<p>Could not load the world database.</p>');
}
?>
<ul>
<?php foreach ($results as $row): ?>
    <li><?= htmlspecialchars($row['name'] . ' is ruled by ' . $row['head_of_state'], ENT_QUOTES, 'UTF-8') ?></li>
<?php endforeach; ?>
</ul>
