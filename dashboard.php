<?php
// Databaseverbinding
$host = "localhost";
$dbname = "oneportal";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<p class='error'>Databaseverbinding mislukt: " . $e->getMessage() . "</p>");
}

// Zoekterm ophalen
$searchTerm = $_GET['searchTerm'] ?? '';

// Zoekopdracht opstellen
$sql = "SELECT dashboards.*, companies.name AS company_name 
        FROM dashboards 
        LEFT JOIN companies ON dashboards.companyid = companies.id 
        WHERE (companies.name LIKE :searchTerm OR dashboards.description LIKE :searchTerm OR :searchTerm = '')";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':searchTerm' => '%' . $searchTerm . '%',
]);
$dashboards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboards</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2 class="mb-4">Dashboards Overzicht</h2>

<!-- Zoekformulier -->
<form method="GET" class="row g-3 mb-4">
    <div class="col-md-10">
        <input type="text" name="searchTerm" class="form-control" placeholder="Zoek op bedrijfsnaam of beschrijving" value="<?= htmlspecialchars($searchTerm) ?>">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Zoeken</button>
    </div>
</form>

<!-- Tabel met dashboards -->
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Naam</th>
            <th>Beschrijving</th>
            <th>Bedrijf</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($dashboards) > 0): ?>
            <?php foreach ($dashboards as $dashboard): ?>
                <tr>
                    <td><?= htmlspecialchars($dashboard['name']) ?></td>
                    <td><?= htmlspecialchars($dashboard['description']) ?></td>
                    <td><?= htmlspecialchars($dashboard['company_name']) ?></td>
                    <td>
                        <a href="edit_dashboard.php?id=<?= $dashboard['id'] ?>" class="btn btn-sm btn-warning">Bewerken</a>
                        <a href="delete.php?id=<?= $dashboard['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit dashboard wilt verwijderen?');">Verwijderen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center">Geen dashboards gevonden.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Knop om nieuw dashboard toe te voegen -->
<a href="test.php" class="btn btn-success mt-3">Nieuw Dashboard Toevoegen</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>