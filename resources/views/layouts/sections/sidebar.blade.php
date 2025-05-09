<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            @auth
                <div class="pull-left image">
                    <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ auth()->user()->name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
                </div>
            @else
                <div class="pull-left image">
                    <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>SIN DATOS</p>
                    <a href="#"><i class="fa fa-circle text-default"></i> Sin conexión</a>
                </div>
            @endauth
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">NAVEGACIÓN PRINCIPAL</li>

            <li class="active">
                <a href="{{ route('welcome') }}" class="ajax-link">
                    <i class="fa fa-dashboard"></i> <span>INICIO</span>
                </a>
            </li>

            <li>
                <a href="{{ route('users') }}" class="ajax-link">
                    <i class="fa fa-user"></i> <span>USUARIOS</span>
                </a>
            </li>

            <li>
                <a href="{{ route('products.index') }}" class="ajax-link">
                    <i class="fa fa-cogs"></i> <span>PRODUCTOS</span>
                </a>
            </li>

            <!-- NUEVO: Opción de PROVEEDORES -->
            <li>
                <a href="{{ route('proveedores') }}" class="ajax-link">
                    <i class="fa fa-truck"></i> <span>PROVEEDORES</span>
                </a>
            </li>
        </ul>
    </section>
</aside>



