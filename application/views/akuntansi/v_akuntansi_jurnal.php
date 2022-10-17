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
                      <span class="title-web">Buku Besar <small>Entri Jurnal</small>
					</h3>
                    <ul class="page-breadcrumb breadcrumb">
						<li>
							<i class="fa fa-home"></i>
							<a href="<?php echo base_url()?>dashboard"/>
                               Awal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="<?php echo base_url()?>akuntansi_ju"/>
                               Daftar Jurnal
							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>
							<a href="<?php echo base_url()?>akuntansi_jurnal"/>
                               Entri Jurnal
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-reorder"></i>Data Jurnal
										</div>
										<div class="tools">
											<!--a href="javascript:;" class="reload">
											</a-->

										</div>
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
										<form id="frmjurnal" action="#" class="form-horizontal" method="post">
											<div class="form-body">
												<!--h4 class="form-section">Deskripsi</h4-->
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
                                                            <label class="col-md-3 control-label">Nomor Bukti</label>
													        <div class="col-md-6">
														        <input type="text" class="form-control" placeholder="" name="nomorbukti" id="nomorbukti" value="<?php echo $nojurnal;?>" readonly>
													        </div>

														</div>
													</div>
													<!--/span-->
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-6">
														       
															    <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d');?>"/>
													    	   
													        </div>
													        


														</div>
													</div>
												</div>
												
												<div class="row">
													<!--/span-->

													
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Cabang</label>
													        <div class="col-md-6">
                                                              <select id="cabang" name="cabang" class="form-control input-mediumx select2me" data-placeholder="Pilih..." onkeypress="return tabE(this,event)" required>
            											
                                                            <?php 
									                            foreach($unit->result_array() as $row){?>
            													<option value="<?php echo $row['koders'];?>"><?php echo $row['namars'];?></option>
                                                            <?php } ?>
            												</select>
													        </div>

														</div>
													</div>

													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Keterangan</label>
													        <div class="col-md-9">
														        <textarea class="form-control" rows="2" id="keterangan" name="keterangan" onkeypress="return tabE(this,event)"></textarea>
														      
													        </div>

														</div>
													</div>
													
												</div>
												
												<div class="row">
													<!--/span-->

													
													<div class="col-md-6">
                                                        
													</div>

													<!--div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">No. Ref</label>
													        <div class="col-md-9">														        
														        <input type="text" class="form-control" placeholder="" name="noref" value="" id="noref" onkeypress="return tabE(this,event)">
													        </div>

														</div>
													</div-->
													
												</div>

												<div class="row">
												 <div class="col-md-12">
                                                   <table id="datatable" class="table table-borderedx table-condensed-">
								                    <thead class="breadcrumb">
                                                      <tr>
                    								
                    									<th width="20%" style="text-align: center">Kode Akun</th>														
                    									<th width="30%" style="text-align: center">Uraian</th>
                    									<th width="15%" style="text-align: center">Debet</th>
                    									<th width="15%" style="text-align: center">Kredit</th>
														<th width="20%" style="text-align: center">Dept</th>
                    								</tr>
                    								</thead>
													
                    								<tbody>
													<tr>
													
														<td width="20%">
														  <select name="akun[]" id="akun1" class="select2_el form-control">
														  </select>
														</td>
																									                                                       
                                                        <td width="30%" >
														  <input name="ket[]"    id="ket1" type="text" class="form-control " size="100%" onkeypress="return tabE(this,event)">															
														</td>
                                                        <td width="15%" ><input name="debet[]"  id="debet1" type="text"  class="form-control rightJustified" size="40%" value="0" onkeyup="total()"   data-type="currency" ></td>
                                                        <td width="15%" ><input name="kredit[]" id="kredit1" type="text" class="form-control rightJustified" size="40%" value="0" onkeyup="total()"   data-type="currency" ></td>
								                        <td width="20%">
														  <select name="dept[]" id="dept1" class="select2_el_dept form-control">
														  </select>
														</td>
													  </tr>
                    								
								                    </tbody>
													<tfoot>
                                                      <tr>
													   		
													    <td width="20%"><button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
												        <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button></td>
                                                        <td width="30%" align="center"><font color="red"><b><input type="text" class="form-control" id="_selisih" disabled></b></font></td>
                                                        
                                                        <td width="15%"  align="right"><font color="red"><b><input type="text" class="form-control rightJustified" id="_jumdebet" disabled></b></font></td>
														<td width="15%"  align="right"><font color="red"><b><input type="text" class="form-control rightJustified" id="_jumkredit" disabled></b></font></td>
														<td></td>
                                                      </tr>
                                                     </tfoot>
								                    </table>
								                    </div>
								                   
												</div>
												

											<div class="form-actions">
	                                            
												<button id="btnsimpan" type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
                                                <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-edit"></i> Data Baru</button>
											</div>
											<h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>

										</form>
									</div>
								</div>
		</div>
	</div>
</div>

<?php
  $this->load->view('template/footer_tb');
?>


<script>
 var idrow = 2;


function save()
{	         
    var noform   = $('[name="nomorbukti"]').val();  
    var tanggal  = $('[name="tanggal"]').val();	
	if(noform==""){
		
	} else {
	$.ajax({				
		url:'<?php echo site_url('akuntansi_jurnal/jurnal_add')?>',				
		data:$('#frmjurnal').serialize(),				
		type:'POST',
		
		success:function(data){ 
		  swal({
					  title: "JURNAL UMUM",
					  html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>akuntansi_ju";
		         });				
	
		},
		    error:function(data){
			$("#error").show().fadeOut(5000);
			
			
								
					
		}
		});
	}	
	
	
}		
        
function total(){
   try {
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tdebet=0;
   tkredit=0;
   
   for(var i=1; i<rowCount-1; i++) 
   {
    var row = table.rows[i];
    
	debet      = row.cells[2].children[0].value;
    var debet1 = Number(debet.replace(/[^0-9\.]+/g,""));

    kredit     = row.cells[3].children[0].value;
    var kredit1= Number(kredit.replace(/[^0-9\.]+/g,""));

	tdebet  = tdebet  + eval(debet1);
	tkredit  = tkredit  + eval(kredit1);
   }	  
   
   
    document.getElementById("_jumdebet").value=formatCurrency1(tdebet);
    document.getElementById("_jumkredit").value=formatCurrency1(tkredit);
	
	if(tdebet>0 || tkredit>0)
	{
	if (tdebet>tkredit)
    {
      selisih = tkredit-tdebet;
      ket = "(Kredit)";
      document.getElementById("_selisih").value="Selisih "+formatCurrency1(selisih)+" "+ket;
      document.getElementById("btnsimpan").disabled=true;
     
    } else
    if (tdebet<tkredit)
    {
      selisih = tdebet-tkredit;
      ket = "(Debet)";
      document.getElementById("_selisih").value="Selisih "+formatCurrency1(selisih)+" "+ket;
      document.getElementById("btnsimpan").disabled=true;      
    } else
    {
      selisih = 0;
      ket = "";
      document.getElementById("_selisih").value="";
      document.getElementById("btnsimpan").disabled=false;      
    }
	} else
	{
	  document.getElementById("_selisih").value="";
      document.getElementById("btnsimpan").disabled=true;      
	}
	
   }catch(e) {
    alert(e);
   }
   
   
  }
  
  
function hapus(){
	if(idrow>2){
        var x=document.getElementById('datatable').deleteRow(idrow-1);
        idrow--;
		total();
    }
}


function tambah(){		
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
    var td4=x.insertCell(3);
	var td5=x.insertCell(4);
	 
	var akun="<select name='akun[]' class='select2_el form-control select2me' id='akun'"+idrow+" ></select>";
	var dept="<select name='dept[]' class='select2_el_dept form-control select2me' id='dept'"+idrow+" ></select>";
	
	 			
	td1.innerHTML=akun;
	td2.innerHTML="<input name='ket[]'    type='text' class='form-control'>";
    td3.innerHTML="<input name='debet[]'  type='text' class='form-control rightJustified' size='40%' value='0' onkeyup='total()'  data-type='currency' >";
	td4.innerHTML="<input name='kredit[]'  type='text' class='form-control rightJustified' size='40%' value='0' onkeyup='total()' data-type='currency'  >";
	td5.innerHTML=dept;
	initailizeSelect2();
	initailizeSelect2_dept();
    idrow++;
}
 

</script>




</body>
</html>
