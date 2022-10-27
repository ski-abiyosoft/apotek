
<?php 
	$this->load->view('template/header');
    $this->load->view('template/body');    	  
?>	


			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					  Payroll<small>Gaji Karyawan </small>
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
							<a href="<?php echo base_url();?>hrd_gaji">
                               Daftar Gaji Karyawan
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url();?>hrd_gaji/add">
                               Hitung Gaji
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
                                                            <label class="col-md-4 control-label">Bulan/Tahun</label>
													        <div class="col-md-8">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $header->bulan.'- '.$header->tahun;?>" onkeypress="return tabE(this,event)" readonly>
													        </div>

														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Tanggal</label>
													        <div class="col-md-8">
														        <input id="tanggal" name="tanggal" class="form-control date-picker input-medium" type="date" value="<?php echo date('Y-m-d',strtotime($header->tglpay));?>" data-date="" placeholder="" readonly />
													    	   
													        </div>
													        


														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">NIK</label>
													        <div class="col-md-8">
                                                               <input type="text" class="form-control" placeholder="" value="<?php echo $header->nip;?>" readonly>
													        </div>

														</div>
													</div>
													
                                                    <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Nama Karyawan</label>
													        <div class="col-md-8">
														        <input type="text" class="form-control" placeholder="" value="<?php echo $header->nama_karyawan;?>" readonly>
													        </div>

														</div>
													</div>

												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Gaji Pokok</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="gapok" name="gapok" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->gapok;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Tunjangan PPH</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="tunjanganpph" name="tunjanganpph" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->tunjanganpph;?>"/>
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Uang Lembur</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="lembur" name="lembur" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->uanglembur;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Uang Transport</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="transport" name="transport" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->uangtransport;?>"/>
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Uang Pulsa</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="pulsa" name="pulsa" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->uangpulsa;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Uang Makan</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="makan" name="makan" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->uangmakan;?>"/>
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">JKM</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="jkm" name="jkm" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->jkm;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">JKK</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="jkk" name="jkk" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->jkk;?>"/>
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Askes</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="askes" name="askes" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->askes;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Potongan</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="potongan" name="potongan" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->potongan;?>"/>
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">PPH</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="pph" name="pph" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->pph;?>"/>
													        </div>

														</div>
													</div>
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Bonus</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="bonus" name="bonus" onClick="totalgaji();formatCurrency(this)" value="<?php echo $header->bonus;?>" />
													        </div>

														</div>
													</div>
												</div>
												
												<div class="row">
													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-4 control-label">Penghasilan Diterima</label>
													        <div class="col-md-8">
                                                               <input class="form-control rightJustified" id="thp" name="thp" onClick="formatCurrency(this)" value="<?php echo $header->thp;?>" readonly />
													        </div>

														</div>
													</div>
												</div>
												
												
											



												

											<div class="form-actions">
												<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
												<a href="<?php echo base_url()?>hrd_gaji" class="btn green"><i class="fa fa-new"></i> Kembali</a>

											</div>
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>
                                            <input type="hidden" name="nomor" value="<?php echo $nomor;?>" />
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

function totalgaji(){
	var gapok = $('#gapok').val();
	var tunjanganpph = $('#tunjanganpph').val();
	var lembur = $('#lembur').val();
	var transport = $('#transport').val();
	var pulsa = $('#pulsa').val();
	var makan = $('#makan').val();
	var jkm = $('#jkm').val();
	var jkk = $('#jkk').val();
	var askes = $('#askes').val();
	var pph = $('#pph').val();
	var potongan = $('#potongan').val();
	var bonus = $('#bonus').val();
	
	var gapok1 = Number(gapok.replace(/[^0-9\.]+/g,""));
	var tunjanganpph1 = Number(tunjanganpph.replace(/[^0-9\.]+/g,""));
	var lembur1 = Number(lembur.replace(/[^0-9\.]+/g,""));
	var transport1 = Number(transport.replace(/[^0-9\.]+/g,""));
	var pulsa1 = Number(pulsa.replace(/[^0-9\.]+/g,""));
	var makan1 = Number(makan.replace(/[^0-9\.]+/g,""));
	var jkm1 = Number(jkm.replace(/[^0-9\.]+/g,""));
	var jkk1 = Number(jkk.replace(/[^0-9\.]+/g,""));
	var askes1 = Number(askes.replace(/[^0-9\.]+/g,""));
	var potongan1 = Number(potongan.replace(/[^0-9\.]+/g,""));
	var pph1 = Number(pph.replace(/[^0-9\.]+/g,""));
	var bonus1 = Number(bonus.replace(/[^0-9\.]+/g,""));
	
	var thp = eval(gapok1)+eval(tunjanganpph1)+eval(lembur1)+eval(transport1)+eval(pulsa1)+
	          eval(makan1)-eval(jkm1)-eval(jkk1)-eval(askes1)-eval(potongan1)-eval(pph1)+eval(bonus1);
		
	$('#thp').val(thp);
	
}
function save()
{	            
	$.ajax({				
		url:'<?php echo site_url('hrd_gaji/ajax_update')?>',				
		data:$('#frmkeuangan').serialize(),				
		type:'POST',
		success:function(data){        		
		swal('','Data berhasil disimpan ...','');								
	
		},
		error:function(data){
			swal('','Gagal menyimpan data ...','');
		}
		});
}	
        

  
  

</script>
</body>
</html>
