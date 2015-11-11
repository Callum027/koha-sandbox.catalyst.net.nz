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
	<link rel="stylesheet" href="css/default.css" media="only all and (min-width: 480px") /> <!---works in conjuction with mobile and tablet style sheets--->
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
		<h2 class="no-mobile">You are here: confirm your info</h2>
	   </hgroup>
	  </header>
        
  <nav class="clearfix">
		<ul>
		  <li><a href="http://koha-community.org">Koha main webpage</a></li>
		  <li><a href="http://dashboard.koha-community.org/">Koha Dashboard</a></li>
            
          <li><a href="http://wiki.koha-community.org/wiki/Main_Page">Koha Wiki</a></li>
            
         <li><a href="https://www.catalyst.net.nz/content/homepage-products">Catalyst IT</a></li>
		</ul>
	  </nav>
	  


	<article>
		<a  class="mobile-only" href="#jumpbottom" >To Navigation</a>
		<a class="mobile-only" name="jumptop"></a> <!---these links are for mobile only, they allow easy navigation whilst on a mobile device.--->
        <h1>Confirm your infomation</h1>
        <p>Please check that all the information you have entered is correct, then hit confirm to build your koha instance. If your information is not correct please follow the <a href="index.php">link</a> back to the homepage. Please note that your information will not be kept.</p>
                  <div id="register">
			<form id="registerform" action="hold.php" method="post" name="register">
		
				<p>
					<label for="firstname">First name</label>
					<input id="insertname" type="text" name="firstname" readonly> <!---makes field a requirment--->
				
				</p>
				<p>
					<label for="surname">Surname</label>
					<input id="insertlastname" type="text" name="surname" readonly>
				</p>
				
				<p>
					<label for="opac">OPAC server</label>
					<input id="insertopac" type="text" name="opacname" readonly>
				</p>
				
				<p>
					<label for="intra">INTRA server</label>
					<input id="insertintra" type="text" name="intraname" readonly>
				</p>
				
				<p> 
					<label for="email">Email address</label>
					<input id="insertemail" type="email" name="email" readonly>
				</p>
				
				<p>
					<label for="password">Password</label>
					<input id="insertpass1" type="password" name="pword" readonly>           
				
				<p>
				
					<label for="confirmpassword">Confirm password</label>
					<input id="insertpass2" type="password" name="confirmpword" readonly>
				</p>

				
				<input id="confirmsite" type="submit" value="confirm">
				
			</form>
		</div>
	

<script>
    document.getElementById("insertname").value = sessionStorage.getItem("name");
    document.getElementById("insertlastname").value = sessionStorage.getItem("lastname");
    document.getElementById("insertopac").value = sessionStorage.getItem("opac");
    document.getElementById("insertintra").value = sessionStorage.getItem("intra");
    document.getElementById("insertemail").value = sessionStorage.getItem("email");
    document.getElementById("insertpass1").value = sessionStorage.getItem("pass1");
    document.getElementById("insertpass2").value = sessionStorage.getItem("pass2");

    $(document).ready(function()
    {
        //$("#confirmsite").click(function()
        //{
            $("#registerform").ajaxForm
            ({
                dataType: 'json',
                success: function(response)
                {
                    sessionStorage.setItem("id", $.parseJSON(response).id);
                }
            });
        //});
        
    });
</script>
        
	                            
		
	</article>


	  <a class="mobile-only" name="jumpbottom"></a>
	  <a class="mobile-only" href="#jumptop" >Back to top</a>

	<footer>
	
		<div class="no-mobile"><p>&copy; Callum Dickinson and Francesca Moore 2015 <?php echo date("d/m/Y");?></p></div> <!---copyright and date will not show on mobile--->
	</footer>
</body>
