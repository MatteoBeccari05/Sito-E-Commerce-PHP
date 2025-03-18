<?php
session_start();
$logged_in = isset($_SESSION['username']); // L'utente è loggato se la variabile di sessione esiste
// Configurazione del DB
$config = require '../connessione_db/db_config.php';
require '../connessione_db/DB_Connect.php';
require_once '../connessione_db/functions.php';

$db = DataBase_Connect::getDB($config);

// Recupera gli ID dei prodotti dalla URL
$productIds = isset($_GET['id']) ? $_GET['id'] : '';
if ($productIds)
{
    $idsArray = explode(',', $productIds);  // Crea un array di ID separati da virgola

    // Prepara la query per ottenere i dati dei prodotti
    $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
    $stmt = $db->prepare("SELECT * FROM prodotti WHERE id IN ($placeholders)");
    $stmt->execute($idsArray);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else
{
    echo "<p>Nessun prodotto selezionato.</p>";
    exit;
}

// Funzione per calcolare il prezzo aggiornato in base alla memoria
function calculatePrice($product, $storage)
{
    $basePrice = $product['price'];
    switch ($storage)
    {
        case "256GB":
            return $basePrice + 100;
        case "512GB":
            return $basePrice + 200;
        case "1TB":
            return $basePrice + 300;
        case "2TB":
            return $basePrice + 450;
        default:
            return $basePrice;
    }
}

// Funzione per ottenere l'immagine in base al colore
function getProductImage($images, $color)
{
    return $images[strtolower($color)] ?? $images['default'];  // Colore selezionato, oppure l'immagine di default
}

// Gestisci la selezione del colore e della memoria tramite POST
$selectedColor = $_POST['color'] ?? '';
$selectedStorage = $_POST['storage'] ?? '128GB';  // Default storage

// Aggiungi al carrello se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart')
{
    $productId = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Trova il prodotto selezionato
    $product = array_filter($products, function($p) use ($productId) {
        return $p['id'] == $productId;
    });

    $product = array_values($product)[0] ?? null;

    if ($product)
    {
        $productName = $product['name'];
        $productPrice = calculatePrice($product, $selectedStorage);
        $productImage = getProductImage(json_decode($product['images'], true), $selectedColor);
        $productColor = $selectedColor;
        $productStorage = $selectedStorage;

        // Aggiungi il prodotto al carrello (sessione)
        if (!isset($_SESSION['cart']))
        {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item)
        {
            if ($item['id'] === $productId)
            {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found)
        {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity,
                'image' => $productImage,
                'color' => $productColor,
                'storage' => $productStorage
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettagli Prodotto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="../images/logo.png" alt="UT Device Logo" style="height: 50px; margin-right: 5px;"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="iphone.php">iPhone</a></li>
                <li class="nav-item"><a class="nav-link" href="samsung.php">Samsung</a></li>
                <li class="nav-item"><a class="nav-link" href="carrello.php">Carrello</a></li>
            </ul>
        </div>
        <div class="d-flex">
            <?php if ($logged_in): ?>
                <!-- Mostra l'immagine dell'utente e il nome/cognome -->
                <div class="user-info d-flex align-items-center">
                    <img src="../images/persona.jpg" alt="Immagine Utente" class="rounded-circle" style="width: 40px; height: 40px;">
                    <span class="ms-2" style="color: white">
                        <?php
                        // Verifica se le variabili di sessione 'nome' e 'cognome' sono definite
                        $nome = isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Nome non disponibile';
                        $cognome = isset($_SESSION['cognome']) ? $_SESSION['cognome'] : 'Cognome non disponibile';
                        echo $nome . ' ' . $cognome;
                        ?>
                    </span>
                    <a href="../utenti/logout.php" class="btn btn-outline-danger ms-3">Esci</a>
                </div>
            <?php else: ?>
                <!-- Mostra i tasti Accedi e Registrati -->
                <a href="../utenti/registrazione.html" class="btn btn-outline-primary me-2">Registrati</a>
                <a href="../utenti/accedi.html" class="btn btn-outline-success">Accedi</a>
            <?php endif; ?>
        </div>
    </div>

</nav>

<div class="container mt-5 pt-5" id="product-details">
    <?php foreach ($products as $product): ?>
        <?php
        // Decodifica le immagini e colori dal JSON
        $images = json_decode($product['images'], true);
        $colors = json_decode($product['colors'], true);
        $defaultColor = strtolower($colors[0]);
        $selectedColor = $_POST['color'] ?? $defaultColor;
        $selectedStorage = $_POST['storage'] ?? '64GB';  // Default storage
        $updatedPrice = calculatePrice($product, $selectedStorage);
        ?>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            <input type="hidden" name="action" value="add_to_cart">
            <div class="row product" id="product-<?= $product['id'] ?>" style="margin-bottom: 30px;">
                <div class="col-md-6">
                    <img id="product-image-<?= $product['id'] ?>" src="<?= getProductImage($images, $selectedColor) ?>" alt="<?= $product['name'] ?>" class="img-fluid rounded product-image">
                </div>
                <div class="col-md-6">
                    <h2 class="product-title"><?= $product['name'] ?></h2>
                    <p><strong>Processore:</strong> <?= $product['processor'] ?></p>
                    <p><strong>Display:</strong> <?= $product['display'] ?></p>
                    <p class="product-description"><strong>Descrizione:</strong> <?= $product['description'] ?></p>

                    <div class="form-group">
                        <label for="color-select-<?= $product['id'] ?>">Scegli il colore:</label>
                        <select name="color" id="color-select-<?= $product['id'] ?>" class="form-select color-select" data-images="<?= htmlspecialchars(json_encode($images)) ?>">
                            <?php foreach ($colors as $color): ?>
                                <option value="<?= strtolower($color) ?>" <?= ($selectedColor === strtolower($color)) ? 'selected' : '' ?>><?= $color ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <br>

                    <div class="form-group">
                        <label for="storage-select-<?= $product['id'] ?>">Scegli la capacità:</label>
                        <select name="storage" id="storage-select-<?= $product['id'] ?>" class="form-select storage-select">
                            <?php
                            $storageOptions = json_decode($product['storageOptions'], true);
                            foreach ($storageOptions as $storage): ?>
                                <option value="<?= $storage ?>" <?= ($selectedStorage === $storage) ? 'selected' : '' ?>><?= $storage ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="product-price-container">
                        <p class="product-price" id="product-price-<?= $product['id'] ?>" data-base-price="<?= $product['price'] ?>">€<?= $updatedPrice ?></p>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantità:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1">
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
                </div>
            </div>
            <hr style="border-top: 1px solid #ccc; margin: 20px 0;">
        </form>
    <?php endforeach; ?>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 UT Device</p>
</footer>

<script src="../script/inserimento_prodotto.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>