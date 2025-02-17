<?php
$host = "localhost";
$dbname = "oneportal";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Haal dashboardgegevens op
$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM dashboards WHERE id = :id");
$stmt->execute(['id' => $id]);
$dashboard = $stmt->fetch(PDO::FETCH_ASSOC);

// Haal bedrijven op voor de selectielijst
$companyStmt = $pdo->query("SELECT id, name FROM companies");
$companies = $companyStmt->fetchAll(PDO::FETCH_ASSOC);

// Verwerken van bewerken
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'companyid' => htmlspecialchars($_POST['companyid']),
        'name' => htmlspecialchars($_POST['name']),
        'description' => htmlspecialchars($_POST['description']),
        'icon' => htmlspecialchars($_POST['icon']),
        'content' => htmlspecialchars($_POST['content']),
        'orderindex' => htmlspecialchars($_POST['orderindex']),
        'id' => $id
    ];

    // Update SQL-query
    $sql = "UPDATE dashboards SET 
            companyid = :companyid, 
            name = :name, 
            description = :description, 
            icon = :icon, 
            content = :content, 
            orderindex = :orderindex 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute($data);
    if ($success) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Fout bij het bijwerken van het dashboard.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard bewerken</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h2>Dashboard bewerken</h2>
<form method="POST">
    <div class="mb-3">
        <label>Bedrijf:</label>
        <select class="form-select" name="companyid" required>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id'] ?>" 
                    <?= $company['id'] == $dashboard['companyid'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($company['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Naam:</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($dashboard['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Beschrijving:</label>
        <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($dashboard['description']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Icoon:</label>
        <input type="text" name="icon" class="form-control" value="<?= htmlspecialchars($dashboard['icon']) ?>">
    </div>
    <div class="mb-3">
        <label>Inhoud:</label>
        <textarea name="content" class="form-control" rows="4" required><?= htmlspecialchars($dashboard['content']) ?></textarea>
    </div>
    <div class="mb-3">
        <label>Volgorde-index:</label>
        <input type="number" name="orderindex" class="form-control" value="<?= htmlspecialchars($dashboard['orderindex']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Opslaan</button>
</form>
</body>
</html>
