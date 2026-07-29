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
})();
