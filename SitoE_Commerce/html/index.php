<?php
session_start();

$logged_in = isset($_SESSION['username']); // L'utente è loggato se la variabile di sessione esiste

// Carica il contenuto del file JSON
$json_data = file_get_contents('../json/index.json');

// Decodifica il JSON in un array associativo
$data = json_decode($json_data, true);
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>

    <?php foreach ($data['head']['meta'] as $meta): ?>
        <meta <?php echo isset($meta['name']) ? 'name="' . $meta['name'] . '" content="' . $meta['content'] . '"' : 'charset="' . $meta['charset'] . '"'; ?>>
    <?php endforeach; ?>

    <?php foreach ($data['head']['links'] as $link): ?>
        <link rel="<?php echo $link['rel']; ?>" href="<?php echo $link['href']; ?>"
            <?php echo isset($link['integrity']) ? 'integrity="' . $link['integrity'] . '"' : ''; ?>
            <?php echo isset($link['crossorigin']) ? 'crossorigin="' . $link['crossorigin'] . '"' : ''; ?>>
    <?php endforeach; ?>
</head>

<body>

<!-- Navbar -->
<nav class="<?php echo $data['body']['nav']['class']; ?>">
    <div class="container-fluid">
        <!-- Logo -->
        <a href="<?php echo $data['body']['nav']['container']['logo']['href']; ?>" class="navbar-brand">
            <img src="<?php echo $data['body']['nav']['container']['logo']['img']['src']; ?>"
                 alt="<?php echo $data['body']['nav']['container']['logo']['img']['alt']; ?>"
                 style="<?php echo $data['body']['nav']['container']['logo']['img']['style']; ?>">
        </a>

        <!-- Button per la navigazione mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menù di navigazione -->
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php foreach ($data['body']['nav']['container']['collapse']['ul']['items'] as $item): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $item['class']; ?>" href="<?php echo $item['href']; ?>"
                            <?php echo isset($item['aria-current']) ? 'aria-current="' . $item['aria-current'] . '"' : ''; ?>>
                            <?php echo $item['text']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
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

<!-- Main Content -->
<h1 class="text-center"><?php echo $data['body']['h1']; ?></h1>
<p class="<?php echo $data['body']['p']['class']; ?>"><?php echo $data['body']['p']['text']; ?></p>

<!-- Carousel -->
<div id="<?php echo $data['body']['carousel']['id']; ?>" class="<?php echo $data['body']['carousel']['class']; ?>"
     data-bs-ride="<?php echo $data['body']['carousel']['data-bs-ride']; ?>">
    <div class="carousel-indicators">
        <?php foreach ($data['body']['carousel']['indicators'] as $index => $indicator): ?>
            <button type="button" data-bs-target="#carousel" data-bs-slide-to="<?php echo $indicator['data-bs-slide-to']; ?>"
                    class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="true"
                    aria-label="Slide <?php echo $indicator['data-bs-slide-to'] + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
        <?php foreach ($data['body']['carousel']['items'] as $index => $item): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                <img src="<?php echo $item['src']; ?>" class="d-block w-100" alt="<?php echo $item['alt']; ?>">
            </div>
        <?php endforeach; ?>
    </div>

    <button class="<?php echo $data['body']['carousel']['prev_button']['class']; ?>" type="button"
            data-bs-target="#carousel" data-bs-slide="prev">
        <span class="<?php echo $data['body']['carousel']['prev_button']['icon_class']; ?>" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo $data['body']['carousel']['prev_button']['aria_label']; ?></span>
    </button>
    <button class="<?php echo $data['body']['carousel']['next_button']['class']; ?>" type="button"
            data-bs-target="#carousel" data-bs-slide="next">
        <span class="<?php echo $data['body']['carousel']['next_button']['icon_class']; ?>" aria-hidden="true"></span>
        <span class="visually-hidden"><?php echo $data['body']['carousel']['next_button']['aria_label']; ?></span>
    </button>
</div>

<!-- Card deck -->
<div class="container mt-5">
    <h2><?php echo $data['body']['cards']['title']; ?></h2>
    <div class="card-deck">
        <?php foreach ($data['body']['cards']['items'] as $card): ?>
            <div class="<?php echo $card['class']; ?>" style="<?php echo $card['style']; ?>">
                <img src="<?php echo $card['img']['src']; ?>" class="<?php echo $card['img']['class']; ?>"
                     alt="<?php echo $card['img']['alt']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $card['body']['h5']; ?></h5>
                    <p class="card-text"><?php echo $card['body']['p']; ?></p>
                    <a href="<?php echo $card['body']['a']['href']; ?>" class="<?php echo $card['body']['a']['class']; ?>"><?php echo $card['body']['a']['text']; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 UT Device</p>
</footer>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
