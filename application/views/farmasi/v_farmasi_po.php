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
                    &nbsp;<?php echo $this->session->userdata('unit'); ?>&nbsp;</span>
                -
                <span class="title-web"><?= $modul;?> <small><?= $submodul;?></small> &nbsp</span>
            </h3>
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <i style="color:white;" class="fa fa-home"></i>
                    <a style="color:white;" href="<?php echo base_url();?>dashboard">
                        <b>Awal</b>
                    </a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a style="color:white;" href="#">
                        <b><?= $modul;?></b>
                    </a>
                    <i style="color:white;" class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a style="color:white;" href="<?php echo base_url();echo $url?>">
                        <b><?= $submodul?></b>
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
                        Daftar <?= $submodul;?>
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">

                        </div>

                        <div class="btn-group">
                            <?php if($akses->uadd==1){ ?>
                                <?php 
                                $cek =  $this->session->userdata('user_level'); 
                                if($cek==0){?> 
                                <?php }else{ ?>

                                    <a id="tambah" href="<?php echo base_url()?><?= $url.'/add'?>" class="btn btn-success">
                                        <i class="fa fa-plus"></i>
                                        <b>Transaksi Baru</b>
                                    </a>

                                    <a type="button" class="btn green" href="<?= site_url("Pareto");?>">Pareto</a>

                                <?php } ?>
                            
                            <?php } ?>
                        </div>

                        <div class="btn-group pull-right">

                            <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i
                                    class="fa fa-angle-down"></i>
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
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead class="page-breadcrumb breadcrumb" style="color:white;">
                            <tr>
                                <th style="text-align: center; width:5%">Cabang &nbsp;</th>
                                <th style="text-align: center; width:10%">User ID</th>
                                <th style="text-align: center; width:10%">No. PO</th>
                                <th style="text-align: center; width:10%">Tgl. PO</th>
                                <th style="text-align: center; width:20%">Nama Pemasok</th>
                                <th style="text-align: center; width:7%">Tgl. Kirim</th>
                                <th style="text-align: center; width:10%">Dibuat Oleh</th>
                                <th style="text-align: center; width:10%">Status</th>
                                <th style="text-align: center;width:13%;">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4"></td>
                                <td></td>
                                <td></td>
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


    <?php
  $this->load->view('template/footer_tb');
  $this->load->view('template/v_report');
  $this->load->view('template/v_periode'); 
?>


    <script type="text/javascript">
var save_method; //for save method string
var sfilter;
var table;
close_app(); 

$(document).ready(function() {

    var display = $.fn.dataTable.render.number('.', ',', 2, ' ').display;
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

function close_app() 
{
    $.ajax({
        url         : '<?php echo base_url(); ?>lock_so/close_app',
        type        : "POST",
        dataType    : "json",
        success:function(data){
                if (data == 1) {
                    $('#tambah').attr('disabled',true);
                }else{
                    $('#tambah').attr('disabled',false);
                }
            }                                     
    });
        
}  

function add_data() {


    $.ajax({
        url: "<?php echo site_url('akuntansi_ju/jurnalentry')?>/",
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            //if success reload ajax table
            $('#modal_form').modal('hide');
            reload_table();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });

}

function edit_data(id) {
    $.ajax({
        url: "<?php echo site_url('akuntansi_jurnal')?>/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            reload_table();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error deleting data');
        }
    });

}

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
            url: '<?php echo base_url('farmasi_po')?>/ajax_delete',
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
            url: '<?php echo base_url('farmasi_po')?>/ajax_delete',
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

function approve(id, nomor) {
    swal({
        title: 'SETUJUI',
        html: "Setujui Nomor PO : "+nomor+" ? <strong><p>",
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
            url: '<?php echo base_url('farmasi_po')?>/approve',
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

function _urlcetak(nomor) {
    var cekstokk = document.getElementById('cekstok').value;
    var cekhargaa = document.getElementById('cekharga').value;
    var baseurl = "<?php echo base_url()?>";
    var idd = $('[name=idd]').val();
    var noo = $('[name=noo]').val();

    url = baseurl + 'farmasi_po/cetak/' + idd + '/' + noo + '/' + cekstokk + '/' + cekhargaa
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

function send_email(id, email){	 
  swal({
		title: 'KIRIM Email',
		text: "Alamat : "+email,
		type: 'info',
		
		showCancelButton: true,
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger m-l-10',
		confirmButtonText: 'Ya, Kirim',     
		cancelButtonText: 'Tidak',     
	}).then(function () {			
		$.ajax({
			type : 'POST',
			dataType : "json",
			data : {id : id},
			url : '<?php echo base_url()?>Farmasi_po/send_email',
			success:function(response){
                console.log(response);
				if(response.status == 1) {
					swal(
						'Sending!',
						'Kirim Email berhasil.',
						'success'
					)
				} else {
					swal(
						'Failed!',
						'Kirim Email gagal',
						'failed'
					)
				}
			}
		});
	});   
}	


function filterdata() {
    var tgl1 = document.getElementById("tanggal1").value;
    var tgl2 = document.getElementById("tanggal2").value;
    var id = 2;
    var str = id + '~' + tgl1 + '~' + tgl2;
    table.ajax.url("<?php echo base_url('farmasi_po/ajax_list/')?>" + str).load();
}

jQuery(document).ready(function() {
    // ComponentsPickers.init();

});
    </script>
    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-custom">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">TAMPILKAN DI CETAKAN ?</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="idd" id="idd" />
                        <input type="hidden" value="" name="noo" id="noo" />
                        <div class="form-body">
                            <div class="form-group">
                                <label class="control-label col-md-3">STOK AKHIR : </label>
                                <div class="col-md-3">
                                    <select class="form-control" id="cekstok" name="cekstok">
                                        <option value='1'>Ya</option>
                                        <option value='2'>Tidak</option>
                                    </select>

                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3">HARGA SATUAN: </label>
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