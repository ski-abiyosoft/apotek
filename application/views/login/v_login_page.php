
<html lang="en" class="no-js">
<head>
<meta charset="utf-8"/>
<title><?php echo $this->config->item('nama_app');?></title>
<link rel="shortcut icon" href="hms.ico"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<link href="<?php echo base_url('assets/css/font_css.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/uniform/css/uniform.default.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/plugins/select2/select2.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/plugins/select2/select2-metronic.css');?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('assets/css/style-metronic.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/style-responsive.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/plugins.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/themes/blue.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/pages/login-soft.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/css/custom.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url('assets/img/hms.ico');?>" rel="shortcut icon" />
<link href="<?php echo base_url('assets/plugins/gritter/css/jquery.gritter.css');?>" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.css" rel="stylesheet" type="text/css">


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
		margin:0px 5px 0px 5px;
    }
	
	.d-flex {
		display: -webkit-box!important;
		display: -ms-flexbox!important;
		display: flex!important;
	}

	.justify-content-center {
		-webkit-box-pack: center!important;
		-ms-flex-pack: center!important;
		justify-content: center!important;
	}

	.justify-content-between {
		-webkit-box-pack: justify!important;
		-ms-flex-pack: justify!important;
		justify-content: space-between!important;
	}
</style>

</head>

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

	.cursor-pointer{
		cursor: pointer;
	}
</style>
<body class="login" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">



<div class="logo">
	<a href="<?php echo base_url();?>">
		<img src="<?php echo base_url('assets/img/ski.png');?>" width="150" alt=""/>
	</a>
</div>

<div class="content">
	<form id="frmlogin" class="login-form"  action="<?php echo base_url('app/auth');?>" method="post">
		<h3 align="center" class="form-title" style="margin-top:-10px;"><b>Login </b></h3>
		<h3 align="center" style="color:white;font-size: 15px;"><?php echo $this->M_global->tgln();?><span id="jam"></span><h3>
		<!--div class="alert alert-danger alert-dismissable">
			   <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			   <?php echo $this->session->flashdata('result_login'); ?>
		</div-->
		<div clas="">		
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Nama</label>
				<div class="input-icon">
					<i class="fa fa-user"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Nama" id="username" name="username" onchange="getcabang()" required /><!-- onclick="getcabang()" onchange="getcabang()"/> -->
				</div>
			</div>
			<div class="form-group">
				<label class="form-label visible-ie8 visible-ie9"></label>
				<select class="form-control" type="text" name="cabang" id="cabang">
					<option>- Pilih Cabang -</option>
					<?php
					foreach($cabang as $ckey => $cval){
						?>
						<option value=<?= $cval->koders ?> ><?= $cval->namars ?></option>";
					<?php	} ?>
					
				</select>
			</div>
			<!--<div class="form-group">
				<label class="form-label visible-ie8 visible-ie9">Cabang</label>
				<div class="input-icon">
					<i class="fa fa-building-o"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Cabang" id="cabang" name="cabang" readonly/>
				</div>
			</div>-->
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">Password</label>
				<div class="input-group" id="show_hide_password">
					<input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" id="password" name="password" required/>
					<div class="input-group-addon cursor-pointer" onclick="cekp();">
						<i class="fa fa-eye-slash" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			<div class="form-actions" style="padding:0px 30px 5px 30px;">            
				
				<button type="submit" class="btn blue pull-right">
				<b>Login <i class="m-icon-swapright m-icon-white"></i></b>
				</button>
				<?php echo '<label class="label label-sm label-warning">' . $this->session->flashdata( "result_login" ) . '</label>';?>
			</div>
			<div class="copyright" style="font-family: arial;color:white;margin-buttom:0px;">     
				<b><?php echo $this->config->item('name_app');?></b><br><br>
				<a data-toggle="modal" data-target="#exampleModal" href="#" style='font-family: arial; color:white;font-size:25px;'>
					<p><b><span >H A K I</span></b></p>
				</a>
			</div>
		</div>
		
	</form>
	
</div>
<!-- 
<div class="copyright">     
	<?php echo $this->config->item('name_app');?>
</div> -->

<!-- <div class="copyright" >     
	<a data-toggle="modal" data-target="#exampleModal" href="#" style='color:white;'>
		<h3><b>H A K I</b></h3>
	</a>
</div> -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b>HAKI</b></h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
		<!-- <center><img src="<?php echo base_url()."assets/img/haki/djki.jpeg"; ?>"></center> -->
		<div class="row-lock">
			<div class="col-sm-lock">
				<img src="<?php echo base_url()."assets/img/haki/djki.jpeg"; ?>"  width='350px'>
			</div>
			<div class="col-sm-lock">
				<img src="<?php echo base_url()."assets/img/hms/hms.jpeg"; ?>"  width='175px'>
			</div>
		</div>
		<br/>
		<div style='text-align:justify;'>
		<b>Hospital Management System (HMS)</b> ®
		Telah dilindungi <b>Undang-Undang Republik Indonesia</b> No 19 Tahun 2002 tentang Hak Cipta, dan telah terdaftar di <b>KEMENTERIAN HUKUM DAN HAM REPUBLIK INDONESIA</b> dengan nomor pendaftaran <b>050356</b>.
		Barang siapa menggandakan dan menjual tanpa seizin pemegang hak cipta ini akan dikenakan denda dan pidana sesuai hukum yang berlaku.
		</div>
		<br>
		<div>
			Inisial Klinik BHAKTI RAHAYU Group:
		</div>

		<?php
			$i = 0; $j = 0;
			foreach($data as $key){
				if($i % 6 == 0) echo "<div class='row'>";		
				echo "<div class='col-md-2'>".($i+1).". ".$key->koders."</div>";
				$j++;
				if($j == 6) {
					echo "</div>";
					$j = 0;
				}
				
				$i++; 
			}

		?>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('assets/plugins/jquery-1.10.2.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-migrate-1.2.1.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.blockui.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery.cokie.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/uniform/jquery.uniform.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/jquery-validation/dist/jquery.validate.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/backstretch/jquery.backstretch.min.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.min.js');?>" type="text/javascript" ></script>
<script src="<?php echo base_url('assets/scripts/core/app.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/scripts/custom/ui-general.js');?>"></script>
<script src="<?php echo base_url('assets/plugins/gritter/js/jquery.gritter.js');?>" type="text/javascript" ></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/sweet-alert2/sweetalert2.js"></script>

	<?php if ( $this->session->flashdata('ntf0')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo 'Informasi Login'?></b>',
			text: '<?php echo $this->session->flashdata('ntf0'); ?>',
			image: '<?php echo base_url('assets/img/logoi.png');?>',
			class_name: 'color-danger'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf1')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo 'Informasi Login'?></b>',
			text: '<?php echo $this->session->flashdata('ntf1'); ?>',
			image: '<?php echo base_url('assets/img/logoi.png');?>',
			class_name: 'color-success'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf2')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo 'Informasi Login'?></b>',
			text: '<?php echo $this->session->flashdata('ntf2'); ?>',
			class_name: 'color-primary'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf3')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo 'Informasi Login'?></b>',
			text: '<?php echo $this->session->flashdata('ntf3'); ?>',
			class_name: 'color-warning'
		} );
	</script>
	<?php }?>
	<?php if ( $this->session->flashdata('ntf4')) {?>
	<script type="text/javascript">
		$.gritter.add( {
			title: '<b><?php echo 'Informasi Login'?></b>',
			text: '<?php echo $this->session->flashdata('ntf4'); ?>',
			class_name: 'color-danger'
		} );
	</script>
	<?php }?>
	
<SCRIPT type="text/javascript">
    
	var Login = function () {
   
	var handleLogin = function() {
		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                username: {
	                    required: true
	                },
	                password: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                }
	            },

	            messages: {
	                username: {
	                    required: "Nama Harus Diisi."
	                },
	                password: {
	                    required: "Password Harus Diisi."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit   
	                $('.alert-danger', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

         $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    $('.login-form').submit();
	                }
	                return false;
	            }
	        });
	}
	 return {
        init: function () {
			//handleLogin();
           	$.backstretch([
		        "<?php echo base_url();?>assets/img/bg/1.jpg",
		        "<?php echo base_url();?>assets/img/bg/2.jpg",
		        "<?php echo base_url();?>assets/img/bg/3.jpg",
		        "<?php echo base_url();?>assets/img/bg/4.jpg"
		        ], {
		          fade: 1000,
		          duration: 8000
		    });
        }

    };

}();

	
	
    window.history.forward();
    function noBack() { window.history.forward(); }
    
    var isCtrl = false;
    document.onkeyup=function(e)
    {
        if(e.which == 17)
        isCtrl=false;
    }
    document.onkeydown=function(e)
    {
        if(e.which == 17)
        isCtrl=true;
        if((e.which == 85 && isCtrl == true) || (e.which == 67 && isCtrl == true))
        {
            return false;
        }
    }


    var isNS = (navigator.appName == "Netscape") ? 1 : 0;
    if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP);
    function mischandler(){
        return false;
    }
    function mousehandler(e){
        var myevent = (isNS) ? e : event;
        var eventbutton = (isNS) ? myevent.which : myevent.button;
        if((eventbutton==2)||(eventbutton==3)) return false;
    }
    document.oncontextmenu = mischandler;
    document.onmousedown = mousehandler;
    document.onmouseup = mousehandler;
    
    history.pushState(null, document.title, location.href);
    window.addEventListener('popstate', function (event)
    {
      history.pushState(null, document.title, location.href);
    });
	
	

	jQuery(document).ready(function() {
		  App.init();
		  Login.init();
		
		  UIGeneral.init();
		  
		  
		});		

	function cekp(){

			if($('#show_hide_password input').attr("type") == "text"){
				
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass( "fa-eye-slash" );
				$('#show_hide_password i').removeClass( "fa-eye" );

			}else if($('#show_hide_password input').attr("type") == "password"){

				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass( "fa-eye-slash" );
				$('#show_hide_password i').addClass( "fa-eye" );
			}

	};


	function getcabang(){
		var listcabang;
		var uidlogin = $("#username").val();
		$.ajax({
			url : "<?php echo base_url();?>app/getcabang/?uid="+uidlogin,
			type: "GET",
			dataType: "JSON",		
			success: function(data){
				// if(res_cabang=='***'){
				// 	//document.getElementById('password').disabled=true;  
				// 	//document.getElementById('username').focus();
				// 	//swal('','Nama User Salah / Tidak Ditemukan...','');
				// } else {
					
					$.each(data.data.split(","), function(i, keyword){

						$.ajax({
							url        : "<?php echo base_url();?>app/getnm/?id="+keyword,
							type       : "GET",
							dataType   : "JSON",
							success: function(data){
								
								var nm = data.nm;	
								
								listcabang += "<option value='"+ keyword +"'>"+ nm +"</option>";
								
								$("[id='cabang']").html(listcabang);
							}
						});

					});

					
					// $('#cabang').val("PAS1").change();
					
					
					//$('#cabang').val(data.cabang);  	
					//document.getElementById('password').disabled=false;  
					//document.getElementById('password').focus();
				// }
			}
		});
	}
    
/*function getcabang() { 
	var xhttp;      
	var str = $('[name=username]').val();
	$('#cabang').val('');  	
	if(str==""){
		s
	}  else  {
		$.ajax({
			url : "<?php echo base_url();?>app/getcabang/?kode="+str,
			type: "GET",
			dataType: "JSON",		
			success: function(data){		     
				if(data.namacabang=='***'){
					document.getElementById('password').disabled=true;  
					//document.getElementById('username').focus();
					swal('','Nama User Salah / Tidak Ditemukan...','');
				} else {
					$('#cabang').val(data.namacabang);  	
					document.getElementById('password').disabled=false;  
					//document.getElementById('password').focus();
		  		}
			}
		});	    
	}	
} */
		

    
</SCRIPT>
	
</body>
</html> 