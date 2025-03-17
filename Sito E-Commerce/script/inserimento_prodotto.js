document.addEventListener("DOMContentLoaded", function () {
    // Gestisci il cambiamento di colore
    const colorSelectors = document.querySelectorAll('.color-select');
    colorSelectors.forEach(function(select) {
        select.addEventListener('change', function() {
            const productId = select.id.split('-')[2];  // Ottieni l'ID del prodotto
            const selectedColor = select.value;
            const images = JSON.parse(select.getAttribute('data-images')); // Ottieni le immagini dal PHP

            // Cambia l'immagine in base al colore selezionato
            const imageElement = document.getElementById('product-image-' + productId);
            const newImage = images[selectedColor.toLowerCase()] || images['default'];
            imageElement.src = newImage;

            // Cambia anche il prezzo in base alla memoria
            const storageSelect = document.getElementById('storage-select-' + productId);
            const selectedStorage = storageSelect.value;
            updatePrice(productId, selectedStorage, selectedColor);
        });
    });

    // Gestisci il cambiamento della memoria
    const storageSelectors = document.querySelectorAll('.storage-select');
    storageSelectors.forEach(function(select) {
        select.addEventListener('change', function() {
            const productId = select.id.split('-')[2];  // Ottieni l'ID del prodotto
            const selectedStorage = select.value;

            // Cambia il prezzo in base alla memoria selezionata
            const colorSelect = document.getElementById('color-select-' + productId);
            const selectedColor = colorSelect.value;

            updatePrice(productId, selectedStorage, selectedColor);
        });
    });

    // Funzione per aggiornare il prezzo in base alla memoria
    function updatePrice(productId, storage, color) {
        const priceElement = document.getElementById('product-price-' + productId);
        const basePrice = parseFloat(priceElement.getAttribute('data-base-price')); // Ottieni il prezzo di base
        let updatedPrice = basePrice;

        switch (storage) {
            case "256GB":
                updatedPrice += 100;
                break;
            case "512GB":
                updatedPrice += 200;
                break;
            case "1TB":
                updatedPrice += 300;
                break;
            case "2TB":
                updatedPrice += 450;
                break;
            default:
                updatedPrice = basePrice;
                break;
        }

        // Mostra il nuovo prezzo
        priceElement.textContent = "€" + updatedPrice.toFixed(2);
    }
});
