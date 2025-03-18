<?php
session_start();
// Carica il file JSON
$jsonData = file_get_contents('../json/samsung.json'); // Usa il percorso corretto del file JSON
$data = json_decode($jsonData, true);
$logged_in = isset($_SESSION['username']); // L'utente è loggato se la variabile di sessione esiste


// Estrai le informazioni necessarie
$title = $data['title'];
$head = $data['head'];
$body = $data['body'];
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>

    <?php
    // Aggiungi i meta tag
    foreach ($head['meta'] as $metaTag)
    {
        echo "<meta ";
        foreach ($metaTag as $key => $value)
        {
            echo "$key=\"$value\" ";
        }
        echo ">\n";
    }

    // Aggiungi i link dei CSS
    foreach ($head['links'] as $link)
    {
        echo "<link ";
        foreach ($link as $key => $value)
        {
            echo "$key=\"$value\" ";
        }
        echo ">\n";
    }
    ?>
</head>

<body>

<nav class="<?php echo $body['nav']['class']; ?>">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $body['nav']['container']['logo']['href']; ?>">
            <img src="<?php echo $body['nav']['container']['logo']['img']['src']; ?>"
                 alt="<?php echo $body['nav']['container']['logo']['img']['alt']; ?>"
                 style="<?php echo $body['nav']['container']['logo']['img']['style']; ?>">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php foreach ($body['nav']['container']['collapse']['ul']['items'] as $item) : ?>
                    <li class="nav-item">
                        <a class="<?php echo $item['class']; ?>" href="<?php echo $item['href']; ?>"
                            <?php echo isset($item['aria-current']) ? 'aria-current="page"' : ''; ?>>
                            <?php echo $item['text']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
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

<div class="container mt-5 pt-5">

    <?php
    // Cicla su tutte le sezioni dei prodotti
    foreach ($body['cardDecks'] as $deck)
    {
        echo "<h2 class='text-center'>" . $deck['title'] . "</h2>";
        echo "<div class=\"row d-flex justify-content-center\">"; // Row centrata
        foreach ($deck['cards'] as $card)
        {
            echo "<div class=\"col-md-3 mb-4 d-flex justify-content-center\">"; // Card centrata
            echo "<div class=\"card\" style=\"width: 20rem;\">";
            echo "<img class=\"card-img-top\" src=\"" . $card['img']['src'] . "\" alt=\"" . $card['img']['alt'] . "\">";
            echo "<div class=\"card-body\">";
            echo "<h5 class=\"card-title\">" . $card['body']['h5'] . "</h5>";
            echo "<p class=\"card-text\">" . $card['body']['p'] . "</p>";
            echo "<a href=\"" . $card['body']['a']['href'] . "\" class=\"" . $card['body']['a']['class'] . "\">" . $card['body']['a']['text'] . "</a>";
            echo "<p class=\"price\"><strong>" . $card['body']['price'] . "</strong></p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    }
    ?>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 UT Device</p>
</footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
