

<!DOCTYPE html>


<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<title>SIMKEU PDPB Bandung</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<link href="css/font_css.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/themes/blue.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/pages/lock.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="shortcut icon" href="hms.ico"/>
<script src="assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>

<SCRIPT type="text/javascript">
    history.pushState(null, document.title, location.href);
    window.addEventListener('popstate', function (event)
    {
      history.pushState(null, document.title, location.href);
    });

</SCRIPT>

</head>


<body>

<div class="page-lock">
	<div class="page-logo">
		<a class="brand" href="home.php">
			<img src="assets/img/logo.png" alt="logo" width="300"/>
		</a>
	</div>
	<div class="page-body">
		<img class="page-lock-img" src="puser/<?php echo $avatar;?>" alt="">
		<div class="page-lock-info">
			<h1><?php echo $userid;?></h1>
			<span class="locked">
				 Terkunci
			</span>
			<form class="form-inline" name="frmlogin" id="frmlogin">
				<div class="input-group input-medium">
					<input type="password" class="form-control" placeholder="Password" id="password" name="password">
					<span class="input-group-btn">
						<button type="submit" class="btn blue icn-only"><i class="m-icon-swapright m-icon-white"></i></button>
					</span>

				</div>
				<div class="err" id="results"></div>
				<div class="relogin">
					<a href="logout.php">
						 Bukan <?php echo $userid;?> ?
					</a>
				</div>
			</form>
		</div>
	</div>
	<div class="page-footer">
      SIMKEU - 2017 &copy; PDPB Bandung
	</div>
</div>

<script src="assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="assets/scripts/core/app.js"></script>
<script src="assets/scripts/custom/cek_login1.js" type="text/javascript"></script>
<script src="assets/scripts/custom/lock.js"></script>
<script src="assets/scripts/custom/home-periode.js" type="text/javascript"></script>

<script>
jQuery(document).ready(function() {    
   App.init();
   Lock.init();
});
</script>
</body>
</html>

