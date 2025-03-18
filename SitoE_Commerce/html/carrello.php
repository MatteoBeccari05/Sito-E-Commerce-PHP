<?php
session_start();

$logged_in = isset($_SESSION['username']); // L'utente è loggato se la variabile di sessione esiste

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

function getCartTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Funzione per aggiungere un prodotto al carrello
function addToCart($id, $name, $price, $quantity, $image, $color, $storage) {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $found = false;

    // Controlla se l'articolo esiste già nel carrello
    foreach ($cart as &$item) {
        if ($item['id'] === $id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // Aggiungi l'articolo se non è stato trovato
    if (!$found) {
        $cart[] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image,
            'color' => $color,
            'storage' => $storage
        ];
    }

    // Salva il carrello nella sessione
    $_SESSION['cart'] = $cart;
}

// Funzione per rimuovere un prodotto dal carrello
function removeFromCart($id) {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $cart = array_filter($cart, function($item) use ($id) {
        return $item['id'] !== $id;
    });

    $_SESSION['cart'] = array_values($cart);
}

// Funzione per aggiornare la quantità di un prodotto
function updateQuantity($id, $quantity) {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    foreach ($cart as &$item) {
        if ($item['id'] === $id) {
            $item['quantity'] = $quantity;
            break;
        }
    }
    $_SESSION['cart'] = $cart;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gestione degli aggiornamenti
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'add') {
            addToCart($_POST['id'], $_POST['name'], $_POST['price'], $_POST['quantity'], $_POST['image'], $_POST['color'], $_POST['storage']);
        }

        if ($action === 'remove') {
            removeFromCart($_POST['id']);
        }

        if ($action === 'update') {
            updateQuantity($_POST['id'], $_POST['quantity']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/home.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="../images/logo.png" alt="UT Device Logo" style="height: 50px; margin-right: 5px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="iphone.php">iPhone</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="samsung.php">Samsung</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="carrello.php">Carrello</a>
                </li>
            </ul>
        </div>

        <!-- Pulsanti Registrati e Accedi o Immagine Utente -->
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

<div class="container" id="cart-container" style="margin-top: 100px;">
    <?php if (empty($cart)): ?>
        <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="alert alert-info text-center w-75">
                <h4>Nessun prodotto nel carrello.</h4>
                <p>Al momento il tuo carrello è vuoto. Aggiungi dei prodotti per continuare.</p>
            </div>
        </div>
    <?php else: ?>
        <h2 class="mb-4">Prodotti nel carrello:</h2>
        <?php $total = getCartTotal($cart); ?>
        <?php foreach ($cart as $item): ?>
            <div class="card mb-4 p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <img src="<?= $item['image']; ?>" alt="<?= $item['name']; ?>" class="img-fluid rounded-3">
                    </div>
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4><?= $item['name']; ?></h4>
                                <p><strong>Colore:</strong> <?= $item['color']; ?></p>
                                <p><strong>Capacità:</strong> <?= $item['storage']; ?></p>
                                <p><strong>Prezzo:</strong> €<?= number_format($item['price'], 2); ?></p>
                                <p><strong>Quantità:</strong> <?= $item['quantity']; ?></p>
                            </div>
                            <div>
                                <form action="" method="post">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="id" value="<?= $item['id']; ?>">
                                    <button class="btn btn-danger" type="submit">Rimuovi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-between align-items-center">
            <h4>Totale Carrello: €<?= number_format($total, 2); ?></h4>

            <?php if (isset($_SESSION['username'])): ?>
                <a href="pagamento.php" class="btn btn-success">Procedi con il pagamento</a>
            <?php else: ?>
                <p class="text-danger">Devi essere loggato per procedere con il pagamento.</p>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
