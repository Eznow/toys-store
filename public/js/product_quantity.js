document.addEventListener('DOMContentLoaded', function() {
    var decreaseBtn = document.getElementById('decrease-qty');
    var increaseBtn = document.getElementById('increase-qty');
    var qtyInput = document.getElementById('quantity');

    // Kiểm tra xem các phần tử có tồn tại không
    if (decreaseBtn && increaseBtn && qtyInput) {
        decreaseBtn.addEventListener('click', function() {
            if (qtyInput.value > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
            }
        });

        increaseBtn.addEventListener('click', function() {
            qtyInput.value = parseInt(qtyInput.value) + 1;
        });
    }
});
