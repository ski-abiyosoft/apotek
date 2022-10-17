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
                <span class="title-web">Kas/Bank <small>Penerimaan Kas</small>
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
                    <a href="#">
                        Kas/Bank
                    </a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?php echo base_url();?>keuangan_masuk">
                        Daftar Penerimaan Kas
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
                        Daftar Penerimaan
                    </div>

                </div>
                <div class="portlet-body">
                    <div class="table-toolbar">
                        <div class="btn-group">

                        </div>

                        <div class="btn-group">
                            <?php if($akses->uadd==1){ ?>
                            <a href="<?php echo base_url()?>keuangan_masuk/entri" class="btn btn-success">
                                <i class="fa fa-plus"></i>
                                Penerimaan Baru
                            </a>
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
                    <table id="table" class="table table-striped- table-bordered-" cellspacing="0" width="100%">
                        <thead class="breadcrumb">
                            <tr>
                                <th style="text-align: center; width:5%">Cab.</th>
                                <th style="text-align: center; width:10%">User ID</th>
                                <th style="text-align: center; width:10%">No. Bukti</th>
                                <th style="text-align: center; width:10%">Tanggal</th>
                                <th style="text-align: center; width:10%">Kas</th>
                                <th style="text-align: center; width:10%">Pos</th>
                                <th style="text-align: center; width:20%">Keterangan</th>
                                <th style="text-align: center; width:10%">Jumlah (Rp)</th>
                                <th style="text-align: center;width:10%;">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7"></td>
                                <td style="text-align: center; width:10%"></td>
                                <td colspan="1"></td>
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
var idCetak;


$(document).ready(function() {

    // $('.print_laporan').on("click", function(){
    //     $('.modal-title').text('KAS/BANK');

    //     var param=this.id;				
    //     console.log('hehe');

    //     $("#simkeureport").html('<iframe src="<?php echo base_url();?>keuangan_masuk/cetak/'+param+'" frameborder="no" width="100%" height="520"></iframe>');
    // });	



    // $("#simkeureport").html('<iframe src="<?php echo base_url();?>keuangan_masuk/cetak/'+sessionStorage.getItem('idCetak')+'" frameborder="no" width="100%" height="520"></iframe>');

    var display = $.fn.dataTable.render.number('.', ',', 2, ' ').display;
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('keuangan_masuk/ajax_list/1')?>",
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
                "targets": [0, 1, 2, 3, 6, 7], //last column
                "orderable": true, //set not orderable

                "className": "text-center",

            },
            {
                "targets": [7], //last column
                "orderable": true, //set not orderable

                "className": "text-right",
                render: function(data, type, row) {
                    return '<b>' + display(row[7]) + '</b>';

                }

            },
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total over all pages
            debet = api
                .column(7)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);


            // Update footer
            $(api.column(7).footer()).html(
                '<b>' + formatCurrency1(debet) + '</b>'
            );


        },


    });




    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });

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


function id_cetak(id) {
    $("#simkeureport").html('<iframe src="<?php echo base_url();?>keuangan_masuk/cetak/' + id +
        '" frameborder="no" width="100%" height="520"></iframe>');
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
        XLSX.writeFile(wb, fn || ('Keuangan-masuk.' + (type || 'xlsx')));
}
$(".btnExport_1").click(function(event) {
    convert_excel('xlsx');
});

function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax 
}

function delete_data(id, nomor) {
    swal({
        title: 'KAS/BANK',
        html: "Penerimaan ini akan dihapus ? <strong><p>" + nomor,
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
            url: '<?php echo base_url('keuangan_masuk')?>/ajax_delete',
            data: {
                id: id
            },
            success: function(response) {
                if (response.status == '1') {
                    swal(
                        '',
                        'Penerimaan Kas sudah dihapus...',
                        ''
                    )
                } else {
                    swal(
                        '',
                        'Penerimaan Kas dihapus.',
                        ''
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
    table.ajax.url("<?php echo base_url('keuangan_masuk/ajax_list/')?>" + str).load();
}
    </script>