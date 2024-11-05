<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
        </a>
        <div class="navbar-nav w-100">
            <a href="{{ route('dashboard') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-laptop me-2"></i>Elements</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('buttons') }}" class="dropdown-item">Buttons</a>
                    <a href="{{ route('typography') }}" class="dropdown-item">Typography</a>
                    <a href="{{ route('other-elements') }}" class="dropdown-item">Other Elements</a>
                </div>
            </div>
            <a href="{{ route('widgets') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets</a>
            <a href="{{ route('forms') }}" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
            <a href="{{ route('tables') }}" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables</a>
            <a href="{{ route('charts') }}" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="far fa-file-alt me-2"></i>Pages</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="{{ route('signin') }}" class="dropdown-item">Sign In</a>
                    <a href="{{ route('signup') }}" class="dropdown-item">Sign Up</a>
                    <a href="{{ route('404') }}" class="dropdown-item">404 Error</a>
                    <a href="{{ route('blank') }}" class="dropdown-item">Blank Page</a>
                </div>
            </div>
        </div>
    </nav>
</div>
