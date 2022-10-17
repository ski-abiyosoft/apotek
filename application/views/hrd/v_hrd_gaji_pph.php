<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


				<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					Payroll <small>Hitung PPH</small>
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
							<a href="<?= base_url('hrd_gaji');?>">
                              Payroll
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                              PPH Final
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

					<div class="note note-success">
						<p>
                         Modul ini berfungsi untuk Proses Perhitungan PPH Final.<br>
                         Tentukan Bulan dan Tahun serta Tanggal proses Gaji.
                         <br>
						</p>
					</div>

					<br>

					<div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Periode
										</div>

									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmtutupbuku" class="form-horizontal form-bordered1" method="post"  >
											<div class="form-body">


                                                <div class="row form-group">
                                                   <label class="control-label col-md-3"> Bulan / Tahun Gaji :</label>
                                                   <div class="col-md-3">

                                                         <select id="bulan" name="bulan" class="form-control input-sm select2me input-medium"  data-placeholder="Pilih...">
                                                         <option value="NONE">&nbsp;</option>
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
                                                          <input maxlength="4" type="text" size="3" name="tahun" id="tahun" class="form-control" value="<?php echo $tahun;?>" />
            										</div>

            									</div>
            									
            									<div class="row form-group">
                                                   <label class="control-label col-md-3"> Tanggal Gaji :</label>
                                                 
            										<div class="col-md-3">
                                                          <input  type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo $tanggal;?>" />
            										</div>

            									</div>

											</div>
											<div class="form-actions fluid">
												<div class="col-md-offset-3 col-md-9">
													<button type="button" class="btn yellow" onclick="save()" id="btnproses">Hitung PPH</button>
													
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


<script>
    
function save(){
     
	 $("#loader").hide();
     
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		var tanggal = $("#tanggal").val();
		
        $("#results").html('');


		if(tahun.length > 0 && bulan.length > 0 && tanggal.length > 0) {
			var qry = 'bulan='+bulan+'&tahun=' + tahun+'&tanggal='+tanggal;

			if($("#loader").attr("class") != "loading") {
				$("#loader").addClass("loading");
				$("#loader").show();
			}

			document.getElementById('proses').style.visibility="visible";
		    document.getElementById('btnproses').disabled=true;			
			
			
            $.ajax({
                type: "POST",                
				url:'<?php echo base_url('hrd_gaji/hitungpph')?>',	
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
                    	$("#resultss").html('<span style="color:red;">Data gaji belum diproses...</span>');
                    } else
                    {
                    	$("#loader").hide();
                    	$("#resultss").html('<span style="color:red;">Tidak ada data yang diproses...</span>');
                    }
              	}
            });
		} else {
		  swal('','Ada kolom yang masih kosong ...','');	
		}
		e.preventDefault();

	
};

	
</script>