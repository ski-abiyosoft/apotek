<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


<link href="<?php echo base_url()?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/select2/select2-metronic.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/jquery-multi-select/css/multi-select.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>
<link href="<?php echo base_url()?>assets/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" />
<link href="<?php echo base_url()?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url()?>assets/css/custom.css" rel="stylesheet" type="text/css"/>

				<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Akuntansi <small>Tutup Buku</small>
					</h3>
					<ul class="page-breadcrumb breadcrumb">

						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url('dashboard')?>">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="#">
                              Akuntansi
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="akuntansi_tutupbuku.php">
                              Tutup Buku
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

					<div class="note note-success">
						<p>
                         Modul ini berfungsi untuk Proses Tutup Buku Periode Berjalan.<br>
                         Pastikan Transaksi sudah selesai sebelum proses ini.
                         <br>
						</p>
					</div>

					<br>

					<div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Parameter Proses
										</div>

									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmtutupbuku" class="form-horizontal form-bordered1" method="post"  >
											<div class="form-body">


                                                <div class="row form-group">
                                                   <label class="control-label col-md-3"> Bulan / Tahun Tutup:</label>
                                                   <div class="col-md-3">

                                                         <select id="blntutup" name="blntutup" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih..." disabled>
                                                         <option value="NONE">&nbsp</option>
                                                         <?php
                                                           for($i=1;$i<=12;$i++)
                                                           { ?>
															 <option value="<?php echo $i;?>" <?php if($bulan==$i){ echo "selected=true";}?>><?php echo $this->M_global->_namabulan($i);?></option>
												           <?php 
                                                           }
                                                         
                                                         ?>

            											</select>

            										</div>
            										<div class="col-md-2">
                                                          <input maxlength="4" type="text" size="3" name="thntutup" id="thntutup" class="form-control" value="<?php echo $tahun;?>" disabled/>
            										</div>

            									</div>
            									
            									<div class="row form-group">
                                                   <label class="control-label col-md-3"> Bulan / Tahun Dibuka :</label>
                                                   <div class="col-md-3">

                                                         <select id="blnbuka" name="blnbuka" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih..." disabled>
                                                         <option value="NONE">&nbsp</option>
                                                         <?php
                                                           for($i=1;$i<=12;$i++)
                                                           { ?>
													         <option value="<?php echo $i;?>" <?php if($bulan_buka==$i){ echo "selected=true";}?>><?php echo $this->M_global->_namabulan($i);?></option>
															 <?php 

                                                           }

                                                         ?>

            											</select>

            										</div>
            										<div class="col-md-2">
                                                          <input maxlength="4" type="text" size="5" name="thnbuka" id="thnbuka" class="form-control" value="<?php echo $tahun_buka;?>" disabled/>
            										</div>

            									</div>

											</div>
											<div class="form-actions fluid">
												<div class="col-md-offset-3 col-md-9">
													<button type="button" class="btn blue" onclick="save()" id="btnproses">Tutup Buku</button>
													
                                                    <br />
                                                    <h4>
													<div class="err" id="resultss"></div>
													</h4>
													
													<div >
													  <img id="proses" src="<?php echo base_url();?>assets/img/loading-spinner-blue.gif" class="img-responsive" style="visibility:hidden"/>
													</div>
												</div>
											</div>
										</form>
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

<script src="<?php echo base_url()?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap2-typeahead.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/clockface/js/clockface.js"></script>


<script>
    
function save(){
     
	 $("#loader").hide();
     
		var thntutup = $("#thntutup").val();
		var thnbuka = $("#thnbuka").val();
		var blntutup = $("#blntutup").val();
		var blnbuka = $("#blnbuka").val();
		
        $("#results").html('');


		if(thntutup.length > 0 && thnbuka.length > 0 && blnbuka.length > 0 && blntutup.length > 0) {
			var qry = 'thntutup='+thntutup+'&thnbuka=' + thnbuka+'&blntutup='+blntutup+'&blnbuka='+blnbuka;

			if($("#loader").attr("class") != "loading") {
				$("#loader").addClass("loading");
				$("#loader").show();
			}

			document.getElementById('proses').style.visibility="visible";
		    document.getElementById('btnproses').disabled=true;			
			
            $.ajax({
                type: "POST",                
				url:'<?php echo site_url('akuntansi_tutupbuku/tutupbuku')?>',	
                data: qry,
                success: function(html) {

                    document.getElementById('btnproses').disabled=false;
                    document.getElementById('proses').style.visibility="hidden";
                    if(html.trim() == "ok")
                    {
                        $("#loader").hide();
                    	$("#resultss").html('<span style="color:blue;">Proses Selesai...</span>');
                    }
                    else if(html.trim() == "not ok")
                    {
                    	$("#loader").hide();
                    	$("#resultss").html('<span style="color:red;">Data sudah pernah diproses...</span>');
                    } else
                    {
                    	$("#loader").hide();
                    	$("#resultss").html('<span style="color:red;">Tidak ada data yang diproses...</span>');
                    }
              	}
            });
		}
		e.preventDefault();

	
};

	
</script>