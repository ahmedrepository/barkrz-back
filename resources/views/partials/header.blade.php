<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        {{--<li class="nav-item d-none d-sm-inline-block">--}}
            {{--<a href="index3.html" class="nav-link">Home</a>--}}
        {{--</li>--}}
        {{--<li class="nav-item d-none d-sm-inline-block">--}}
            {{--<a href="#" class="nav-link">Contact</a>--}}
        {{--</li>--}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a href="{{ route('logout') }}" class="btn btn-label btn-label-brand btn-sm btn-bold" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>

    </ul>

</nav>