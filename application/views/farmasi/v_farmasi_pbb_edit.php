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
            <span class="title-web">Farmasi <small>Permohonan Barang Ke Gudang</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i style="color:white;" class="fa fa-home"></i>
                <a class="title-white" href="<?php echo base_url();?>dashboard">
                    Awal
                </a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="<?php echo base_url();?>farmasi_pbb">Daftar Permohonan</a>
                <i style="color:white;" class="fa fa-angle-right"></i>
            </li>
            <li>
                <a class="title-white" href="">Edit Permohonan</a>
            </li>
        </ul>
    </div>
</div>

<div class="portlet box blue" style="margin-bottom:30px !important">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-reorder"></i> Edit Data</div>
    </div>

    <div class="portlet-body form">
        <form id="frmpermohonan" class="form-horizontal" method="post"
            style="padding-bottom:0px !important;margin-bottom:0 !important">
            <div class="form-body" style="padding-bottom:0px !important;margin-bottom:0 !important">
                <div class="tabbable tabbable-custom tabbable-full-width"
                    style="padding-bottom:0px !important;margin-bottom:0 !important">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Tanggal</label>
                                <div class="col-md-4">
                                    <input id="tanggal" name="tanggal" class="form-control" type="date"
                                        value="<?php echo date('Y-m-d');?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Gudang Asal</label>
                                <div class="col-md-9">
                                    <select id="gudang_asal" name="gudang_asal"
                                        class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..."
                                        onkeypress="return tabE(this,event)">
                                        <?php if($header->dari): $namagudang = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan;?>
                                        <option value="<?= $header->dari;?>"><?= $namagudang;?></option>
                                        <?php endif; ?>
                                    </select>
                                    <!-- <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)"></select>	 -->
                                    <!-- <?php $namagudang = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan; ?> 
									<select type="text" class="form-control" name="gudang_asal" id="gudang_asal">
										<option value="<?= $header->dari;?>"><?= $namagudang ?></option>
									</select> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Gudang Tujuan</label>
                                <div class="col-md-9">
                                    <select id="gudang_tujuan" name="gudang_tujuan"
                                        class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..."
                                        onkeypress="return tabE(this,event)">
                                        <?php if($header->ke): $namagudang = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan;?>
                                        <option value="<?= $header->ke;?>"><?= $namagudang;?></option>
                                        <?php endif; ?>
                                    </select>
                                    <!-- <select id="gudang_tujuan" name="gudang_tujuan" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)"></select>	 -->
                                    <!-- <?php $namagudang = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan; ?> 
									<select type="text" class="form-control" name="gudang_tujuan" id="gudang_tujuan">
										<option value="<?= $header->ke;?>"><?= $namagudang ?></option>
									</select> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pemohonan No.</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="hidden" name="hidenomorbukti" id="hidenomorbukti"
                                            value="<?= $header->mohonno;?>">
                                        <input type="text" name="nomorbukti" id="nomorbukti" class="form-control"
                                            value="<?= $header->mohonno;?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Keterangan</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="ket"
                                        value="<?= $header->keterangan ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding:20px">
                    <table id="datatable" class="table table-striped table-condensed table-scrollable">
                        <thead>
                            <tr>
                                <th style="text-align: center;width:28%">Kode/Nama Barang</th>
                                <th style="text-align: center;width:10%">Kuantitas</th>
                                <th style="text-align: center;width:10%">Satuan</th>
                                <th style="text-align: center;width:16%">Harga</th>
                                <th style="text-align: center;width:16%">Total</th>
                                <th style="text-align: center;width:16%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $totalharga = 0;
                             ?>
                            <?php $no = 1; foreach($detil as $dkey => $dval): ?>
                            <tr>
                                <td>
                                    <select name="kode[]" id="kode<?= $no ?>"
                                        class="select2_el_farmasi_baranggud form-control"
                                        onchange="getbarang(<?= $no ?>)">
                                        <?php 
											if($dval->kodebarang){
												$barang = data_master('tbl_barang', array('kodebarang' => $dval->kodebarang));
										?>
                                        <option value="<?= $dval->kodebarang;?>">
                                            <?= '['. $barang->kodebarang.'] - ['.$barang->namabarang.'] - ['.$barang->satuan1 .'] - ['. $barang->hargajual .']';?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input name="qty[]" id="qty<?= $no ?>" type="text" class="form-control"
                                        onkeyup="total(<?= $no ?>)"
                                        value="<?= str_replace(".00", "",$dval->qtymohon) ?>"></td>
                                <td><input name="sat[]" id="sat<?= $no ?>" type="text" class="form-control"
                                        value="<?= $dval->satuan ?>"></td>
                                <td><input name="harga[]" id="harga<?= $no ?>" type="text" class="form-control"
                                        value="<?= number_format($dval->harga, 0, '.', ',') ?>.00" readonly></td>
                                <td><input name="total[]" id="total<?= $no ?>" type="text"
                                        class="form-control sumsubtot"
                                        value="<?= number_format($dval->totalharga, 0, '.', ',') ?>.00" readonly></td>
                                <td><input name="note[]" id="note<?= $no ?>" type="text" class="form-control"
                                        value="<?= $dval->keterangan ?>"></td>
                            </tr>
                            <?php
                            $totalharga += $dval->totalharga;
                            ?>

                            <?php $no++; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <button type="button" onclick="tambah()" class="btn green"><i
                                            class="fa fa-plus"></i></button>
                                    <button type="button" onclick="hapus()" class="btn red"><i
                                            class="fa fa-trash-o"></i></button>
                                </td>

                                <td colspan="2">&nbsp;</td>
                                <td style="text-align:right"><span style="padding-top:8px;display:block">TOTAL</span>
                                </td>
                                <td><input type="text" class="form-control" name="vtotal" id="vtotal" readonly></td>
                                <!-- <td><input type="text" class="form-control" name="vtotal" id="vtotal"
                                        value="<?=number_format($totalharga);?>" readonly></td>
                                <td></td> -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div style="padding:20px;padding-top:0px">
                <button type="button" class="btn blue" style="font-weight:bold" id="save_pembayaran" onclick="save()"><i
                        class="fa fa-save fa-fw"></i>&nbsp; Simpan</button>
                        <a class="btn red" href="<?php echo base_url('Farmasi_pbb/')?>">
                            <i class="fa fa-undo"></i><b> KEMBALI </b>
                        </a>
            </div>
        </form>
    </div>
</div>

<?php
	$this->load->view('template/footer');
	$this->load->view('template/footer_all');
?>

<script>
$(window).on("load", function() {
    // sequentPenjualan();
    var gud = $("#gudang_tujuan").val();
    initailizeSelect2_farmasi_baranggud(gud);

    totalline();
});

var idrow = "<?= $jumdata+1;?>";

function tambah(gudang) {
    var v = document.getElementById('datatable').insertRow(idrow);
    var tdv1 = v.insertCell(0);
    var tdv2 = v.insertCell(1);
    var tdv3 = v.insertCell(2);
    var tdv4 = v.insertCell(3);
    var tdv5 = v.insertCell(4);
    var tdv6 = v.insertCell(5);

    var novoucher = '<select name="kode[]" id="kode' + idrow +
        '" class="select2_el_farmasi_baranggud form-control" onchange="getbarang(' + idrow + ')"></select>';
    tdv1.innerHTML = novoucher;
    tdv2.innerHTML = '<input name="qty[]" id="qty' + idrow + '" type="text" class="form-control" onkeyup="total(' +
        idrow + ')">';
    tdv3.innerHTML = '<input name="sat[]" id="sat' + idrow + '" type="text" class="form-control">';
    tdv4.innerHTML = '<input name="harga[]" id="harga' + idrow + '" type="text" class="form-control" readonly>';
    tdv5.innerHTML = '<input name="total[]" id="total' + idrow +
        '" type="text" class="form-control sumsubtot" readonly>';
    tdv6.innerHTML = '<input name="note[]" id="note' + idrow + '" type="text" class="form-control">';
    var gud = $("#gudang_tujuan").val();
    initailizeSelect2_farmasi_baranggud(gud);
    idrow++;
}

// function checkstock(param){
// 	var gudang 		= $("#gudang_asal").val();
// 	var kodebarang	= $("#kode"+ param).val();

// 	if(gudang == null){
// 		swal({
// 			title: "Kesalahan",
// 			html: "Pilih Gudang Asal Terlebih Dahulu",
// 			type: "error",
// 			confirmButtonText: "Ok" 
// 		}).then(function(isConfirm){
// 			$('#kode'+ param).empty();
// 			$('#qty'+ param).val("");
// 			$('#sat'+ param).val("");
// 			$('#harga'+ param).val("");
// 			$('#total'+ param).val("");
// 			$('#note'+ param).val("");
// 		});
// 	} else {
// 		$.ajax({
// 			url: "/farmasi_pbb/checkstock/?kode="+ kodebarang +"&gudang="+ gudang,
// 			type: "GET",
// 			dataType: "JSON",
// 			success: function(data){
// 				if(data.status == 0){
// 					swal({
// 						title: "Kesalahan",
// 						html: "Barang Tidak Tersedia",
// 						type: "error",
// 						confirmButtonText: "Ok" 
// 					});
// 					$('#kode'+ param).empty();
// 					$('#qty'+ param).val("");
// 					$('#sat'+ param).val("");
// 					$('#harga'+ param).val("");
// 					$('#total'+ param).val("");
// 					$('#note'+ param).val("");
// 				} else {
// 					if(data.stock == 0){
// 						swal({
// 							title: "Kesalahan",
// 							html: "Stock Tidak Cukup",
// 							type: "error",
// 							confirmButtonText: "Ok" 
// 						});
// 						$('#kode'+ param).empty();
// 						$('#qty'+ param).val("");
// 						$('#sat'+ param).val("");
// 						$('#harga'+ param).val("");
// 						$('#total'+ param).val("");
// 						$('#note'+ param).val("");
// 					}
// 				}
// 			}
// 		});
// 	}
// }

// function sequentPenjualan(){
//     $.ajax({
//         url: "/farmasi_pbb/get_last_number/NO_MOHON",
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){
//             $("#nomorbukti").val("AUTO");
// 			$("#hidenomorbukti").val(data.lastno);
//         },
//         error: function (data,xhr, ajaxOptions, thrownError) {
//             alert("error get no tr");
//         }
//     });
// }

// function secondPenjualan(){
//     $.ajax({
//         url: "/farmasi_pbb/get_recent_number/NO_MOHON",
//         type: "GET",
//         dataType: "JSON",
//         success: function(data){
//             $("#nomorbukti").val(data.notr);
// 			$("#hidenomorbukti").val(data.notr);
// 		},
//         error: function (data,xhr, ajaxOptions, thrownError) {
//             alert("error get no tr");
//         }
//     });
// }

function getbarang(param) {
    var kodebarang = $("#kode" + param).val();
    $.ajax({
        url: "/farmasi_po/getinfobarang/?kode=" + kodebarang,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $("#sat" + param).val(data.satuan1);
            $("#harga" + param).val(separateComma(data.hargajual));
            // totalline(id);
        }
    });
}

function total(param) {
    var qtybarang = $("#qty" + param).val();
    var hargabarang = $("#harga" + param).val();
    var harga1 = numeric_restruct(hargabarang);
    console.log(harga1);

    var subtotal = harga1 * qtybarang;
    $("#total" + param).val(separateComma(subtotal));

    totalline();
}

function hapus() {
    if (idrow > 1) {
        var x = document.getElementById('datatable').deleteRow(idrow - 1);
        idrow--;
    }

    total();
}

function save() {
    var gudang_asal = $('[name="gudang_asal"]').val();
    var gudang_tujuan = $('[name="gudang_tujuan"]').val();
    var total = $('#vtotal').val();
    var tanggal = $('[name="tanggal"]').val();
    if (gudang_asal == "" || gudang_tujuan == "" || total == "" || total == "0.00") {
        swal('MUTASI GUDANG', 'gudang belum diisi ...', '');
    } else {
        $.ajax({
            url: "/farmasi_pbb/save/2",
            data: $('#frmpermohonan').serialize(),
            type: 'POST',
            success: function(data) {
                swal({
                    title: "PERMOHON BERHASIL DISIMPAN",
                    html: "<p> No. Mutasi   : <b>" + data + "</b> </p>" +
                        "Tanggal :  " + tanggal + "<b> </p>" + "Total:" + total,
                    type: "info",
                    confirmButtonText: "OK"
                }).then((value) => {
                    location.href = "<?php echo base_url()?>farmasi_pbb";
                });

            },
            error: function(data) {
                swal('PERMOHONAN GUDANG', 'Data gagal disimpan ...', '');
            }
        });
    }
}

function separateComma(val) {
    // remove sign if negative
    var sign = 1;
    if (val < 0) {
        sign = -1;
        val = -val;
    }
    // trim the number decimal point if it exists
    let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
    let len = num.toString().length;
    let result = '';
    let count = 1;

    for (let i = len - 1; i >= 0; i--) {
        result = num.toString()[i] + result;
        if (count % 3 === 0 && count !== 0 && i !== 0) {
            result = ',' + result;
        }
        count++;
    }

    // add number after decimal point
    if (val.toString().includes('.')) {
        result = result + '.' + val.toString().split('.')[1];
    }
    // return result with - sign if negative
    return sign < 0 ? '-' + result : result;
}


function totalline() {
    var total = 0;
    $("tr .sumsubtot").each(function(index, value) {
        currentRow = parseInt(Number($(this).val().replace(/[^0-9\.]+/g, "")));
        total += currentRow
    });
    $("#vtotal").val(separateComma(total));
}

function numeric_restruct(param) {
    var resone, restwo;

    resone = param.split(",").join("");
    restwo = resone.split(".00").join("");

    return restwo;
}
</script>
<!-- -->

<?php 
	// $this->load->view('template/header');
    // $this->load->view('template/body');    	  
?>
<!-- 
			<div class="row">
				<div class="col-md-12">
					<h3 class="page-title">
					<span class="title-unit">
                            &nbsp;<?php echo $this->session->userdata('unit'); ?> 
                        </span>
                      - 
                      <span class="title-web">Farmasi <small>Permohonan Barang Ke Gudang</small>
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
							<a href="<?php echo base_url();?>farmasi_pbb">
                               Daftar Permohonan
                              							</a>
							<i class="fa fa-angle-right"></i>
						</li>
						<li>							
							<a href="">
                               Edit Permohonan
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="portlet box yellow">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-reorder"></i>*Edit Data
					</div>
					
					
				</div>
				
				<div class="portlet-body form">									
				  <form id="frmpenjualan" class="form-horizontal" method="post">
					<div class="form-body">
					  <div class="tabbable tabbable-custom tabbable-full-width">
					    <ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab1" data-toggle="tab">
                                   <i class="fa fa-file"></i> 
								   Permohonan Barang
								</a>
							</li>
							
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab1">		
												
												<div class="row">												    												
													<div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Tanggal</label>
													        <div class="col-md-4">
														        <input id="tanggal" name="tanggal" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d', strtotime($header->tglmohon));?>" />
													    	   
													        </div>



														</div>
													</div>
													<div class="col-md-6">
                                                         <div class="form-group">	
                                                           <label class="col-md-3 control-label">Pemohonan No.</label>
													        <div class="col-md-6">
															  <div class="input-group">
                                                                 <input type="text" placeholder="Otomatis" name="nomorbukti" class="form-control" value="<?= $header->mohonno;?>" readonly>
															</div>
                                                           </div>
														</div>    
													</div>
													
												
													
												</div>
												
												<div class="row">												    												 -->
<!-- <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Gudang Asal</label>
													        <div class="col-md-9">
														        <select id="gudang_asal" name="gudang_asal" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												     <?php
																  if($header->dari){
																  $namagudang = data_master('tbl_depo', array('depocode' => $header->dari))->keterangan;?>	
																  <option value="<?= $header->dari;?>"><?= $namagudang;?></option>
																<?php }
																?>
																</select>	
													    	   
													        </div>



														</div>
													</div> -->
<!-- <div class="col-md-6">
                                                         
													</div>
													
												
													
												</div>
												
												<div class="row">												    												 -->
<!-- <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Gudang Tujuan</label>
													        <div class="col-md-9">
														        <select id="gudang_tujuan" name="gudang_tujuan" class="form-control select2_el_farmasi_depo" data-placeholder="Pilih..." onkeypress="return tabE(this,event)">
            												    <?php
																if($header->ke){
																  $namagudang = data_master('tbl_depo', array('depocode' => $header->ke))->keterangan;?>	
																  <option value="<?= $header->ke;?>"><?= $namagudang;?></option>
																<?php }
																?>
																</select>	
													    	   
													        </div>



														</div>
													</div> -->
<!-- <div class="col-md-6">
                                                         <div class="form-group">
                                                            <label class="col-md-3 control-label">Keterangan</label>
													        <div class="col-md-9">
														        <input type="text" class="form-control" name="ket" value="<?= $header->keterangan;?>">
													    	   
													        </div>



														</div>  
													</div>
													
												
													
												</div>
												
												
												
												

												<div class="row">
												 <div class="col-md-12">
                                                   	
													<table id="datatable" class="table table-hoverx table-stripedx table-borderedx table-condensed table-scrollable">
													<thead>
                                                      <tr>
                    									<th width="30%" style="text-align: center">Kode/Nama Barang</th>
                    									<th width="10%" style="text-align: center">Kuantitas</th>
														<th width="10%" style="text-align: center">Satuan</th>
														<th width="10%" style="text-align: center">Harga</th>
														<th width="15%" style="text-align: center">Total</th>
														<th width="20%" style="text-align: center">Keterangan</th>
														
                    								</tr>
                    								<thead>
													
                    								<tbody>
													<?php
													$no=1;
													foreach($detil as $row){ ?>
													<tr>													   
                                                       <td width="30%">
														    <select name="kode[]" id="kode<?= $no;?>" class="select2_el_farmasi_barang form-control" onchange="showbarangname(this.value, 1)">															  
															 <?php
																if($row->kodebarang){
																  $barang = data_master('tbl_barang', array('kodebarang' => $row->kodebarang));?>	
																  <option value="<?= $row->kodebarang;?>"><?= $barang->kodebarang.' | '.$barang->namabarang.' | '.$barang->satuan1;?></option>
																<?php } ?>
															</select>
														</td>
                                                       
                                                        <td width="10%" ><input name="qty[]"    onchange="totalline(<?= $no;?>)" value="<?= $row->qtymohon;?>" id="qty<?= $no;?>" type="text" class="form-control rightJustified"  ></td>
														<td width="10%" ><input name="sat[]"    id="sat<?= $no;?>" type="text" value="<?= $row->satuan;?>" class="form-control "  onkeypress="return tabE(this,event)"></td>
														<td width="10%" ><input name="harga[]"  onchange="totalline(<?= $no;?>)" value="<?= $row->harga;?>" id="harga<?= $no;?>" type="text" class="form-control rightJustified"  onkeypress="return tabE(this,event)"></td>
														<td width="15%" ><input name="total[]"  onchange="totalline(<?= $no;?>)"  value="<?= $row->totalharga;?>" id="total<?= $no;?>" type="text" class="form-control rightJustified"  onkeypress="return tabE(this,event)"></td>
														<td width="10%" ><input name="note[]"    id="note<?= $no;?>" type="text" value="<?= $row->keterangan;?>" class="form-control "  onkeypress="return tabE(this,event)"></td>
								                      </tr>
                    								<?php $no++;} ?>
								                    </tbody>
													<tfoot>
													   <tr>
													     <td colspan="4" style="text-align:right">TOTAL</td>
														 <td><input type="text" class="form-control rightJustified" id="vtotal" readonly></td>
														 <td colspan="2"></td>
													   </tr>
													</tfoot>
													</table>
													
													<div class="row">
														<div class="col-xs-9">
															<div class="wells">
																<button type="button" onclick="tambah()" class="btn green"><i class="fa fa-plus"></i> </button>
												                <button type="button" onclick="hapus()" class="btn red"><i class="fa fa-trash-o"></i></button>
															</div>															
														</div>
														
																										
													</div>

								                   
								                   </div>
												</div>
												

											
							</div> -->
<!-- tab1-->

<!-- <div class="tab-pane" id="tab2">	
							   <div class="row">
							       
								</div>
                                
							</div> -->
<!-- tab2-->

<!-- </div>tab	 -->

<!-- <div class="row">
							<div class="col-xs-12">
								<div class="well">		
								   
                                   
									<button type="button" onclick="save()" class="btn blue"><i class="fa fa-save"></i> Simpan</button>
									   
									<div class="btn-group">
									  <button type="button" class="btn green" onclick="this.form.reset();location.reload();"><i class="fa fa-pencil-square-o"></i> Data Baru</button>                																							
									</div>
									<h4><span id="error" style="display:none; color:#F00">Terjadi Kesalahan... </span> <span id="success" style="display:none; color:#0C0">Data sudah disimpan...</span></h4>								
								</div>															
							</div>
							
																		
						</div>
													
													
					  </div>	
					</div>  
					
					
				   </form>
				</div>
            </div>
		</div>
	</div>
</div> -->

<?php
//   $this->load->view('template/footer'); 
?>

<!-- <script>

var idrow  = "<?= $jumdata+1;?>";

function tambah(){
    var x=document.getElementById('datatable').insertRow(idrow);
    var td1=x.insertCell(0);
    var td2=x.insertCell(1);
    var td3=x.insertCell(2);
	var td4=x.insertCell(3);
	var td5=x.insertCell(4);
	var td6=x.insertCell(5);
	
	var akun="<select name='kode[]' id=kode"+idrow+" onchange='showbarangname(this.value,"+idrow+")' class='select2_el_farmasi_barang form-control' ></select>";
	td1.innerHTML=akun;
	td2.innerHTML="<input name='qty[]'    id=qty"+idrow+" onchange='totalline("+idrow+")' value='1'  type='text' class='form-control rightJustified'  >";
	td3.innerHTML="<input name='sat[]'    id=sat"+idrow+" type='text' class='form-control' >";
	td4.innerHTML="<input name='harga[]'  id=harga"+idrow+" type='text' class='form-control' >";
	td5.innerHTML="<input name='total[]'    id=total"+idrow+" type='text' class='form-control rightJustified' >";
	td6.innerHTML="<input name='note[]'    id=note"+idrow+" type='text' class='form-control' >";
	initailizeSelect2_farmasi_barang();
	total();
    idrow++;
}

function showbarangname(str, id) {   
  var xhttp; 
  var vid = id;
   $.ajax({
        url : "<?php echo base_url();?>farmasi_po/getinfobarang/?kode="+str,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {						
			$('#sat'+vid).val(data.satuan1);
			$('#harga'+vid).val(separateComma(data.hargajual));
			totalline(vid);
		}
	});	
  
  
}


function save()
{	      
    var gudang_asal   = $('[name="gudang_asal"]').val(); 
	var gudang_tujuan   = $('[name="gudang_tujuan"]').val(); 
	var total   = $('#vtotal').val(); 
	var tanggal  = $('[name="tanggal"]').val(); 
	if(gudang_asal=="" || gudang_tujuan=="" || total=="" || total=="0.00"){
		swal('PERMOHONAN GUDANG','gudang belum diisi ...',''); 
	} else {      
	$.ajax({				
		url:'<?php echo site_url('farmasi_pbb/save/2')?>',				
		data:$('#frmpenjualan').serialize(),				
		type:'POST',
		success:function(data){        		
		swal({
					  title: "PERMOHONAN GUDANG",
					  html: "<p> No. Mutasi   : <b>"+data+"</b> </p>"+ 
					  "Tanggal :  " + tanggal,
					  type: "info",
					  confirmButtonText: "OK" 
					 }).then((value) => {
							location.href = "<?php echo base_url()?>farmasi_pbb";
		         });								
	
		},
		error:function(data){
			swal('PERMOHONAN GUDANG','Data gagal disimpan ...',''); 
		}
		});
	}		
}	
   
	function hapus(){
		if(idrow>2){
			var x=document.getElementById('datatable').deleteRow(idrow-1);
			idrow--;
		}
	}

  
  
   
function showpo() {
  var xhttp;
  var str = $('[name="cust"]').val(); 
  
  if (str == "") {
    document.getElementById("kodeso").innerHTML = "";
    return;
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    document.getElementById("kodeso").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "<?php echo base_url(); ?>penjualan_pengiriman/getlistpo/"+str, true);  
  xhttp.send();
}

function getpo() { 
  var xhttp;      
  var str = $('[name=kodeso]').val();
  if(str==""){
	hapus();
	$('[id=kode1]').val('');
	$('[id=qty1]').val('');
	$('[id=sat1]').val('');
  }  else  {
	$.ajax({
        url : "<?php echo base_url();?>penjualan_pengiriman/getpo/"+str,
        type: "GET",
        dataType: "JSON",
		
        success: function(data)
        {		            
		    for(i=0; i <= data.length-1; i++){	
			hapus();
			}
			
            for(i=0; i <= data.length-1; i++){		
			  if(i>0){
		       tambah();
			  }
			  
			  x = i+1;
			  
			  var option = $("<option selected></option>").val(data[i].kodeitem).text(data[i].namabarang);
              $('#kode'+x).append(option).trigger('change');
			  
			  document.getElementById("qty"+x).value=data[i].sisa;		    
			  document.getElementById("sat"+x).value=data[i].satuan;		    
			}
			
			
			
			
		}
	});	    
  }	
}

window.onload = function(){
        document.getElementById('nomorbukti').focus();
};

total();

function totalline(id)
  {      
   var table = document.getElementById('datatable');
   var row = table.rows[id];        
   var harga = Number(row.cells[3].children[0].value.replace(/[^0-9\.]+/g,"")); 
   jumlah      = row.cells[1].children[0].value*harga;    
    
   row.cells[4].children[0].value= separateComma(jumlah);   
   total();  
}

function total()
  {
   
   var table = document.getElementById('datatable');
   var rowCount = table.rows.length;
   
   tjumlah = 0;
   for(var i=1; i<rowCount-1; i++) 
   {
    var row = table.rows[i];
    
	ztotal      = row.cells[4].children[0].value;
	
    var jumlah1 = Number(ztotal.replace(/[^0-9\.]+/g,""));
	
   	tjumlah  = tjumlah  + eval(jumlah1);
		  
   } 
   
   document.getElementById("vtotal").value=separateComma(tjumlah);
 

  }
  

</script> -->



</body>

</html>