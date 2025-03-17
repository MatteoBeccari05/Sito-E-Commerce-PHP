<?php
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

<!-- Footer -->
<footer class="<?php echo $data['body']['footer']['class']; ?>">
    <p><?php echo $data['body']['footer']['p']; ?></p>
</footer>

<!-- Scripts -->
<?php foreach ($data['scripts'] as $script): ?>
    <script src="<?php echo $script['src']; ?>"
        <?php echo isset($script['integrity']) ? 'integrity="' . $script['integrity'] . '"' : ''; ?>
        <?php echo isset($script['crossorigin']) ? 'crossorigin="' . $script['crossorigin'] . '"' : ''; ?>></script>
<?php endforeach; ?>
</body>

</html>
