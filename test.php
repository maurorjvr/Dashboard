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

// Bedrijven ophalen
$stmt = $pdo->query("SELECT id, name FROM companies");
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Functie om een 32-karakter hexadecimale GUID te genereren
function generateHexGUID() {
    return bin2hex(random_bytes(16));
}

// Formuliervelden definiëren (companyid toegevoegd als selectielijst)
$fields = [
    'companyid' => ['label' => 'Bedrijf', 'type' => 'select', 'options' => $companies],
    'name' => ['label' => 'Naam', 'type' => 'text'],
    'description' => ['label' => 'Beschrijving', 'type' => 'text'],
    'icon' => ['label' => 'Icoon', 'type' => 'text'],
    'content' => ['label' => 'Inhoud', 'type' => 'textarea'],
    'orderindex' => ['label' => 'Volgorde-index', 'type' => 'number']
];

// Formulierverwerking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [];
    foreach ($fields as $key => $info) {
        $data[$key] = htmlspecialchars($_POST[$key] ?? '');
    }

    // GUID genereren
    $data['guid'] = generateHexGUID();

    // Invoegen in de dashboards-tabel
    $sql = "INSERT INTO dashboards (companyid, name, description, icon, content, orderindex, guid) 
            VALUES (:companyid, :name, :description, :icon, :content, :orderindex, :guid)";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute($data);

    // Redirect naar dashboard.php bij succes
    if ($success) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p class='error'>Fout bij het toevoegen van het dashboard.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Toevoegen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2 class="mb-4">Nieuw Dashboard Toevoegen</h2>
<form method="POST" class="needs-validation" novalidate>
    <?php foreach ($fields as $name => $info): ?>
        <div class="mb-3">
            <label class="form-label"> <?= $info['label'] ?>:</label>
            <?php if ($info['type'] === 'select'): ?>
                <select class="form-select" name="<?= $name ?>" required>
                    <?php foreach ($info['options'] as $option): ?>
                        <option value="<?= $option['id'] ?>"> <?= htmlspecialchars($option['name']) ?> </option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($info['type'] === 'textarea'): ?>
                <textarea class="form-control" name="<?= $name ?>" rows="4" required></textarea>
            <?php else: ?>
                <input class="form-control" type="<?= $info['type'] ?>" name="<?= $name ?>" required>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Opslaan</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
