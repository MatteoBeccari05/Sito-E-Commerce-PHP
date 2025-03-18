<?php
session_start();
$logged_in = isset($_SESSION['username']); // L'utente è loggato se la variabile di sessione esiste
// Carica il carrello dalla sessione
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Funzione per calcolare il totale
function getCartTotal($cart)
{
    $total = 0;
    foreach ($cart as $item)
    {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Imposta il titolo della pagina
$jsonData = json_decode(file_get_contents('../json/pagamento.json'), true);
$title = $jsonData['title'];
$formFields = $jsonData['body']['form']['fields'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>

    <!-- Carica il CSS di Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/home.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="../images/logo.png" alt="UT Device Logo" style="height: 50px; margin-right: 5px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
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

<!-- Titolo Pagina -->
<h1 class="mt-5 pt-5 text-center"><?php echo $jsonData['body']['h1']; ?></h1>

<!-- Form di Pagamento e Riepilogo Ordine -->
<div class="container my-5 d-flex justify-content-between">
    <!-- Form di Pagamento -->
    <div class="col-6">
        <form id="payment-form">
            <h4 class="text-center"><strong>Informazioni di fatturazione</strong></h4>
            <div class="row">
                <?php foreach (array_slice($formFields, 0, 6) as $field): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="<?php echo $field['id']; ?>" class="form-label"><?php echo $field['label']; ?></label>
                            <?php if ($field['type'] === 'select'): ?>
                                <select class="form-control" id="<?php echo $field['id']; ?>" required>
                                    <?php foreach ($field['options'] as $option): ?>
                                        <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?php echo $field['type']; ?>" class="form-control" id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['placeholder']; ?>" required>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr>

            <h4 class="text-center"><strong>Informazioni di Pagamento</strong></h4>
            <div class="row">
                <?php foreach (array_slice($formFields, 6) as $field): ?>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="<?php echo $field['id']; ?>" class="form-label"><?php echo $field['label']; ?></label>
                            <?php if ($field['type'] === 'select'): ?>
                                <select class="form-control" id="<?php echo $field['id']; ?>" required>
                                    <?php foreach ($field['options'] as $option): ?>
                                        <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="<?php echo $field['type']; ?>" class="form-control" id="<?php echo $field['id']; ?>" placeholder="<?php echo $field['placeholder']; ?>" required>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary" id="pay-button">Paga</button>
        </form>
    </div>

    <!-- Riepilogo Ordine -->
    <div class="col-4 bg-light p-4 border rounded">
        <h4 class="text-center">Riepilogo ordine</h4>
        <ul id="order-summary">
            <?php
            $total = getCartTotal($cart); // Calcola il totale del carrello
            if (count($cart) > 0)
            {
                foreach ($cart as $product)
                {
                    $quantity = $product['quantity'] > 0 ? $product['quantity'] : 1;
                    $price = isset($product['price']) && is_numeric($product['price']) ? $product['price'] : 0;
                    echo "<li><strong>{$product['name']}</strong> - {$quantity} x €" . number_format($price, 2) . "</li>";
                }
            }
            else
            {
                echo "<li><em>Carrello vuoto</em></li>";
            }
            ?>
        </ul>
        <hr>
        <h5 class="text-center"><strong>Totale: €<span id="total-price"><?php echo number_format($total, 2); ?></span></strong></h5>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 UT Device</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
