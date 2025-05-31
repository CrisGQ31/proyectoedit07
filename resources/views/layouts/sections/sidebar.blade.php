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

            <!-- NUEVO: Opción de EMPLEADOS -->
            <li>
                <a href="{{ route('empleados.index') }}" class="ajax-link">
                    <i class="fa fa-users"></i> <span>EMPLEADOS</span>
                </a>
            </li>



            <li>
                <a href="{{ route('permisos.index') }}" class="ajax-link">
                    <i class="fa fa-key"></i> <span>PERMISOS</span>
                </a>
            </li>

            <li>
                <a href="{{ route('solicitantes.index') }}" class="ajax-link">
                    <i class="fa fa-address-card"></i> <span>SOLICITANTES</span>
                </a>
            </li>

            <li>
                <a href="{{ route('bitacora.index') }}" class="ajax-link">
                    <i class="fa fa-history"></i> <span>BITÁCORA</span>
                </a>
            </li>

            <!-- NUEVO: Submenú CATÁLOGO -->
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder-open"></i> <span>CATÁLOGO</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('tipoestatus.index') }}" class="ajax-link">
                            <i class="fa fa-tasks"></i> <span>TIPO DE STATUS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tipojuicio.index') }}" class="ajax-link">
                            <i class="fa fa-gavel"></i> <span>TIPO DE JUICIO</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('materias.index') }}" class="ajax-link">
                            <i class="fa fa-book"></i> <span>MATERIAS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('acciones.index') }}" class="ajax-link">
                            <i class="fa fa-flash"></i> <span>ACCIONES</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </section>
</aside>
