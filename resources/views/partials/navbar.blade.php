<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('public/images/logo.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><h5>Barkrz</h5></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('users')}}" class="nav-link @if(isset($users_tab)) active @endif">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('pets')}}" class="nav-link @if(isset($pets_tab)) active @endif">
                        <i class="nav-icon fa fa-paw"></i>
                        <p>
                            Pets
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('subscribers')}}" class="nav-link @if(isset($subscriber_tab)) active @endif">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Subscribers List
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('coupon')}}" class="nav-link @if(isset($coupon_tab)) active @endif">
                        <i class="nav-icon fa fa-key"></i>
                        <p>
                            Coupon Code
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
