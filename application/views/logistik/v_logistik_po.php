<?php
    $this->load->view('template/header');
    $this->load->view('template/body');
    ?>



    <!--div class="page-content-wrapper">
		<div class="page-content"-->
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-title">
                <span class="title-unit">
                    &nbsp;<?php echo $this->session->userdata('unit'); ?>
                </span>
                -
                <span class="title-web"><?= $modul; ?> <small><?= $submodul; ?></small>
            </h3>
            <ul class="page-breadcrumb breadcrumb">

                <li>
                    <i style="color:white;" class="fa fa-home"></i>
                    <a class="title-white" href="<?php echo base_url(); ?>dashboard">
                        Awal
                    </a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="#">
                        <?= $modul; ?>
                    </a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a class="title-white" href="<?php echo base_url();
                                                    echo $url ?>">
                        <?= $submodul ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        Daftar <?= $submodul; ?>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">

                        </div>

                        <div class="btn-group">
                            <?php if ($akses->uadd == 1) { ?>
                                <a href="<?php echo base_url() ?><?= $url . '/add' ?>" class="btn btn-success">
                                    <i class="fa fa-plus"></i>
                                    Data Baru
									</a>
								  <?php } ?>
                                    <button type="button" class="btn btn-primary" onclick="laporan_po()"><i class="fa fa-book"></i> Laporan PO</button>
                                  
								</div>
							  
								<div class="btn-group pull-right">
								    
									<button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										
										<li>											
											<a data-toggle="modal" href="#lupperiode">Ganti Periode Data</a>										
										</li>	
										<li>										
                                            <a href="#" class="btnExport_1">Export ke Excel</a>										
											
										</li>
									</ul>
								</div>
							</div>
							<table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                               <thead class="breadcrumb">
                                     <tr>
                                         <th class="title-white" style="text-align: center; width:5%">Cabang</th>
										 <th class="title-white" style="text-align: center; width:10%">User ID</th>
                                         <th class="title-white" style="text-align: center; width:10%">No. PO</th>
                                         <th class="title-white" style="text-align: center; width:10%">Tgl. PO</th>
										 <th class="title-white" style="text-align: center; width:20%">Nama Pemasok</th>
										 <th class="title-white" style="text-align: center; width:10%">Tgl. Kirim</th>
										 <th class="title-white" style="text-align: center; width:10%">Dibuat Oleh</th>
										 <th class="title-white" style="text-align: center;">Status</th>
									     <th class="title-white" style="text-align: center;width:15%;">Aksi</th>

                                     </tr>
                                </thead>
                                <tbody>
                                </tbody>
								<tfoot>
								    <tr>
									    <td colspan="4"></td>
										<td ></td>
										<td ></td>
										<td colspan="3"></td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
				</div>
			</div>
       </div>
  </div>
</div>  


<div class="modal fade" id="laporanpo" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">Laporan PO</h4>
			</div> 
            <form method="POST" id="form_lap_po"></form>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label class="col-md-3">Dari</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="lap_dari" id="lap_dari" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-md-3">Sampai</label>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="lap_sampai" id="lap_sampai" value="<?= date('Y-m-d') ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <label class="col-md-3">Gudang</label>
                                <div class="col-md-9">
                                    <select name="lap_gudang" id="lap_gudang" class="form-control selectpicker" data-live-search="true">
                                        <?php $sql = $this->db->get_where("tbl_depo", ["konekpos" => "LOGISTIK"])->result(); ?>
                                        <option value="">Pilih...</option>
                                        <?php foreach($sql as $s) : ?>
                                            <option value="<?= $s->depocode; ?>"><?= $s->keterangan; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">	                                        
                    <button type="button" id="btncetak" class="btn yellow" onclick="ceklaporan()"><i class="fa fa-print"></i> Cetak</button>
                </div>
            </form>			
		</div>									
	</div>								
</div>

<?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>
	

<script type="text/javascript">
// $(".selectpicker").selectpicker();

function ceklaporan() {
    var dari = $("#lap_dari").val();
    var sampai = $("#lap_sampai").val();
    var gudang = $("#lap_gudang").val();
    var baseurl = "<?php echo base_url()?>";
    url = baseurl + 'Logistik_po/laporan_po/' + dari + '/' + sampai + '?gudang=' + gudang;
    window.open(url, '');
    $('#laporanpo').modal('hide');
}

function laporan_po() {
    $("#laporanpo").modal("show");
}

var save_method; //for save method string
var sfilter;
var table;

$(document).ready(function() {

    var display = $.fn.dataTable.render.number( '.', ',', 2, ' ' ).display;
    table = $('#table').DataTable({ 
        "responsive": true,		
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url()?><?= $url;?>/ajax_list/1",
		    "type": "POST"
        },
		
		"oLanguage": {
                    "sEmptyTable": "Tidak ada data",
                    "sInfoEmpty": "",
                    "sInfoFiltered": " - Dipilih dari _MAX_ data",
                    "sSearch": "Pencarian Data : ",
                    "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                    "sLengthMenu": "_MENU_ Baris",
                    "sZeroRecords": "-",
                    "oPaginate": {
                        "sPrevious": "Sebelumnya",
                        "sNext": "Berikutnya"
                    }
                },

                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "Semua"] // change per page values here
                ],

                //Set column definition initialisation properties.
                "columnDefs": [{
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                    },
                    {
                        "targets": [0, 1, 2, 6, 7, 8], //last column
                        "orderable": true, //set not orderable

                        "className": "text-center",

                    },


                ],


            });



            //datepicker
            // $('.datepicker').datepicker({
            //     autoclose: true,
            //     format: "yyyy-mm-dd",
            //     todayHighlight: true,
            //     orientation: "top auto",
            //     todayBtn: true,
            //     todayHighlight: true,  
            // });

            //set input/textarea/select event when change value, remove class error and remove text help block 
            $("input").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });

        });




        function convert_excel(type, fn, dl) {
            var elt = document.getElementById('table');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "Sheet JS"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('Jurnal-Report.' + (type || 'xlsx')));
        }
        $(".btnExport_1").click(function(event) {
            convert_excel('xlsx');
        });

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax 
        }

        function delete_data(id, nomor) {
            swal({
                title: 'PURCHASE ORDER',
                html: "Data ini akan dihapus ? <strong><p>" + nomor,
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function() {
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: '<?php echo base_url('logistik_po') ?>/ajax_delete',
                    data: {
                        id: id
                    },
                    success: function(response) {

                        if (response.status == '1') {
                            swal(
                                '',
                                'Data sudah dihapus...',
                                ''
                            )
                        } else {
                            swal(
                                '',
                                'Data gagal dihapus.',
                                ''
                            )
                        }
                        //dataTable.ajax.reload(null,false);
                        reload_table();
                    }
                });
            });
        }

        function _urlcetak(nomor) {
            var cekstokk = document.getElementById('cekstok').value;
            var cekhargaa = document.getElementById('cekharga').value;
            var baseurl = "<?php echo base_url() ?>";
            var idd = $('[name=idd]').val();
            var noo = $('[name=noo]').val();

            url = baseurl + 'Logistik_po/cetak2/' + idd + '/' + noo + '/' + cekstokk + '/' + cekhargaa
            window.open(url, '');
            $('#modal_form').modal('hide');

        }

        function _urlcetak2(nomor) {
            var cekstokk = document.getElementById('cekstok').value;
            var cekhargaa = document.getElementById('cekharga').value;
            var baseurl = "<?php echo base_url() ?>";
            var idd = $('[name=idd]').val();
            var noo = $('[name=noo]').val();

            url = baseurl + 'Logistik_po/cetakpsn/' + idd + '/' + noo + '/' + cekstokk + '/' + cekhargaa
            window.open(url, '');
            $('#modal_form').modal('hide');

        }


        function cekctk(id, nomor) {
            var idd = id;
            var noo = nomor;
            $('#idd').val(idd);
            $('#noo').val(noo);
            $('#modal_form').modal('show');

        }

        function cekctkpsn(id, nomor) {
            var idd = id;
            var noo = nomor;
            $('#idd').val(idd);
            $('#noo').val(noo);
            $('#modal_form2').modal('show');

        }


        function approve(id, nomor) {
            swal({
                title: 'SETUJUI',
                html: "Setujui Nomor PO : " + nomor + " ? <strong><p>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                confirmButtonText: 'Ya, Setuju',
                cancelButtonText: 'Batal'
            }).then(function() {
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    url: '<?php echo base_url('logistik_po') ?>/approve',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status == '1') {
                            swal(
                                'SETUJUI!',
                                'Data berhasil disetujui.',
                                'success'
                            )
                        } else {
                            swal(
                                'SETUJUI!',
                                'Data gagal disetujui.',
                                'error'
                            )
                        }
                        //dataTable.ajax.reload(null,false);
                        reload_table();
                    }
                });
            });
        }

        function filterdata() {
            var tgl1 = document.getElementById("tanggal1").value;
            var tgl2 = document.getElementById("tanggal2").value;
            var id = 2;
            var str = id + '~' + tgl1 + '~' + tgl2;
            table.ajax.url("<?php echo base_url('logistik_po/ajax_list/') ?>" + str).load();
        }

        jQuery(document).ready(function() {
            // ComponentsPickers.init();

        });
    </script>
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-custom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">TAMPILKAN STOK DI KETERANGAN ?</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="idd" id="idd" />
                        <input type="hidden" value="" name="noo" id="noo" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">STOK : </label>
                                <div class="col-md-3">
                                    <select class="form-control" id="cekstok" name="cekstok">
                                        <option value='1'>Ya</option>
                                        <option value='2'>Tidak</option>
                                    </select>

                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">HARGA : </label>
                                <div class="col-md-3">
                                    <select class="form-control" id="cekharga" name="cekharga">
                                        <option value='1'>Ya</option>
                                        <option value='2'>Tidak</option>
                                    </select>

                                    <span class="help-block"></span>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="_urlcetak()" class="btn btn-primary">CETAK</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="modal_form2" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header header-custom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">TAMPILKAN STOK DI KETERANGAN ?</h4>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal" style="padding-left:30px; padding-right:30px;">
                        <input type="hidden" value="" name="idd" id="idd" />
                        <input type="hidden" value="" name="noo" id="noo" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="col-md-4">STOK</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="cekstok" name="cekstok">
                                        <option value='1'>Ya</option>
                                        <option value='2'>Tidak</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">HARGA</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="cekharga" name="cekharga">
                                        <option value='1'>Ya</option>
                                        <option value='2'>Tidak</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>


                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="_urlcetak2()" class="btn btn-primary">CETAK</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->