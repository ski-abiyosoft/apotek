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
                      <span class="title-web">Kas/Bank <small>Pengeluaran Kas</small>
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
							<a href="<?php echo base_url();?>keuangan_keluar">
                               Daftar Pengeluaran Kas/Bank
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Pengeluaran Kas/Bank
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Form Entri Pengeluaran
                    </div>
                    <div class="tools">
                            

                    </div>

                    
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <br>
                    <form id="frmkeuangan" class="form-horizontal" method="post">
                        <div class="form-body">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">No. Transaksi<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            
                                            <div class="input-group">														        
                                                <input type="text" class="form-control" placeholder="Nomor Otomatis" readonly name="nomorbukti" value="<?php echo $header->bayarno;?>" id="nomorbukti" value="" onkeypress="return tabE(this,event)">																																															
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!--/span-->
                                
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Kas/Bank<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select name="kasbank" id="kasbank" class="select2_el_kasbank form-control" >
												<option value="<?= $header->accountno;?>"><?= $header->accountno." | ".$header->acname;?></option>
											</select>
                                        </div>

                                    </div>
                                </div>
                                <!--/span-->
                                <?php 
                                    // print_r($jenis_pembayaran);
                                ?>
                                <div class="col-md-2">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Jenis<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select name="jenis" id="jenis" class="form-control" >
                                                <option value="" disabled selected>-- Pilih Jenis --</option>
                                                <?php
													$selected = '';
                                                    foreach($jenis_pembayaran as $key){
														if($key->id == $header->jenisbayar) $selected = 'selected';
                                                        echo "<option $selected value='".$key->id."'>".$key->jenis."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Tanggal<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d',strtotime($header->tglbayar));?>" /> 
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Keterangan<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input id="keterangan" name="keterangan" class="form-control " type="text" value="<?php echo $header->keterangan;?>"/> 
                                        </div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">No Mohon<font color="red">*</font></label>
                                        <div class="col-md-6">
                                            <input id="no_mohon" name="no_mohon" class="form-control input-medium" type="text" value="<?php echo $header->nokasbon;?>"/> 
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Jumlah<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <input id="jumlah" name="jumlah" class="form-control " type="text" value="<?php echo $header->jmbayar;?>" data-type='currency'/> 
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->                                
                                <div class="col-md-2">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Cek/<br/>Giro<font color="red">*</font></label>
                                        <!-- <label class="col-md-3 control-label">Jumlah<font color="red">*</font></label> -->
                                        <div class="col-md-9">
                                            <input id="cekgiro" name="cekgiro" class="form-control" type="text" value="<?php echo $header->nocek;?>"/> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                     
                                </div>
                                <!--/span-->
                                <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="col-md-3 control-label">Cost Centre<font color="red">*</font></label>
                                        <div class="col-md-9">
                                            <select name="cost_centre" id="cost_centre" class="select2_el_costcentre form-control" >
                                            	<option value="<?= $header->depid;?>"><?= $header->depid." | ".$header->namadep;?></option>
											</select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            
                            <div class="row">
                                <div class="col-md-12">
                                <div class="table-responsive" >
                                <table id="datatable" class="table table-bordered- table-condensed">
                                <thead class="breadcrumb">
                                    <tr>                    									
                                    <th width="20%" style="text-align: center">No. Perkiraan</th>
                                    <th width="60%" style="text-align: center">Uraian</th>
                                    <th width="20%" style="text-align: center">Jumlah</th>                    									
                                    </tr>
                                </thead>
                                                                            
                                <tbody>
								<tbody>
									<?php 
										$no = 1;
										foreach($detil as $row){ ?>
										<tr>
											<td width="20%">
												<select name="akun[]" id="akun<?= $no;?>" class="select2_el form-control" style="width:300px" >
													<option value="<?= $row->accountno;?>"><?= $row->accountno." | ".$row->acname;?></option>
												</select>
											</td>
											
											<td width="60%" ><input name="ket[]"    value="<?= $row->keterangan;?>" id="ket<?= $no;?>" type="text" class="form-control " size="100%" onkeypress="return tabE(this,event)"></td>
											<td width="20%" ><input name="jumlah[]" value="<?= $row->jumlah;?>" id="jumlah<?= $no;?>" data-type="currency" type="text" class="form-control rightJustified" size="40%" value="0" onkeyup="total()" data-type="currency" onkeypress="return tabE(this,event);formatCurrency(this)"></td>
										</tr>
										<?php 
										$no++;
									} ?>
                                </tbody>
                                </div>
                                <tfoot>
                                    <tr>
                                        <td width="20%"><button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
                                        <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button></td>
                                        <td width="60%" align="right">TOTAL</td>
                                        <td width="20%" align="right"><font color="red"><b><input class="form-control rightJustified" type="text" id="_jumlah" readonly><b></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                </div>
                                </div>
                            </div>
                            

                        <div class="form-actions">
                            
                            <button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>																								
                            <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-edit"></i> Data Baru</button>
                            <button type="button" class="btn red" onclick="javascript:history.go(-1)"><i class="fa fa-undo"></i> Kembali</button>						
                    
                        </div>
                        <h2><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h2>
                        <input type="hidden" name="register" id="register" value="<?php echo $register;?>" />
											
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
    
$("input[data-type='currency']").on({
    blur: function() { 
		var val = this.value.replaceAll(',','').split('.');
		this.value = currencyFormat(val[0]);
    }
});

function currencyFormat (num) {
	return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


$('#_jumlah').val(0);

var idrow = 2;

function tambah(){
    var table      = document.getElementById('datatable');
	idrow          = table.rows.length - 1;
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
  
	var akun1="<select name='akun[]' class='select2_el form-control select2me' id='akun'"+idrow+" style='width:300px' ><option value=''>--- Pilih Akun ---</option></select>";	
	td1.innerHTML=akun1; 
	td2.innerHTML="<input name=ket[]'    type='text' class='form-control'>";
	td3.innerHTML="<input name='jumlah[]'  data-type='currency' type='text' class='form-control rightJustified' size='40%' value='0' onkeyup='total()'>";
    initailizeSelect2();
	idrow++;


    
    $("input[data-type='currency']").on({
        keyup: function() {
            formatCurrency($(this));
        },
        blur: function() { 
            var val = this.value.replaceAll(',','').split('.');
            this.value = currencyFormat(val[0]);
        }
    });

    function currencyFormat (num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

        
    function formatCurrency(input, blur) {
        var input_val = input.val();
        
        if (input_val === "") { return; }
        
        var original_len = input_val.length;

        var caret_pos = input.prop("selectionStart");
            
        if (input_val.indexOf(".") >= 0) {

            var decimal_pos = input_val.indexOf(".");

            var left_side = input_val.substring(0, decimal_pos);
            var right_side = input_val.substring(decimal_pos);

            left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);
            
            if (blur === "blur") {
            right_side += "00";
            }
            
            right_side = right_side.substring(0, 2);

            input_val = "" + left_side + "." + right_side;

        } else {
            input_val = formatNumber(input_val);
            input_val = "" + input_val;
            
            if (blur === "blur") {
            input_val += ".00";
            }
        }
        
        input.val(input_val);

        
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
    }
}

function save()
{	        
    var tanggal     = $('[name="tanggal"]').val();
	var no_mohon    = $('#no_mohon').val();
	var kasbank     = $('#kasbank').val();
	var keterangan  = $('#keterangan').val();
	var jumlah      = $('#jumlah').val();
	var cost_centre = $('#cost_centre').val();
	var jenis       = $('#jenis').val();
	var cekgiro     = $('#cekgiro').val();
	var total       = $('#_jumlah').val();
	
	if(total==0 || total==""
        || tanggal =="" 
        || no_mohon =="" 
        || kasbank =="" || kasbank == null
        || keterangan =="" 
        || jumlah =="" 
        || cost_centre =="" || cost_centre == null 
        || jenis =="" || jenis == null
        || cekgiro =="" ){
		 swal('','Data belum lengkap...','')
                        
	} else {
        console.log($('#frmkeuangan').serialize());

        var val = total.replaceAll(',','').split('.');
        console.log(jumlah + " - " + val[0]);
        if(jumlah != val[0]){
            swal('','Maaf, jumlah dan total tidak sama.','')
        } else {
            $.ajax({				
                url:'<?php echo site_url('keuangan_keluar/pengeluaran_save/2')?>',				
                data:$('#frmkeuangan').serialize(),				
                type:'POST',
                success:function(data){        		
                    swal({
                                title: "PENGELUARAN KAS/BANK",
                                html: "<p> No. Bukti   : <b>"+data+"</b> </p>"+ 
                                "Tanggal :  " + moment(tanggal).format('DD/MM/YYYY'),
                                type: "info",
                                confirmButtonText: "OK" 
                                }).then((value) => {
                                        location.href = "<?php echo base_url()?>keuangan_keluar";
                    });								
            
                },
                error:function(data){
                    $("#error").show().fadeOut(5000);
                }
            });
        }
	}	
}	
        

   
	function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
			total();
		}
	}

  function total()
  {
    
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;

   tjumlah=0;
  
   
   for(var i=1; i<rowCount-1; i++) 
   {
    var row = table.rows[i];
    
	jumlah      = row.cells[2].children[0].value;
    var jumlah1 = Number(jumlah.replace(/[^0-9\.]+/g,""));

   	tjumlah  = tjumlah  + eval(jumlah1);
		  
    
   }
   document.getElementById("_jumlah").value=formatCurrency1(tjumlah);


  }
  
  
total();
  

</script>

</body>
</html>