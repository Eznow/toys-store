document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filter-form');
    const productList = document.getElementById('product-list');
    const headerSearchForm = document.getElementById('header-search-form');
    const searchInput = document.getElementById('header-search-input');

    // Kiểm tra nếu đang ở trang Home
    const isHomePage = window.location.pathname === '/';

    // Nếu có từ khóa tìm kiếm trong URL, đặt vào ô input
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('query');
    if (searchQuery) {
        searchInput.value = searchQuery;
    }

    headerSearchForm.addEventListener('submit', function (e) {
        const searchQuery = searchInput.value.trim();

        if (isHomePage) {
            e.preventDefault(); // Ngăn tải lại trang nếu ở trang Home

            if (searchQuery) {
                fetch(`/search?query=${encodeURIComponent(searchQuery)}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    productList.innerHTML = data.products;
                })
                .catch(error => console.error('Error fetching products:', error));
            }
        } else {
            // Nếu không ở trang Home, chuyển hướng đến Home với từ khóa tìm kiếm
            headerSearchForm.action = `/?query=${encodeURIComponent(searchQuery)}`;
        }
    });

    function fetchFilteredProducts() {
        const formData = new FormData(filterForm);

        // Lấy từ khóa tìm kiếm nếu có
        const searchQuery = searchInput.value.trim();
        if (searchQuery) {
            formData.append('search', searchQuery);
        }

        fetch(filterForm.getAttribute('data-url'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error(`HTTP status ${response.status}`);
            return response.json();
        })
        .then(data => {
            productList.innerHTML = data.products;
        })
        .catch(error => console.error('Error fetching products:', error));
    }

    // Để lưu trạng thái checked của radio
    let lastCheckedRadio = null;

    // Toggle checked state for radio buttons
    document.querySelectorAll('input[type="radio"]').forEach((radio) => {
        radio.addEventListener('click', function () {
            if (lastCheckedRadio === radio) {
                radio.checked = false;
                lastCheckedRadio = null;
            } else {
                lastCheckedRadio = radio;
            }
            fetchFilteredProducts();
        });
    });

    // Lắng nghe sự kiện thay đổi trên các input khác trong form
    filterForm.addEventListener('change', fetchFilteredProducts);

    // Lắng nghe sự kiện tìm kiếm trên thanh tìm kiếm
    searchInput.addEventListener('input', fetchFilteredProducts);
});
