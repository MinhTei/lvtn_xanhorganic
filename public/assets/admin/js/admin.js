(function () {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('adminSidebarOverlay');
    var openBtn = document.getElementById('adminSidebarToggle');
    var closeBtn = document.getElementById('adminSidebarClose');

    if (!sidebar || !overlay || !openBtn) {
        return;
    }

    function setOpen(isOpen) {
        sidebar.classList.toggle('is-open', isOpen);
        overlay.classList.toggle('is-visible', isOpen);
        document.body.classList.toggle('admin-sidebar-open', isOpen);
        openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        overlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    }

    function openSidebar() {
        setOpen(true);
    }

    function closeSidebar() {
        setOpen(false);
    }

    openBtn.addEventListener('click', function () {
        if (sidebar.classList.contains('is-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }

    overlay.addEventListener('click', closeSidebar);

    sidebar.querySelectorAll('.nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.matchMedia('(max-width: 991.98px)').matches) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });

    window.addEventListener('resize', function () {
        if (window.matchMedia('(min-width: 992px)').matches) {
            closeSidebar();
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.table-role-permissions tbody tr');
        
        rows.forEach(row => {
            const manageCheckbox = row.querySelector('td:nth-child(1) input[type="checkbox"]');
            const childCheckboxes = row.querySelectorAll('td:not(:nth-child(1)) input[type="checkbox"]');
            
            if (!manageCheckbox || childCheckboxes.length === 0) return;

            manageCheckbox.addEventListener('change', function() {
                childCheckboxes.forEach(cb => {
                    cb.checked = manageCheckbox.checked;
                });
            });

            childCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
    
                    const anyChecked = Array.from(childCheckboxes).some(c => c.checked);
                    manageCheckbox.checked = anyChecked;
                });
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const ingredientSelect = document.getElementById("ingredients_select");
        if (ingredientSelect) {
            new TomSelect(ingredientSelect, {
                plugins: ['remove_button'],
                placeholder: 'Gõ để tìm kiếm nguyên liệu...',
                searchField: ['text'],
                maxOptions: 50,
            });
        }
    });

    // Validate file size for product images
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('product_images_input');
        if (!input) return; // Chỉ chạy nếu đang ở trang có form upload ảnh

        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                let totalSize = 0;
                for (let i = 0; i < input.files.length; i++) {
                    totalSize += input.files[i].size;
                }
                if (totalSize > 7340032) {
                    e.preventDefault(); // Chặn không cho gửi lên máy chủ
                    input.value = ''; // Xóa trắng ô input bắt người dùng chọn lại
                    
                    // Hiển thị Alert cơ bản
                    let oldAlert = document.getElementById('js-error-alert');
                    if (oldAlert) oldAlert.remove();

                    let alertHTML = `
                        <div id="js-error-alert" class="alert alert-danger py-2 alert-dismissible fade show" role="alert">
                            Tổng dung lượng ảnh đã vượt quá mức an toàn (7MB). Vui lòng chọn ít ảnh hơn!
                            <button type="button" class="btn-close" style="padding: 0.7rem 1rem;" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    let mainContainer = document.querySelector('.admin-main main');
                    if (mainContainer) {
                        mainContainer.insertAdjacentHTML('afterbegin', alertHTML);
                    }
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        }
    });

    // Handle removing existing product images
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.product-img-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var wrap = btn.closest('.product-img-thumb');
                var id = btn.getAttribute('data-id');
                var holder = document.getElementById('product-delete-images');
                if (holder && id) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_images[]';
                    input.value = id;
                    holder.appendChild(input);
                }
                if (wrap) wrap.remove();
            });
        });
    });
})();