      <nav class="sidebar-nav">
        <div class="logo-wrapper">
          <img src="{{url('images/logo.png')}}" alt="" class="img-fluid">
        </div>
        <ul class="">
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}"><i class="icon-speedometer"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('user.list')}}"><i class="icon-puzzle"></i> Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('setting.list')}}"><i class="icon-puzzle"></i> Settings</a>
          </li>
        </ul>
      </nav>
    