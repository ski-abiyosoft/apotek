
<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


			<div class="row">
				<div class="col-md-12">
                    <h3 class="page-title">
                    	<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      	<span class="title-web">Kas/Bank <small>Mutasi Kas/Bank</small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url();?>dashboard">
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url();?>keuangan_transfer">
                               Daftar Mutasi Kas/Bank
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="">
                               Edit Mutasi Kas/Bank
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Form Entri
                    </div>
                    
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form id="frmkeuangan" class="form-horizontal" method="post">
                        <div class="form-body">
                            <h4 class="form-section">Deskripsi</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Nomor Mutasi<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nomorbukti;?>" onkeypress="return tabE(this,event)" readonly>
                                        </div>

                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal Mutasi<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <?php                                                 
                                                $dateMutasi = date_create($tglmutasi);
                                                $dateMutasi = date_format($dateMutasi,"Y-m-d");
                                            ?>
                                            <input id="tglmutasi" name="tglmutasi" class="form-control date-picker input-medium" type="date" value="<?php echo $dateMutasi; ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Dari Kas/Bank<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select id="acdari" name="acdari" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <option value="">--- Pilih ---</option>
                                                <?php 
                                                // kasbank=1 and aclevel=4
                                                    $dt = $this->db->get_where("tbl_accounting", 
                                                            array(
                                                                "accountno" => $acdari,
                                                                "kasbank" => 1,
                                                                "aclevel" => 4,
                                                            )
                                                        )->result();
                                                    foreach($dt as $row){ 
                                                    $selected = ($row->accountno==$acdari ? 'selected' : '');
                                                ?>
                                                
                                                <option <?= $selected;?> value="<?= $row->accountno;?>"><?= $row->accountno." | ".$row->acname;?></option>
                                                
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                
                                
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Ke Kas/Bank<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select id="acke" name="acke" class="form-control select2_el_kasbank" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <option value="">--- Pilih ---</option>
                                                <?php 
                                                // kasbank=1 and aclevel=4
                                                    $dt = $this->db->get_where("tbl_accounting", 
                                                            array(
                                                                "accountno" => $acke,
                                                                "kasbank" => 1,
                                                                "aclevel" => 4,
                                                            )
                                                        )->result();
                                                    foreach($dt as $row){ 
                                                    $selected = ($row->accountno==$acke ? 'selected' : '');
                                                ?>
                                                
                                                <option <?= $selected;?> value="<?= $row->accountno;?>"><?= $row->accountno." | ".$row->acname;?></option>
                                                
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">
                                <!--/span-->

                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">No Cek/Giro<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input class="form-control" type="text" rows="2" id="cekno" name="cekno" onClick="" value="<?php echo $cekno;?>"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Jumlah Mutasi<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input class="form-control" rows="2" id="mutasirp" name="mutasirp"  value="<?php echo $mutasirp;?>" data-type="currency"/>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            
                            <div class="row">
                                <!--/span-->
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Biaya Admin<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input class="form-control" rows="2" id="admrp" name="admrp"  value="<?php echo $admrp;?>" data-type="currency"/> <!-- type="number" -->
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Akun Biaya<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select id="acbiaya" name="acbiaya" class="form-control select2_el_akunbiaya" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
                                                <option value="">--- Pilih ---</option>
                                                <?php 
                                                    $dt = $this->db->get_where("tbl_accounting", array("accountno" => $acbiaya))->result();
                                                    print_r($dt);
                                                    foreach($dt as $row){ 
                                                    $selected = ($row->accountno==$acbiaya ? 'selected' : '');
                                                ?>
                                                
                                                <option <?= $selected;?> value="<?= $row->accountno;?>"><?= $row->acname;?></option>
                                                
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <!--/span-->
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Keterangan<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" rows="2" id="keterangan" name="keterangan" onkeypress="return tabE(this,event)"><?php echo $keterangan;?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                        <div class="form-actions">
                            <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
                            <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-edit"></i> Data Baru</button>
                            <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i class="fa fa-undo"></i> Kembali</button>

                        </div>
                        <h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>

                    </form>
                </div>
            </div>
		</div>
	</div>
</div>
<?php
  $this->load->view('template/footer');
?>

<script>

jQuery(document).ready(function() {
 
   ComponentsPickers.init();
});


$("input[data-type='currency']").on({
    blur: function() { 
		var val = this.value.replaceAll(',','').split('.');
		this.value = currencyFormat(val[0]);
    }
});

function currencyFormat (num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function save()
{	          
	var nomorbukti  = $('#nomorbukti').val();
    var tglmutasi  	= $('[name="tglmutasi"]').val();
	var acdari    	= $('#acdari').val();
	var acke    	= $('#acke').val();
	var cekno     	= $('#cekno').val();
	var mutasirp    = $('#mutasirp').val();
	var admrp    	= $('#admrp').val();
	var acbiaya     = $('#acbiaya').val();
	var keterangan	= $('#keterangan').val();

	if(acdari=="" || acke=="" || cekno=="" || mutasirp =="" || admrp =="" || acbiaya == "" || keterangan == ""){
		swal('','Maaf, mohon isi data Anda dengan lengkap!','')  
	} else {  
		if(acdari == acke){
			swal('','Maaf, mohon cek dari dan ke Kas/Bank dengan benar!','')  
		} else {			
			console.log($('#frmkeuangan').serialize());

			console.log(moment(tglmutasi).format('DD-MM-YYYY'));
			$.ajax({				
				url:'<?php echo site_url('keuangan_transfer/transfer_save/2')?>',				
				data:$('#frmkeuangan').serialize(),				
				type:'POST',
				success:function(data){        		
					swal({
								title: "MUTASI KAS/BANK",
								html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
								"Tanggal :  " + moment(tglmutasi).format('DD/MM/YYYY'),
								type: "info",
								confirmButtonText: "OK" 
								}).then((value) => {
										location.href = "<?php echo base_url()?>keuangan_transfer";
					});								
				
				},
				error:function(data){
					$("#error").show().fadeOut(5000);
				}
			});
		}
	}	
}	
        
window.onload = function(){
        document.getElementById('nomorbukti').focus();
};



</script>

<!-- <script src="https://momentjs.com/downloads/moment-with-locales.min.js" type="text/javascript"></script> -->
</body>
</html>
