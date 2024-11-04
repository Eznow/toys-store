document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-form input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.parentElement.style.color = '#007bff'; // Đổi màu chữ khi chọn
            } else {
                this.parentElement.style.color = '#000'; // Đổi lại màu khi bỏ chọn
            }
        });
    });
});