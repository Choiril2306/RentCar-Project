document.getElementById('rentalForm').addEventListener('submit', function(event){
    var durationInput = document.getElementById('duration').value;
    var quantityInput = document.getElementById('quantity').value;

    var durationError = document.getElementById('duration-error');
    var quantityError = document.getElementById('quantity-error');

    var isValid = true;

    durationError.textContent = '';
    quantityError.textContent = '';

    var duration = parseInt(durationInput);
    if (isNaN(duration) || duration <= 0 || duration > 30) {
        durationError.textContent = 'Durasi harus di antara 1 hingga 30 hari.';
        isValid = false;
    }

    var quantity = parseInt(quantityInput);
    if (isNaN(quantity) || quantity <= 0) {
        quantityError.textContent = 'Jumlah kendaraan harus lebih besar dari 0.';
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
});