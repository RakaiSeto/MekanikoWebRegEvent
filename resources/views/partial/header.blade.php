<header class="dark-bb">
    @if(request()->session()->get('token') !== null and request()->session()->get('token') !== '')
    <nav class="navbar navbar-expand-lg px-3" style="justify-content: space-between">
        <a href="/" class="text-decoration-none">
            <h3>Mekaniko Presale</h3>
        </a>
        <a href="/signout" class="btn-2 text-decoration-none">Sign Out</a>
        @else
        <nav class="navbar navbar-expand-lg px-3 justify-content-center">
            <a href="/" class="text-decoration-none">
                <h3>Mekaniko Presale</h3>
            </a>
        @endif

    </nav>
</header>
