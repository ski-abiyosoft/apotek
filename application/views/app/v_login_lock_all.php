
<!DOCTYPE html>

<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<title><?php echo $this->config->item('nama_app');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<link href="<?php echo base_url();?>css/font_css.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/themes/blue.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/pages/lock.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/img/hms.ico" rel="shortcut icon"/>


<SCRIPT type="text/javascript">
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
      window.history.pushState(null, "", window.location.href);
    };
   
</SCRIPT>

<style>
    .row-lock{
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        /* margin-right: -15px;
        margin-left: -15px; */
    }

    .col-sm-lock{
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
        max-width: 100%;
    }
</style>



</head>


<body>
<div class="page-lock">
   

	<div class="page-logo">
		<a class="brand" href="<?php echo base_url(); ?>">
			<img src="<?php echo base_url(); ?>assets/img/ski.png" alt="logo" width="100"/>
		</a>
	</div>
	<div class="page-body">
    
    <div class="row-lock">
        <div class="col-sm-lock">
            <h4 style="color:white;"><b><?php echo $this->session->userdata('nama_lengkap'); ?></b> <br><br>Maaf, sesi Anda telah habis 60 menit. Silahkan login kembali.</h4>
        </div>
        <div class="col-sm-lock">
            <!-- <div style="margin-top:20px;"></div> -->
            <h5 style="color:white;"><center>Kembali ke login. 
			<a href="<?php echo base_url(); ?>" class="btn blue icn-only" style="border:1px solid white;"><i class="m-icon-swapright m-icon-white"></i></a></center></h5>
        </div>
    </div>
        <!-- <div class="page-lock-img">Maaf, sesi Anda telah habis. Silahkan login kembali.</div>
		<div class="page-lock-info">
			<h1>Maaf, sesi Anda telah habis. Silahkan login kembali.</h1>
			<form id="frmlogin" class="login-form"  action="<?php echo base_url(); ?>" method="post">
				
				<div class="relogin">
					<a href="<?php echo base_url(); ?>">
						 Back To Login
					</a>
				</div>
			</form>
		</div> -->
	</div>
	<div class="page-footer">
      <?php echo $this->config->item('name_app');?>
	</div>
</div>



<script src="<?php echo base_url();?>assets/scripts/custom/lock.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/scripts/core/app.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>


<script>
jQuery(document).ready(function() {    
   App.init();
   Lock.init();
});
</script>
</body>
</html>

