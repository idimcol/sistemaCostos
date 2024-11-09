<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-header {{ $item['class'] ?? '' }}">

    {{ is_string($item) ? $item : $item['header'] }}

</li>
<style>
    .nav-header {
        background: #0026a4f7 !important;
        color: #fff !important;
    }
</style>
