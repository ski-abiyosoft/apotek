<?php 
	  $this->load->view('template/header');
      $this->load->view('template/body');    	  
	?>	

	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css')?>"/>
	<link href="<?php echo base_url('assets/css/plugins.css" rel="stylesheet" type="text/css')?>"/>
	<link href="<?php echo base_url('assets/css/pages/profile.css" rel="stylesheet" type="text/css')?>"/>
	<link href="<?php echo base_url('assets/css/custom.css" rel="stylesheet" type="text/css')?>"/>

			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Profil Pengguna Aplikasi <small></small>					
					</h3>					
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url();?>dashboard">
								Awal
							</a>

						</li>

					</ul>
				</div>
			</div>
            <?php echo $this->session->flashdata('pesan');?>					
			<div class="row profile">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom tabbable-full-width">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_1_1" data-toggle="tab">
                                     Beranda
								</a>
							</li>
							<li>
								<a href="#tab_1_3" data-toggle="tab">
									 Biodata
								</a>
							</li>

						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1_1">
								<div class="row">
									<div class="col-md-3">
										<ul class="list-unstyled profile-nav">
											<li>
												<img src="<?php echo base_url();?>assets/puser/<?php echo $avatar;?>" class="img-responsive" alt=""/>
												<!--a href="#" class="profile-edit">
													 edit
												</a-->
											</li>

										</ul>
									</div>
									<div class="col-md-9">
										<div class="row">
											<div class="col-md-8 profile-info">
												<h1><?php echo $nama;?></h1>
												<p>
                                                  <?php echo $desc;?>
												</p>
												<p>
													<a href="<?php echo $website;?>">
                                                       <?php echo $website;?>
													</a>
												</p>
												<ul class="list-inline">
													<li>
														<i class="fa fa-envelope"></i> <?php echo $email;?>
													</li>
													<li>
														<i class="fa fa-phone"></i> <?php echo $hp;?>
													</li>

												</ul>
											</div>
											<!--end col-md-8-->

										</div>
										<!--end row-->

									</div>
								</div>
							</div>
							<!--tab_1_2-->
							<div class="tab-pane" id="tab_1_3">
								<div class="row profile-account">
									<div class="col-md-3">
										<ul class="ver-inline-menu tabbable margin-bottom-10">
											<li class="active">
												<a data-toggle="tab" href="#tab_1-1">
													<i class="fa fa-cog"></i> Biodata
												</a>
												<span class="after">
												</span>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_2-2">
													<i class="fa fa-picture-o"></i> Mengganti Foto
												</a>
											</li>
											<li>
												<a data-toggle="tab" href="#tab_3-3">
													<i class="fa fa-lock"></i> Mengganti Password
												</a>
											</li>

										</ul>
									</div>
									<div class="col-md-9">
										<div class="tab-content">
											<div id="tab_1-1" class="tab-pane active">
												<form role="form" action="#">
													<div class="form-group">
														<label class="control-label">Nama Lengkap</label>
														<input type="text" placeholder="" class="form-control" value="<?php echo $nama;?>" id="_nama" name="_nama"/>
													</div>
													<div class="form-group">
														<label class="control-label">Nomor Handphone</label>
														<input type="text" placeholder="" class="form-control" value="<?php echo $hp;?>" id="_nohp" name="_nohp"/>
													</div>
													<div class="form-group">
														<label class="control-label">Email</label>
														<input type="text" placeholder="" class="form-control" value="<?php echo $email;?>" id="_email" name="_email"/>
													</div>
													<div class="form-group">
														<label class="control-label">Website</label>
														<input type="text" placeholder="" class="form-control" value="<?php echo $website;?>" id="_website" name="_website"/>
													</div>
													<div class="form-group">
														<label class="control-label">Deskripsi</label>
														<textarea class="form-control" rows="3" placeholder="" id="_desc" name="_desc" ><?php echo $desc;?></textarea>
													</div>
													<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>
													<div class="margiv-top-10">
														<a href="#" class="btn green" id="btnsimpanbio" onclick="save_bio()">
															 Simpan
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
												</form>
											</div>
											<div id="tab_2-2" class="tab-pane">
												<p>
													 Ubahlah foto di bagian ini.
													 
												</p>												
												<form name="frmupload" action="<?php echo base_url()?>master_user_profile/upload" method="post" enctype="multipart/form-data">
													<div class="form-group">
														<div class="fileinput fileinput-new" data-provides="fileinput">
															<div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
																<img src="<?php echo base_url();?>assets/puser/<?php echo $avatar;?>" alt=""/>
															</div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
															</div>
															<div>
																<span class="btn default btn-file">
																	<span class="fileinput-new">
																		 Pilih Foto
																	</span>
																	<span class="fileinput-exists">
																		 Ganti
																	</span>
																	<input type="file" name="filefoto" id="filefoto">
																</span>
																<a href="#" class="btn default fileinput-exists" data-dismiss="fileinput">
																	 Hapus
																</a>
															</div>
														</div>
														<div class="clearfix margin-top-10">
															<span class="label label-danger">
																 CATATAN!
															</span>
															<span>
																 Ukuran file foto yang akan diupload tidak lebih dari 500Kb.
															</span>
														</div>
													</div>
													<div id="progressNumber" style="display:none;"></div>
									                <progress id="prog" value="0" max="100.0" style="display:none;"></progress>
													<div class="margin-top-10">
														<!--a href="#" class="btn green" onclick="<?php echo base_url();?>master_user_profile/upload">
															 Kirim
														</a-->
														<input type="submit" class="btn green" value="Kirim" />
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
												</form>
											</div>
											<div id="tab_3-3" class="tab-pane">
												<form action="#">
													<div class="form-group">
														<label class="control-label">Password Sekarang</label>
														<input type="password" class="form-control" id="_password" name="_password"/>
													</div>
													<div class="form-group">
														<label class="control-label">Password Baru</label>
														<input type="password" class="form-control" id="_passwordbaru" name="_passwordbaru"/>
													</div>
													<div class="form-group">
														<label class="control-label">Ulangi Password Baru</label>
														<input type="password" onkeyup="checkPass();" class="form-control" id="_confirm_password" name="_confirm_password"/>
													</div>

													
													<div class="err" id="results"></div>
													<div class="margin-top-10">

													
														<a href="#" class="btn green" id="btngantipsw" onclick="save_psw()">
															 Ganti Password
														</a>
														<a href="#" class="btn default">
															 Batal
														</a>
													</div>
												</form>
											</div>

										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer');  
?>
	
<script>

function save_bio(){		
var nama    = $("#_nama").val();
var nohp    = $("#_nohp").val();
var email   = $("#_email").val();
var website = $("#_website").val();
var desc    = $("#_desc").val();
var qry = 'nama='+nama+'&nohp='+nohp+'&email='+email+'&website='+website+'&desc='+desc;			
$.ajax({
	url:'<?php echo site_url();?>master_user_profile/savebio',        
	data:qry,
	type:'POST',
	success:function(data){
		console.log(data);
	//$("#success").show().fadeOut(5000);
	alert('Data berhasil disimpan...');
	},
	error:function(data){
		//$("#error").show().fadeOut(5000);
		alert('Data gagal disimpan...');
	}
	});        
}



function save_psw(){
	    $("#loader").hide();      	
		var passnum = $("#_password").val();
		var passnumbaru = $("#_passwordbaru").val();

        $("#results").html('');

		if(passnum.length > 1 && passnumbaru.length > 2) {

			var qry = 'passw='+passnum+'&passwn='+passnumbaru;

			if($("#loader").attr("class") != "loading") {
				$("#loader").addClass("loading");
				$("#loader").show();
			}            
            $.ajax({
                type: "POST",
                url: "<?php echo site_url()?>master_user_profile/savepsw",
                data: qry,
                success: function(html) {
                    if(html == 'ok')
                    {
                        $("#loader").hide();
                    	//$("#results").html('<span style="color:green;">Password Sudah Berhasil Diubah...</span>');
						alert('Password Sudah Berhasil Diubah...');
                    }
                    else
                    {
                    	$("#loader").hide();
                    	//$("#results").html('<span style="color:red;">Password Lama Salah...</span>');
						alert('Password Lama Salah...');
                    }
              	}
            });


		}
	
   };


 function checkPass()
    {

        var pass1 = document.getElementById('_passwordbaru');
        var pass2 = document.getElementById('_confirm_password');

        var message = document.getElementById('results');

        var goodColor = "#66cc66";
        var badColor = "#ff6666";

        message.innerHTML = '';

        if(pass1.value!="" && pass2.value!="")
        {

        if(pass1.value == pass2.value){          
            message.innerHTML = ''
        }else{
            message.innerHTML = '<span style="color:red;">Password Tidak Sama...</span>'
		   //alert('password tidak sama....');
        }
        }
    }
	
</script>
