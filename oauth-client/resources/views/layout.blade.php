<!DOCTYPE html>
<html class='no-js' lang='en'>
<head>
	<meta charset='utf-8'>
	<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
	<title>@yield('title')</title>
	<meta content='lab2023' name='author'>
	<meta content='' name='description'>
	<meta content='' name='keywords'>
	<link href="/css/base.css" rel="stylesheet" type="text/css" />
	<link href="/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/css/font-awesome.min.css" rel="stylesheet" />
	<!--<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
	<link href="assets/images/favicon.ico" rel="icon" type="image/ico" />
	<link href="/css/app.css" rel="stylesheet" type="text/css" />
	<script src="/js/jquery-3.1.0.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/layout.js"></script>
</head>
<body class='main page'>
<!-- Navbar -->
<!--
<div class='navbar navbar-default' id='navbar'>
	<a class='navbar-brand' href='#'>
		<i class='icon-beer'></i>
		OAUTH - play
	</a>
	<--!--
	<ul class='nav navbar-nav pull-right'>
		<li class='dropdown'>
			<a class='dropdown-toggle' data-toggle='dropdown' href='#'>
				<i class='icon-envelope'></i>
				Messages
				<span class='badge'>5</span>
				<b class='caret'></b>
			</a>
			<ul class='dropdown-menu'>
				<li>
					<a href='#'>New message</a>
				</li>
				<li>
					<a href='#'>Inbox</a>
				</li>
				<li>
					<a href='#'>Out box</a>
				</li>
				<li>
					<a href='#'>Trash</a>
				</li>
			</ul>
		</li>
		<li>
			<a href='#'>
				<i class='icon-cog'></i>
				Settings
			</a>
		</li>
		<li class='dropdown user'>
			<a class='dropdown-toggle' data-toggle='dropdown' href='#'>
				<i class='icon-user'></i>
				<strong>John DOE</strong>
				<img class="img-rounded" src="http://placehold.it/20x20/ccc/777" />
				<b class='caret'></b>
			</a>
			<ul class='dropdown-menu'>
				<li>
					<a href='#'>Edit Profile</a>
				</li>
				<li class='divider'></li>
				<li>
					<a href="/">Sign out</a>
				</li>
			</ul>
		</li>
	</ul>
	-- >
</div>
-->
<div id='wrapper'>
	<!-- Sidebar -->
	<section id='sidebar'>
			<ul id='dock'>
			<li class='launcher dropdown hover active'>
				<a href='#'>Home</a>
			</li>
			<!--<li class='launcher active'>
				<i class='icon-linkedin-sign'></i>
				<a href="/tests/linkedin/auth-grant">Linkedin</a>
			</li>
			<!--
			<li class='launcher active'>
				<i class='icon-google-plus'></i>
				<a href="dashboard.html">Google</a>
			</li>

			<li class=' launcher'>
				<i class='icon-file-text-alt'></i>
				<a href="forms.html">Forms</a>
			</li>
			<li class='launcher'>
				<i class='icon-table'></i>
				<a href="tables.html">Tables</a>
			</li>
			<li class='launcher dropdown hover'>
				<i class='icon-flag'></i>
				<a href='#'>Reports</a>
				<ul class='dropdown-menu'>
					<li class='dropdown-header'>Launcher description</li>
					<li>
						<a href='#'>Action</a>
					</li>
					<li>
						<a href='#'>Another action</a>
					</li>
					<li>
						<a href='#'>Something else here</a>
					</li>
				</ul>
			</li>
			<li class='launcher'>
				<i class='icon-bookmark'></i>
				<a href='#'>Bookmarks</a>
			</li>
			<li class='launcher'>
				<i class='icon-cloud'></i>
				<a href='#'>Backup</a>
			</li>
			<li class='launcher'>
				<i class='icon-bug'></i>
				<a href='#'>Feedback</a>
			</li>
			-->
		</ul>
		<div data-toggle='tooltip' id='beaker' title='Made by lab2023'></div>
	</section>
	<!-- Tools -->
	<section id='tools'>

		<ul class='breadcrumb' id='breadcrumb'>
			<li class='title'>@yield('page-title')</li>
		</ul>
		<!--<ul class='breadcrumb' id='breadcrumb'>
			<li class='title'>Forms</li>
			<li><a href="#">Lorem</a></li>
			<li class='active'><a href="#">ipsum</a></li>
		</ul>-->
		<div id='toolbar'>

		</div>
	</section>
	<!-- Content -->
	<div id='content'>

			@yield('content')

	</div>
</div>

@yield('scripts')
</body>


</html>
