<!doctype html>
<html lang="en" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title>Koha</title>
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<link rel="icon" href="favicon.ico" type="image/ico" />
	<meta name="keywords" content="wellington,koha, service, catalyst cloud, catalyst">
	<meta name="description" content="This webpage is a front for a site testing registration and deployment of sites on the Koha as a Service system" />
	<meta name="author" content="Webpage:Francesca Moore; Koha as a Service: Callum Dickinson" />
	<meta name="copyright" content="Copyright 2015 Callum Dickinson and Francesca Moore " />
	<meta name="robots"content= "noindex, nofollow" />
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="css/mobile.css" media="only all and (max-width: 480px)" />
	<link rel="stylesheet" href="css/tablet.css" media="only all and (min-width: 481px)" />
	<link rel="stylesheet" href="css/styles.css" media="only all and (min-width: 960px)" />
	<link rel="stylesheet" href="css/default.css" /> <!---works in conjuction with mobile and tablet style sheets--->
	<!-- the following script operates for older browsers incl. mobile -->
	<!--[if lt IE 9 & !IEMobile]>
			<link rel="stylesheet" href="css/mobile.css" />
			<link rel="stylesheet" href="css/laptop.css" />
		<![endif]-->

		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]--> 
    
    <!---load jquery---><script type="text/javascript" src="//code.jquery.com/jquery-2.1.4.min.js"></script>

</head>

<body>
	<div id="container"> <!--this holds everything-->
	  <header>
        <img src="assets/koha.png" alt="koha logo"/>
        <hgroup>
            <h1><i>Koha as a Service</i></h1>
            <h2>You are here: registration/home</h2>
        </hgroup>
	  </header>

	  <nav class="no-mobile">
		<ul>
		  <li><a href="http://koha-community.org">Koha main webpage</a></li>
		  <li><a href="http://dashboard.koha-community.org/">Koha Dashboard</a></li>
		</ul>
	  </nav>

	  


	<article>
		<a  class="mobile-only" href="#jumpbottom" >To Navigation</a>
		<a class="mobile-only" name="jumptop"></a> <!---these links are for mobile only, they allow easy navigation whilst on a mobile device.---> 
		
		<h1>Welcome</h1>
			<p>Hello and welcome to the Koha as a Service webpage. To register a domain please fill out all fields in the form below.</p>
			
		<h1>Register a domain</h1>
			<p>Please note that all fields <strong>must</strong> be filled out before you can register a domain. Please do not refesh this page until all information is correct - you will lose your form data.</p>
			
		<div id="domain">
			<form name="register" onSubmit=" return password()" method="post">
		
				<p>
					<label for="firstname">First name</label>
					<input id="name" type="text" name="firstname" placeholder="Enter your first name" required> <!---makes field a requirment--->
				
				</p>
				<p>
					<label for="surname">Surname</label>
					<input id="lastname" type="text" name="surname" placeholder="Enter your last name"
required> 
				</p>
				
				<p>
					<label for="sitename">Site name</label>
					<input id="site" type="text" name="sitename" placeholder="Enter your site name" required>          
				</p>
				
				<p>
					<label for="domain">Base domain</label>
					<input id="domain1" type="text" name="domain" placeholder="Enter the domain name" required>          
				
				<p> 
					<label for="email">Email adress</label>
					<input id="email" type="email" name="email" placeholder="Enter your email" required>
				</p>
				
					
				
				<p>
					<label for="password">Password</label>
					<input id="pass1" type="password" name="pword" placeholder="Enter a password for your koha instance" required>           
				
				<p>
				
					<label for="confirmpassword">Confirm password</label>
					<input id="pass2" type="password" name="confirmpword" placeholder="Confirm password" required >
				</p>
				<div id="password"></div> <!---this is where the password error will show--->
				
				
				
			<script><!---javascript to check if passwords match--->
				function password() {
					var pass1 = document.getElementById("pass1").value;
					var pass2 = document.getElementById("pass2").value;
					if (pass1 != pass2) {
						$("#password").html("<p>passwords don't match</p>");
                            document.getElementById("pass1").style.borderColor = "#E34234";
                            document.getElementById("pass2").style.borderColor = "#E34234";
                        }
                        else {
                            sessionStorage.setItem("name", document.getElementById("name").value);
                            sessionStorage.setItem("lastname", document.getElementById("lastname").value);
                            sessionStorage.setItem("site", document.getElementById("site").value);
                            sessionStorage.setItem("domain1", document.getElementById("domain1").value);
                            sessionStorage.setItem("email", document.getElementById("email").value);
                             window.location.href = "confirm.php";

                        }
                        return false;

                    }
                </script>
			
				<input type="submit"  value="Submit">
				
			</form>
		</div>
		
		
	</article>


	  <a class="mobile-only" name="jumpbottom"></a>
	  <a class="mobile-only" href="#jumptop" >Back to top</a>

	<footer>
        <ul class="mobile-only">
		  <li><a href="http://koha-community.org">Koha main webpage</a></li>
		  <li><a href="http://dashboard.koha-community.org/">Koha Dashboard</a></li>
		</ul>
	
		<div class="no-mobile"><p>&copy; Callum Dickinson and Francesca Moore 2015 <?php echo date("d/m/Y");?></p></div> <!---copyright and date will not show on mobile--->
	</footer>
</body>
