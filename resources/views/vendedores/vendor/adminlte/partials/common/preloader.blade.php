@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">

    @hasSection('preloader')

        {{-- Use a custom preloader content --}}
        @yield('preloader')

    @else
    <style>
        .preloader {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            width: 100%;
            background: linear-gradient(90deg, #000, #021e81) !important;
        }
    </style>
        <div class="preloader">
            <l-helix
                size="90"
                speed="2.5"
                color="#3a71e0"
            >
            </l-helix>
        </div>

        <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/helix.js"></script>
    @endif

</div>
