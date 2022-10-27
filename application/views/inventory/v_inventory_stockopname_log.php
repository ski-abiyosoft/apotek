<?php
$this->load->view('template/header');
$this->load->view('template/body');
?>

<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css-') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<style>
    div.tableContainer {
        clear: both;
        border: 1px solid #963;
        height: 285px;
        overflow: auto;
        width: 100%
    }

    html>body div.tableContainer {
        overflow: hidden;
        width: 100%
    }

    div.tableContainer table {
        float: left
    }

    html>body div.tableContainer table {}

    thead.fixedHeader tr {
        position: relative
    }

    thead.fixedHeader th {
        background: #C96;
        border-left: 1px solid #EB8;
        border-right: 1px solid #B74;
        border-top: 1px solid #EB8;
        padding: 4px 3px;
        text-align: left
    }

    html>body tbody.scrollContent {
        display: block;
        height: 262px;
        overflow: auto;
        width: 100%
    }

    html>body thead.fixedHeader {
        display: table;
        overflow: auto;
        width: 100%
    }

    tbody.scrollContent td,
    tbody.scrollContent tr.normalRow td {
        background: #FFF;
        border-bottom: none;
        border-left: none;
        border-right: 1px solid #CCC;
        border-top: 1px solid #DDD;
        padding: 2px 3px 3px 4px
    }

    tbody.scrollContent tr.alternateRow td {
        background: #EEE;
        border-bottom: none;
        border-left: none;
        border-right: 1px solid #CCC;
        border-top: 1px solid #DDD;
        padding: 2px 3px 3px 4px
    }
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
            <span class="title-unit">&nbsp;<?php echo $this->session->userdata('unit'); ?> </span>&nbsp;
            -
            <span class="title-web">Logistik <small>Stock Opname</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home title-white"></i>
                <a class="title-white" href="<?php echo base_url(); ?>dashboard">Awal</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a class="title-white" href="#">Inventory</a>
                <i class="fa fa-angle-right title-white"></i>
            </li>
            <li>
                <a class="title-white" href="#">Stock Opname</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-title">
                <div class="caption">Daftar Stock Opname
                </div>
                <div class="btn-group pull-right" style="margin-bottom:20px;">
                    <label>Gudang : </label>
                    <select class="form-control input-large select2_el_logistik_depo" id="gudang" name="gudang" onchange="getsogudang()">
                        <?php if (isset($_GET["gudang"])) :
                            $depo   = $this->db->query("SELECT * FROM tbl_depo WHERE depocode = '$_GET[gudang]'")->row();
                        ?>

                            <option value="<?= $depo->depocode ?>" selected><?= $depo->keterangan ?></option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="btn-group">
                        <!-- <button onclick="location.href='<?php echo base_url() ?>inventory_tso_log/entri'"
                            class="btn btn-success"><i class="fa fa-plus"></i>&nbsp; Data Baru</button> -->

                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#SO">Data
                            Baru</button>

                        <!-- <div id="myBtnContainer" style="display:inline-block;margin-left:10px">
                        <button class="btn btn-primary" type="button" onclick="filterSelection('adjustment')">Adjustment</button>
                        <button class="btn btn-default" type="button" onclick="filterSelection('opname')">Stock Opname</button>
                    </div> -->
                    </div>
                    <!--button class="btn btn-success" onclick="add_data()"><i class="glyphicon glyphicon-plus"></i> Data Baru</button-->
                    <!-- <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Refresh</button> -->
                    <!-- <div class="btn-group pull-right">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">Data <i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="#formulirso"  data-toggle="modal" >Cetak Lembar Stock Opname</a>
                        </li>
                        <li>
                            <a href="#hasilso"     data-toggle="modal" >Cetak Hasil Stock Opname </a>
                        </li>
                        <li>
                            <a href="#hasilsosel"  data-toggle="modal" >Cetak Hasil Stock Opname Selisih</a>
                        </li>
                    </ul>
                </div> -->
                </div>

                <div class="filterDiv">
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr class="page-breadcrumb breadcrumb">
                                <th style="text-align: center;width: 10%;color: #fff">Cabang</th>
                                <th style="text-align: center;width: 10%;color: #fff">Username</th>
                                <th style="text-align: center;width: 10%;color: #fff">Nama Gudang</th>
                                <th style="text-align: center;width: 10%;color: #fff">Tanggal</th>
                                <th style="text-align: center;width: 10%;color: #fff">Jenis</th>
                                <th style="text-align: center;width: 10%;color: #fff">Kode Barang</th>
                                <th style="text-align: center;width: 10%;color: #fff">Nama Barang</th>
                                <th style="text-align: center;width: 10%;color: #fff">Qty</th>
                                <th style="text-align: center;width: 10%;color: #fff">Qty SO</th>
                                <th style="text-align: center;width: 10%;color: #fff">Status</th>
                                <th style="text-align: center;width: 10%;color: #fff">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $level = $this->session->userdata('level');
                            $userid = $this->session->userdata('username');
                            foreach ($list as $lkey => $lval) {
                                switch ($lval->type) {
                                    case "so":
                                        $type_stock = "Stock Opname";
                                        $tglstock   = $lval->tglso;
                                        $resstock   = $lval->hasilso;
                                        break;
                                    case "adjustment":
                                        $type_stock = "Adjustment";
                                        $tglstock = $lval->tglso;
                                        $resstock   = $lval->sesuai;
                                        break;
                                    default:
                                        $type_stock = "Unselected";
                                        break;
                                }
                                $cabang = $this->session->userdata("unit");
                                $cek = $this->db->query("SELECT * FROM tbl_apostocklog JOIN tbl_aposesuailog ON tbl_apostocklog.kodebarang=tbl_aposesuailog.kodeobat WHERE tbl_apostocklog.kodebarang = '$lval->kodeobat' AND tbl_aposesuailog.koders = '$cabang' and menyetujui = '$userid' GROUP BY tbl_apostocklog.id DESC")->row_array();

                                if ($lval->approve == 1) {
                                    $status = '<span class="label label-sm label-success">Approved</span>';
                                } else {
                                    $status = '<span class="label label-sm label-warning">Waiting</span>';
                                }
                                echo "
                                    <tr>
                                        <td class='text-center'>$lval->koders</td>
                                        <td>$lval->username</td>
                                        <td>$lval->gudang</td>
                                        <td>" . date("d/m/Y", strtotime($tglstock)) . "</td>
                                        <td>$type_stock</td>
                                        <td>$lval->kodeobat</td>
                                        <td>$lval->namabarang</td>
                                        <td class='text-center'>$lval->saldo</td>
                                        <td class='text-center'>$resstock</td>
                                        <td class='text-center'>$status</td>
                                        <td class='text-center'>
                                        ";
                                $akses = $this->M_global->cek_menu_akses($level, 4304);
                                if ($akses->uedit == 1 && $akses->udel == 1) {
                                    if ($userid != $lval->yangubah) {
                                        $cek = $this->db->query("SELECT * FROM tbl_apostocklog JOIN tbl_aposesuailog ON tbl_apostocklog.kodebarang=tbl_aposesuailog.kodeobat WHERE tbl_apostocklog.kodebarang = '$lval->kodeobat' AND tbl_apostocklog.koders = '$cabang' and menyetujui = '$userid'")->row_array();
                                        if ($userid == $cek['menyetujui']) {
                                            if ($lval->approve != 1) {
                                                echo '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Approve" onclick="approve(' . $lval->id . ')"><i class="glyphicon glyphicon-check"></i></a>';
                                            } else {
                                                echo '';
                                            }
                                        } else {
                                            echo '';
                                        }
                                    } else {
                                        echo '';
                                    }
                                } else {
                                    echo '';
                                }
                                echo "</td>
                                </tr>";
                                // echo "<td>
                                //             <center>
                                //                 <button class='btn red btn-sm' type='button' onclick='deleteso($lval->id)'><i class='fa fa-trash'></i></button>
                                //             </center>
                                //         </td>
                                //     </tr>
                                // ";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="SO" class="modal fade" role="dialog">
        <div class="modal-dialog modal-small">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><b>Masukan Password</b></h4>
                </div>
                <!-- body modal -->
                <form action="<?= site_url('Inventory_tso_log/validate'); ?>" method="POST">
                    <div class="modal-body">
                        <!-- <label for="username">Username: </label>
                    <input type="username" name="username" id="username" required><br> -->
                        <label for="password">Passsword:</label>
                        <input type="password" name="password" class="form-control" id="password" required>
                    </div>
                    <!-- footer modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" type="submit">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('template/footer');
//   $this->load->view('template/v_report');
?>

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<script src="<?php echo base_url('assets/scripts/custom/components-pickers.js') ?>"></script>


<script type="text/javascript">
    function approve(id) {
        var gudang = $("#gudang").val();
        swal({
            title: 'SETUJUI ?',
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
                url: '<?php echo base_url('Inventory_tso_log') ?>/approve/' + id,
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
                    location.href = '/inventory_tso_log/?gudang=' + gudang;
                }
            });
        });
    }

    function getsogudang() {
        var gudang = $("#gudang").val();
        var checkg = (gudang == null || gudang == "") ? "Pilih Gudang" : "Tidak Ada Data";

        location.href = '/inventory_tso_log/?gudang=' + gudang;
    }

    $(document).ready(function() {
        var gudang = $("#gudang").val();
        var checkg = (gudang == null || gudang == "") ? "Pilih Gudang" : "Tidak Ada Data";

        $('#table').DataTable({
            "oLanguage": {
                "sEmptyTable": checkg,
                "sInfoEmpty": "Tidak ada data",
                "sInfoFiltered": " - Dipilih dari _MAX_ data",
                "sSearch": "Pencarian Data : ",
                "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
                "sLengthMenu": "_MENU_ Baris",
                "sZeroRecords": "Tida ada data",
                "oPaginate": {
                    "sPrevious": "Sebelumnya",
                    "sNext": "Berikutnya"
                }
            },

            "aLengthMenu": [
                [5, 15, 20, -1],
                [5, 15, 20, "Semua"] // change per page values here
            ],

            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }, ],
        });
    });


    // filterSelection("adjustment")
    // function filterSelection(c) {
    //     var x, i;
    //     x = document.getElementsByClassName("filterDiv");
    //     if (c == "all") c = "";
    //     for (i = 0; i < x.length; i++) {
    //         w3RemoveClass(x[i], "show");
    //         if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
    //     }
    // }

    // function w3AddClass(element, name) {
    //     var i, arr1, arr2;
    //     arr1 = element.className.split(" ");
    //     arr2 = name.split(" ");
    //     for (i = 0; i < arr2.length; i++) {
    //         if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
    //     }
    // }

    // function w3RemoveClass(element, name) {
    //     var i, arr1, arr2;
    //     arr1 = element.className.split(" ");
    //     arr2 = name.split(" ");
    //     for (i = 0; i < arr2.length; i++) {
    //         while (arr1.indexOf(arr2[i]) > -1) {
    //             arr1.splice(arr1.indexOf(arr2[i]), 1);     
    //         }
    //     }
    //     element.className = arr1.join(" ");
    // }

    // var btnContainer = document.getElementById("myBtnContainer");
    // var btns = btnContainer.getElementsByClassName("btn");
    // for (var i = 0; i < btns.length; i++) {
    //     btns[i].addEventListener("click", function(){
    //         var current = document.getElementsByClassName("btn-primary");
    //         current[0].className = current[0].className.replace(" btn-primary", "");
    //         this.className += " btn-primary";
    //     });
    // }

    // var save_method; //for save method string
    // var table;

    // $(document).ready(function() {

    // $('#table').DataTable({ 

    //     // "processing": true, //Feature control the processing indicator.
    //     // "serverSide": true, //Feature control DataTables' server-side processing mode.
    //     // "order": [], //Initial no order.

    //     // // Load data for the table's content from an Ajax source
    //     // "ajax": {
    //     //     "url": "<?php echo site_url('inventory_tso_log/ajax_list') ?>",
    //     //     "type": "POST"
    //     // },

    // 	"oLanguage": {
    //                 "sEmptyTable": "Tidak ada data",
    //                 "sInfoEmpty": "Tidak ada data",
    //                 "sInfoFiltered": " - Dipilih dari _MAX_ data",
    //                 "sSearch": "Pencarian Data : ",
    //                 "sInfo": " Jumlah _TOTAL_ Data (_START_ - _END_)",
    //                 "sLengthMenu": "_MENU_ Baris",
    //                 "sZeroRecords": "Tida ada data",
    //                 "oPaginate": {
    //                     "sPrevious": "Sebelumnya",
    //                     "sNext": "Berikutnya"
    //                 }
    //             },

    // 	"aLengthMenu": [
    //                 [5, 15, 20, -1],
    //                 [5, 15, 20, "Semua"] // change per page values here
    //             ],		

    //     //Set column definition initialisation properties.
    //     "columnDefs": [
    //     { 
    //         "targets": [ -1 ], //last column
    //         "orderable": false, //set not orderable
    //     },
    //     ],

    // });

    //datepicker
    // $('.datepicker').datepicker({
    //     autoclose: true,
    //     format: "yyyy-mm-dd",
    //     todayHighlight: true,
    //     orientation: "top auto",
    //     todayBtn: true,
    //     todayHighlight: true,  
    // });

    // //set input/textarea/select event when change value, remove class error and remove text help block 
    // $("input").change(function(){
    //     $(this).parent().parent().removeClass('has-error');
    //     $(this).next().empty();
    // });
    // $("textarea").change(function(){
    //     $(this).parent().parent().removeClass('has-error');
    //     $(this).next().empty();
    // });
    // $("select").change(function(){
    //     $(this).parent().parent().removeClass('has-error');
    //     $(this).next().empty();
    // });

    // });

    // $(document).ready(function() {
    //     ComponentsPickers.init();
    // });

    // function add_data(){
    //     save_method = 'add';
    //     $('#form')[0].reset(); // reset form on modals
    //     $('.form-group').removeClass('has-error'); // clear error class
    //     $('.help-block').empty(); // clear error string
    //     $('#modal_form').modal('show'); // show bootstrap modal
    //     $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    // }

    // function cetaklembarso(){    
    //     $('#form')[0].reset(); // reset form on modals
    //     $('.form-group').removeClass('has-error'); // clear error class
    //     $('.help-block').empty(); // clear error string
    //     $('#cetaklembarso').modal('show'); // show bootstrap modal
    //     $('.modal-title').text('Lembar Stock Opname'); // Set Title to Bootstrap modal title
    // }

    // function edit_data(id){
    //     save_method = 'update';
    //     $('#form')[0].reset(); // reset form on modals
    //     $('.form-group').removeClass('has-error'); // clear error class
    //     $('.help-block').empty(); // clear error string

    //     //Ajax Load data from ajax
    //     $.ajax({
    //         url : "<?php echo site_url('inventory_tso_log/ajax_edit/') ?>/" + id,
    //         type: "GET",
    //         dataType: "JSON",
    //         success: function(data)
    //         {

    //             $('[name="kodeitem"]').val(data.kodeitem);
    //             $('[name="satuan1"]').val(data.satuan1);
    // 			$('[name="satuan2"]').val(data.satuan2);
    // 			$('[name="satuan3"]').val(data.satuan3);
    // 			$('[name="satuan4"]').val(data.satuan4);
    // 			$('[name="qty1"]').val(data.qty1);
    //             $('[name="qty2"]').val(data.qty2);
    // 			$('[name="qty3"]').val(data.qty3);
    // 			$('[name="qty4"]').val(data.qty4);
    //             $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
    //             $('.modal-title').text('Edit Data'); // Set title to Bootstrap modal title

    //         },
    //         error: function (jqXHR, textStatus, errorThrown)
    //         {
    //             alert('Error get data from ajax');
    //         }
    //     });
    // }

    // function reload_table(){
    //     table.ajax.reload(null,false); //reload datatable ajax 
    // }

    // function save(){
    //     $('#btnSave').text('saving...'); //change button text
    //     $('#btnSave').attr('disabled',true); //set button disable 
    //     var url;

    //     url = "<?php echo site_url('inventory_tso_log/save/1') ?>";

    //     $.ajax({
    //         url : url,
    //         type: "POST",
    //         data: $('#form').serialize(),
    //         dataType: "JSON",
    //         success: function(data)
    //         {

    //             if(data.status) //if success close modal and reload ajax table
    //             {
    //                 $('#modal_form').modal('hide');
    //                 reload_table();
    //             }
    //             else
    //             {
    //                 for (var i = 0; i < data.inputerror.length; i++) 
    //                 {
    //                     $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
    //                     $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
    //                 }
    //             }
    //             $('#btnSave').text('Simpan'); //change button text
    //             $('#btnSave').attr('disabled',false); //set button enable 


    //         },
    //         error: function (jqXHR, textStatus, errorThrown)
    //         {
    //             alert('Error adding / update data');
    //             $('#btnSave').text('save'); //change button text
    //             $('#btnSave').attr('disabled',false); //set button enable 

    //         }
    //     });
    // }

    // function delete_data(id){
    //     if(confirm('Yakin data barang dengan kode '+id+' ini akan dihapus ?'))
    //     {
    //         // ajax delete data to database
    //         $.ajax({
    //             url : "<?php echo site_url('inventory_tso_log/ajax_delete') ?>/"+id,
    //             type: "POST",
    //             dataType: "JSON",
    //             success: function(data)
    //             {
    //                 //if success reload ajax table
    //                 $('#modal_form').modal('hide');
    //                 reload_table();
    //             },
    //             error: function (jqXHR, textStatus, errorThrown)
    //             {
    //                 alert('Error deleting data');
    //             }
    //         });

    //     }
    // }

    // function cetaklap(str){
    //   var xhttp;
    //   if (str == "") {
    //     document.getElementById("txtHint").innerHTML = "";
    //     return;
    //   }

    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("txtHint").innerHTML = this.responseText;
    //     }
    //   };  

    //   xhttp.open("GET", "<?php echo base_url(); ?>inventory_tso/cetak/"+str, true);  
    //   xhttp.send();
    // }

    // function Run(){
    //   var rak  = $('#rak').val();

    //   if(rak==''){
    // 	alert('Merk dan Rak belum dipilih ...');  
    //   } else {
    //   var str = rak;  
    //   var xhttp;
    //   if (str == "") {
    //     document.getElementById("txtHint").innerHTML = "";
    //     return;
    //   }
    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("txtHint").innerHTML = this.responseText;
    //     }
    //   };
    //   xhttp.open("GET", "<?php echo base_url(); ?>inventory_tso/getitem/"+str, true);  
    //   xhttp.send();
    //   }
    // }

    // function GetFormulir(){
    //   var rak  = $('#rak2').val();
    //   var tgl  = $('#tanggal2').val();

    //   if(rak==''){
    // 	alert('Rak belum dipilih ...');  
    //   } else {
    //   var str = rak+'~'+tgl;  

    //   var xhttp;
    //   if (str == "") {
    //     document.getElementById("txtHint2").innerHTML = "";
    //     return;
    //   }
    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("txtHint2").innerHTML = this.responseText;
    //     }
    //   };  
    //   $("#txtHint2").html('<iframe src="<?php echo base_url(); ?>inventory_tso/cetakformulir/'+str+'" frameborder="no" width="100%" height="300"></iframe>');  
    //   }
    // }

    // function GetSO() {
    //   var rak  = $('#rak3').val();
    //   var tgl  = $('#tanggal3').val();

    //   if(rak==''){
    // 	alert('Rak belum dipilih ...');  
    //   } else {
    //   var str = rak+'~'+tgl;  

    //   var xhttp;
    //   if (str == "") {
    //     document.getElementById("_hasilso").innerHTML = "";
    //     return;
    //   }
    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("_hasilso").innerHTML = this.responseText;
    //     }
    //   };  
    //   $("#_hasilso").html('<iframe src="<?php echo base_url(); ?>inventory_tso/hasilso/'+str+'" frameborder="no" width="100%" height="300"></iframe>');  
    //   }
    // }

    // function GetSOsel() {
    //   var rak  = $('#rak4').val();
    //   var tgl  = $('#tanggal4').val();

    //   if(rak==''){
    // 	alert('Rak belum dipilih ...');  
    //   } else {
    //   var str = rak+'~'+tgl;  

    //   var xhttp;
    //   if (str == "") {
    //     document.getElementById("_hasilsosel").innerHTML = "";
    //     return;
    //   }
    //   xhttp = new XMLHttpRequest();
    //   xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {
    //     document.getElementById("_hasilsosel").innerHTML = this.responseText;
    //     }
    //   };  
    //   $("#_hasilsosel").html('<iframe src="<?php echo base_url(); ?>inventory_tso/hasilsosel/'+str+'" frameborder="no" width="100%" height="300"></iframe>');  
    //   }
    // }
</script>

<script>
    $(document).ready(function() {
        ComponentsPickers.init();
    });
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Data kategori</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">


                        <table class="table">
                            <!--table class="table table-striped table-bordered table-condensed table-scrollable"-->
                            <tr>
                                <td>Kode Rak</td>
                                <td> : </td>
                                <td>
                                    <select required name="rak" id="rak" class="form-control input-medium">
                                        <option value="">--Pilih --</option>
                                        <?php
                                        foreach ($rak as $db) { ?>
                                            <option value="<?php echo $db['kode']; ?>"><?php echo $db['nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </td>

                                <td>Tanggal</td>
                                <td> : </td>
                                <td>
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input name="tanggal" class="form-control date-picker input-medium" type="text" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy" data-date-viewmode="years" placeholder="" onkeypress="return tabE(this,event)" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> </td>
                                <td>

                                </td>

                                <td width="70"><button type="button" onclick="Run()" class="btn btn-success">RUN</button></td>
                            </tr>


                        </table>


                        <div class="form-group">
                            <div id="txtHint"></div>
                        </div>


                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p align="center">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="formulirso" role="dialog">
    <div class="modal-dialog-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Lembar SO</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">


                        <table class="table">
                            <tr>
                                <td>Kode Rak</td>
                                <td> : </td>
                                <td>
                                    <select required name="rak2" id="rak2" class="form-control input-medium">
                                        <option value="">--Pilih --</option>
                                        <?php
                                        foreach ($rak as $db) { ?>
                                            <option value="<?php echo $db['kode']; ?>"><?php echo $db['nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </td>

                                <td>Tanggal</td>
                                <td> : </td>
                                <td>

                                    <input name="tanggal2" id="tanggal2" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                </td>
                                <td><button type="button" onclick="GetFormulir()" class="btn btn-success">RUN</button>
                                </td>
                            </tr>

                        </table>

                        <div class="form-group">
                            <div id="txtHint2"></div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p align="center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="hasilso" role="dialog">
    <div class="modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Hasil Stok Opname</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form2" class="form-horizontal">
                    <div class="form-body">


                        <table class="table">
                            <tr>
                                <td>Kode Rak</td>
                                <td> : </td>
                                <td>
                                    <select required name="rak3" id="rak3" class="form-control input-medium">
                                        <option value="">--Pilih --</option>
                                        <?php
                                        foreach ($rak as $db) { ?>
                                            <option value="<?php echo $db['kode']; ?>"><?php echo $db['nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </td>

                                <td>Tanggal</td>
                                <td> : </td>
                                <td>
                                    <input name="tanggal3" id="tanggal3" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                </td>
                                <td width="10"><button type="button" onclick="GetSO()" class="btn btn-success">RUN</button></td>
                            </tr>

                        </table>

                        <div class="form-group">
                            <div id="_hasilso"></div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p align="center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="hasilsosel" role="dialog">
    <div class="modal-dialog-large">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Hasil Stok Opname dengan selisih</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id" />
                    <div class="form-body">


                        <table class="table">
                            <tr>
                                <td>Kode Rak</td>
                                <td> : </td>
                                <td>
                                    <select required name="rak4" id="rak4" class="form-control input-medium">
                                        <option value="">--Pilih --</option>
                                        <?php
                                        foreach ($rak as $db) { ?>
                                            <option value="<?php echo $db['kode']; ?>"><?php echo $db['nama']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="help-block"></span>
                                </td>

                                <td>Tanggal</td>
                                <td> : </td>
                                <td>

                                    <input name="tanggal4" id="tanggal4" class="form-control input-medium" type="date" value="<?php echo date('Y-m-d'); ?>" />

                                </td>
                                <td><button type="button" onclick="GetSOsel()" class="btn btn-success">RUN</button>
                                </td>
                            </tr>

                        </table>

                        <div class="form-group">
                            <div id="_hasilsosel"></div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p align="center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->