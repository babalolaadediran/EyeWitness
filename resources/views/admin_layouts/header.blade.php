<!-- navbar-->
<header class="header">
	<nav class="navbar navbar-expand-lg px-4 py-2 bg-white shadow">
		<a href="javascript:void(0);" class="sidebar-toggler text-gray-500 mr-4 mr-lg-5 lead">
			<i class="fas fa-align-left"></i>
		</a>
		<a href="{{ url('administrator/home') }}" class="navbar-brand font-weight-bold text-uppercase text-base">Mopani Hotspot Reporter</a>
		<ul class="ml-auto d-flex align-items-center list-unstyled mb-0">
			<li class="nav-item dropdown ml-auto">
				<a id="userInfo" href="javacript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
					<img src="data:image/png;base64,{{ $admin->picture }}" alt="Profile Picture" style="max-width: 2.5rem;" class="avatar img-fluid rounded-circle shadow">
				</a>
				<div aria-labelledby="userInfo" class="dropdown-menu">
					<a href="#" class="dropdown-item">
						<strong class="d-block text-uppercase headings-font-family">{{ $admin->fullname }}</strong>
						<small>{{ $admin->email }}</small>
					</a>
					<div class="dropdown-divider"></div>
					<a href="{{ url('administrator/profile') }}" class="dropdown-item">Profile</a>
					<div class="dropdown-divider"></div>
					<a href="javascript:void(0);" id="logout-link" class="dropdown-item">Logout</a>
					<form method="POST" id="logout-form" action="{{ url('administrator/logout') }}">
						@csrf
					</form>
				</div>
			</li>
		</ul>
	</nav>
</header>
