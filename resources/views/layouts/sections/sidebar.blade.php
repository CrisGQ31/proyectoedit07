<aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        @auth
          <div class="pull-left image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ auth()->user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> En l&iacute;nea</a>
          </div>
        @else
          <div class="pull-left image">
            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>SIN DATOS</p>
            <a href="#"><i class="fa fa-circle text-default"></i> Sin conexi&oacute;n</a>
          </div>
        @endauth
      </div>

      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVEGACI&Oacute;N PRINCIPAL</li>
        <li class="active">
          <a href="{{ route('welcome') }}" class="ajax-link">
            <i class="fa fa-dashboard"></i> <span>INICIO</span>
          </a>
        </li>
        <li class="">
          <a href="{{ route('users') }}" class="ajax-link">
            <i class="fa fa-user"></i> <span>USUARIOS</span>
          </a>
        </li>
        {{-- <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>USUARIOS</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li> --}}
      </ul>
    </section>
</aside>