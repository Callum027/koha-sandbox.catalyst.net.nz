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
	<meta name="robots" content="noindex, nofollow" />
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="css/mobile.css" media="only all and (max-width: 480px)" />
	<link rel="stylesheet" href="css/tablet.css" media="only all and (min-width: 481px)" />
	<link rel="stylesheet" href="css/styles.css" media="only all and (min-width: 960px)" />
	<!--<link rel="stylesheet" href="css/default.css" media="only all and (min-width: 480px") />-->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script> 
</head>

<body>
	<div id="container"> <!--this holds everything-->
	  <header>
      <img src="assets/koha.png" alt="koha logo"/>
	   <hgroup>
		<h1><i>Koha as a Service</i></h1>
		<h2>You are here: hold</h2>
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

        <div id="headline">
          <p>Your Koha instance is being built.</p>
          <p id="small"><i>This may take a few minutes...</i></p>
        </div>
	
        <div id="dbstatus"></div>	
        <div id="memcachedstatus"></div>
        <div id="zebrastatus"></div>
        <div id="kohastatus"></div>
        <div id="proxystatus"></div>
        <div id="done"></div>

        <script type="text/javascript">
          var CHECK_TIMEOUT = 30000; // ms

          var id = sessionStorage.getItem("id");
          var opac_server_name = sessionStorage.getItem("opac");
          var intra_server_name = sessionStorage.getItem("intra");

          var db_status = 'waiting';
          var memcached_status = 'waiting';
          var zebra_status = 'waiting';
          var koha_status = 'waiting';
          var proxy_status = 'waiting';
          var done = false;

          function refresh_status()
          {
              // Note to Francesca: change this HTML here to change the style of the output.
              $("#dbstatus").html("<p>Database: " + db_status + "</p>");
              $("#memcachedstatus").html("<p>memcached: " + memcached_status + "</p>");
              $("#zebrastatus").html("<p>Zebra: " + zebra_status + "</p>");
              $("#kohastatus").html("<p>Koha Site: " + koha_status + "</p>");
              $("#proxystatus").html("<p>Reverse Proxy: " + proxy_status + "</p>");

              if (done)
              {
                  $("#done").html("<p id=\"small\">Your Koha site is now accessible. Go to your Koha site's administration interface to get started:<br>"
                          + "<a target=\"_blank\" href=\"https://" + intra_server_name + "\">" + intra_server_name + "</a><br><br>"
                          + "Once you have set up your Koha site, the OPAC will be accessible from here:<br>"
                          + "<a target=\"_blank\" href=\"https://" + opac_server_name + "\">" + opac_server_name + "</a></p>");
              }
          }

          function component_ready(name, id)
          {
              return $.getJSON('/api/status/' + name + '/' + id).then(function(data)
              {
                  return data.ready;
              });
          }

          function check_db(id)
          {
              if (!component_ready('db', id))
              {
                  db_status = 'building';
                  refresh_status();
                  setTimeout(check_db, CHECK_TIMEOUT, id);
              }
              else
              {
                  db_status = 'ready';
                  refresh_status();
                  check_memcached(id);
              }
          }

          function check_memcached(id)
          {
              if (!component_ready('memcached', id))
              {
                  memcached_status = 'building';
                  refresh_status();
                  setTimeout(check_memcached, CHECK_TIMEOUT, id);
              }
              else
              {
                  memcached_status = 'ready';
                  refresh_status();
                  check_zebra(id);
              }
          }

          function check_zebra(id)
          {
              if (!component_ready('zebra', id))
              {
                  zebra_status = 'building';
                  refresh_status();
                  setTimeout(check_zebra, CHECK_TIMEOUT, id);
              }
              else
              {
                  zebra_status = 'ready';
                  refresh_status();
                  check_koha(id);
              }
          }

          function check_koha(id)
          {
              if (!component_ready('koha', id))
              {
                  koha_status = 'building';
                  refresh_status();
                  setTimeout(check_koha, CHECK_TIMEOUT, id);
              }
              else
              {
                  koha_status = 'ready';
                  refresh_status();
                  check_proxy(id);
              }
          }

          function check_proxy(id)
          {
              if (!component_ready('proxy', id))
              {
                  proxy_status = 'building';
                  refresh_status();
                  setTimeout(check_proxy, CHECK_TIMEOUT, id);
              }
              else
              {
                  proxy_status = 'ready';
                  refresh_status();
                  done = true;
              }
          }

          refresh_status();
          check_db(id);
        </script>
		
	</article>


	  <a class="mobile-only" name="jumpbottom"></a>
	  <a class="mobile-only" href="#jumptop" >Back to top</a>

	<footer>
	
		<div class="no-mobile"><p>&copy; Callum Dickinson and Francesca Moore 2015 <?php echo date("d/m/Y");?></p></div> <!---copyright and date will not show on mobile--->
	</footer>

        </div>
</body>
