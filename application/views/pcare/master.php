<?php

    $this->load->view("template/header");
    $this->load->view("template/body");

?>

<style>
    .filterDiv {display:none}
    .show {display:block}
</style>

<div class="row">
    <div class="col-md-12">
        <h3 class="page-title">
        <span class="title-unit">
                &nbsp;<?php echo $this->session->userdata('unit'); ?>
            </span>
            &nbsp;-&nbsp;
            <span class="title-web">P-Care <small>Master</small>
        </h3>
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <i class="fa fa-home title-white"></i>&nbsp; 
                <a href="<?php echo base_url()?>dashboard" class="title-white">Awal</a>&nbsp;
                <i class="fa fa-angle-right title-white"></i></li>
            <li>
                <a href="#" class="title-white">P-Care</a>&nbsp;
                <i class="fa fa-angle-right title-white"></i>&nbsp;
            </li>
            <li>
                <a href="#" class="title-white">Master</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">

            <div class="portlet-title" style="border-radius:0px !important">
                <div class="caption">
                    <i class="fa fa-reorder"></i><b>P-CARE Master</b>
                </div>
            </div>
            
            <div class="portlet-body" style="border-radius:0px !important;padding:40px 0px 40px 0px">
                <div class="row">
                    <div class="col-sm-4">&nbsp;</div>
                    <div class="col-sm-4">
                        <form id="" class="form-horizontal form-bordered1">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <select type="text" class="selectpicker" id="data" data-live-search="true" data-width="100%" data-title="- Pilih Data Master -">
                                                <?php foreach($list_master as $lmk => $lmv): ?>
                                                    <option value="<?= $lmk ?>"><?= $lmv ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <center><button type="button" class="btn green" id="show_data">Tampilkan Tabel</button></center>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">&nbsp;</div>
                </div>
            </div>

            <div class="portlet-body" style="border-radius:0px !important;border-top:1px solid #ddd;padding:20px 20px 40px 20px">
                <div class="filterDiv 01">Diagnosa</div>
                <div class="filterDiv 02">Dokter</div>
                <div class="filterDiv 03">Faskes</div>
                <div class="filterDiv 04">KDTKP</div>
                <div class="filterDiv 05">Kesadaran</div>
                <div class="filterDiv 06">Obat</div>
                <div class="filterDiv 07">Poli</div>
                <div class="filterDiv 08">Provider</div>
                <div class="filterDiv 09">Sarana</div>
                <div class="filterDiv 10">Spesialis</div>
                <div class="filterDiv 11">Sub Spesialis</div>
                <div class="filterDiv 12">Status Pulang</div>
                <div class="filterDiv 13">Tindakan</div>
            </div>

        </div>
    </div>
</div>

<?php

    $this->load->view("template/footer_all");
    $this->load->view("template/footer_tb");

?>

<script>
    $("#show_data").on("click", function(){
        var no_master = $("#data").val();
        filterSelection(no_master);
    });
    function filterSelection(c) {
    var x, i;
    x = document.getElementsByClassName("filterDiv");
        if (c == "all") c = "";
        for (i = 0; i < x.length; i++) {
            w3RemoveClass(x[i], "show");
            if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
        }
    }

    function w3AddClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
    }
    }

    function w3RemoveClass(element, name) {
    var i, arr1, arr2;
    arr1 = element.className.split(" ");
    arr2 = name.split(" ");
    for (i = 0; i < arr2.length; i++) {
        while (arr1.indexOf(arr2[i]) > -1) {
        arr1.splice(arr1.indexOf(arr2[i]), 1);     
        }
    }
    element.className = arr1.join(" ");
    }
</script>

<script>
    $(document).ready(function(){        
        $("#show_data").on("click", function(){
            var no_master = $("#data").val();
            var header, list;
            var columns = new Array();

            $.ajax({
                url         : "/pcare_master/data_header/"+ no_master,
                type        : "GET",
                dataType    : "JSON",
                beforeSend  : function(){},
                success     : function(res){
                    $(".table-title").attr("style", "padding-bottom:30px;font-size:18px;font-weight:bold;position:relative").html("<div style='position:absolute;right:0px;padding:5px 12px 5px 12px !important' class='btn btn-primary btn-md'><b>Total "+ res.total +"</b></div>"+
                    "# "+ res.title );
                    // $("#showDataTableHeader").append(res.header);

                    $('#showDataTable').dataTable().fnDestroy();

                    $.each(res.header, function(v){
                        columns.push({title: v});
                    });

                    // setTimeout(() => {
                    
                    $('#showDataTable').dataTable({
                        processing: true,
                        serverSide: true,
                        order: [],

                        ajax: {
                            url: "<?= base_url("pcare_master/data/") ?>"+ no_master,
                            type: "POST",
                        },

                        columns: columns,

                        paging: true,
                        ordering: true,

                        oLanguage: {
                            sSearch: "<i class='fa fa-search fa-fw'></i>&emsp;",
                            sSearchPlaceholder: "Cari...",
                            sLengthMenu: "Tampilkan _MENU_ Baris",
                            sEmptyTable: "<span class='text-danger'><i class='fa fa-times'></i>&nbsp; Tidak ada data</span>",
                            sInfo: "data (_START_ - _END_ baris dipilih) dari <b>_MAX_ total</b> data",
                            sInfoEmpty: "",
                            sInfoFiltered: "",
                            sZeroRecords: "Hasil pencarian tidak ditemukan",
                            oPaginate: {
                                sPrevious: "Sebelumnya",
                                sNext: "Berikutnya"
                            }
                        },

                        aLengthMenu: [
                            [5, 15, 20, -1],
                            [5, 15, 20, "Semua"]
                        ],

                        columnDefs: [{
                            orderable: true,
                            className: "text-right",
                            render: function(data, type, row){}
                        }],
                    });
                    // }, 1000);
                },
                error       : function(){
                }
            });

        });
    });
</script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>