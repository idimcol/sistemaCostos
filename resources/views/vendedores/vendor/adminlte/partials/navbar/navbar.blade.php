<nav class="main-header navbar text-white
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav text-white">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        <a class="nav-link" data-widget="darkmode" href="#" role="button">
            <i id="theme-icon" class="fas fa-moon"></i> <!-- Ícono de luna por defecto -->
        </a>

        <div class="">
            {{-- <button type="button" class="btn btn-info">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button type="button" class="btn btn-info">
                <i class="fa-solid fa-arrow-right"></i>
            </button> --}}
            <button onclick="location.reload()" class="btn btn-info">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto text-white">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        nav.main-header {
            color: #fff !important;
            background: #58a4f0 !important;
        }
        body.dark-mode {
            background-color: #343a40;
            color: #c2c7d0;
        }

        /* Estilo de icono para modo claro/oscuro */
        .nav-item .nav-link .fas.fa-sun {
            color: #f39c12;
        }
        .nav-item .nav-link .fas.fa-moon {
            color: #adb5bd;
        }
    </style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggle = document.querySelector('[data-widget="darkmode"]');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;

        // Verifica el tema guardado en localStorage
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            body.classList.add(currentTheme);
            if (currentTheme === 'dark-mode') {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        // Cambia entre modo oscuro y claro al hacer clic
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                localStorage.setItem('theme', 'dark-mode');
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                localStorage.setItem('theme', 'light-mode');
            }
        });
    });
</script>
