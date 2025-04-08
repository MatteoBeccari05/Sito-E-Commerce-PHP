document.addEventListener("DOMContentLoaded", function ()
{
    const colorSelectors = document.querySelectorAll('.color-select');
    colorSelectors.forEach(function(select) {
        select.addEventListener('change', function() {
            const productId = select.id.split('-')[2];
            const selectedColor = select.value;
            const images = JSON.parse(select.getAttribute('data-images'));

            const imageElement = document.getElementById('product-image-' + productId);
            const newImage = images[selectedColor.toLowerCase()] || images['default'];
            imageElement.src = newImage;

            const storageSelect = document.getElementById('storage-select-' + productId);
            const selectedStorage = storageSelect.value;
            updatePrice(productId, selectedStorage, selectedColor);
        });
    });
    const storageSelectors = document.querySelectorAll('.storage-select');
    storageSelectors.forEach(function(select) {
        select.addEventListener('change', function() {
            const productId = select.id.split('-')[2];
            const selectedStorage = select.value;
            const colorSelect = document.getElementById('color-select-' + productId);
            const selectedColor = colorSelect.value;
            updatePrice(productId, selectedStorage, selectedColor);
        });
    });

    function updatePrice(productId, storage, color) {
        const priceElement = document.getElementById('product-price-' + productId);
        const basePrice = parseFloat(priceElement.getAttribute('data-base-price'));
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
        priceElement.textContent = "€" + updatedPrice.toFixed(2);
    }
});
