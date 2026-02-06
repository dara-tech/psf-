<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="PSF Patient Satisfactory Feedback">
	<meta name="author" content="Ansonika">
	<title>Patient Satisfactory Feedback (PSF)</title>

	<!-- GOOGLE WEB FONT -->
	<link href="https://fonts.googleapis.com/css2?family=Hanuman&display=swap" rel="stylesheet">
	<!-- BASE CSS -->
	<link href="/css/bootstrap-grid.css" rel="stylesheet">
	<link href="/css/bootstrap-grid.min.css" rel="stylesheet">
	<link href="/css/bootstrap-reboot.css" rel="stylesheet">
	<link href="/css/bootstrap-reboot.min.css" rel="stylesheet">
	<link href="/css/bootstrap.css" rel="stylesheet">
	<link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> 
	<link rel="icon" href="/img/logo.png">
	<!-- YOUR CUSTOM CSS -->
	
	<!-- Modernizr -->
        
        

</head>
<body> 

 
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded ">
        @yield('section_title')
            <ul class="navbar-nav ml-auto"​ id="navbarsExample09">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-danger" href="" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flag-icon flag-icon-us"> </span><?php echo App::isLocale('kh')? "English" : "ខ្មែរ"; ?> </a>
                    <div class="dropdown-menu" aria-labelledby="dropdown09">
                        <a class="dropdown-item " href="/{{$parent}}/{{$token}}/en" ><span class="flag-icon flag-icon-en"> </span>  English</a>
                        <a class="dropdown-item " href="/{{$parent}}/{{$token}}/kh" ><span class="flag-icon flag-icon-kh"> </span>  ខ្មែរ</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
   
    
    <main>
        
        <div class="container">
            @yield('content')
        </div> <!-- form_container -->
    </main>
	

    <!-- SCRIPTS -->
    <!-- Jquery-->
    <script src="/js/jquery-3.5.1.js"></script>
    <!-- Wizard script -->
    <script src="/js/bootstrap.js"></script>
    <!-- Menu script -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/bootstrap.bundle.js"></script>
    <!-- Theme script -->
    <script src="/js/bootstrap.bundle.min.js"></script>

    <div class="row justify-content-center align-items-center pt-3" >
					<img src="/img/logos.png" style="width:70%;" >
	</div>


    @yield('javascript')
</body>

</html>