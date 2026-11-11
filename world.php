<?php
$host = 'localhost';
$username = 'lab5_user';
$password = getenv('WORLD_DB_PASSWORD');
$dbname = 'world';
$country = $_GET['country'] ?? '';

if (!is_string($country)) {
    http_response_code(400);
    exit('<p>Enter a country name.</p>');
}

$country = trim($country);

if ($password === false && is_readable(__DIR__ . '/world.local.php')) {
    $password = require __DIR__ . '/world.local.php';
}

header('Content-Type: text/html; charset=UTF-8');

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, (string) $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare('SELECT name, continent, independence_year, head_of_state FROM countries WHERE name LIKE :country ORDER BY name');
    $stmt->execute(['country' => '%' . $country . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    http_response_code(500);
    exit('<p>Could not load the world database.</p>');
}
function escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<?php if (!$results): ?>
    <p>No matching countries found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Continent</th>
                <th scope="col">Independence Year</th>
                <th scope="col">Head of State</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= escape($row['name']) ?></td>
                    <td><?= escape($row['continent']) ?></td>
                    <td><?= escape($row['independence_year']) ?></td>
                    <td><?= escape($row['head_of_state']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
