<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://kit.fontawesome.com/7307807463.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    * {font-family: 'Poppins', sans-serif;}
    body {background:#ddd !important}
    .table-responsive {margin:20px !important;width:auto}
    .wrapper {width:90%;margin:auto}
    .form-title {margin:20px;width:auto;color:#666}
    .form {margin:20px;width:auto;padding:20px;border:1px solid #ddd;border-radius:5px}
    .form .form-label {margin:0 0 10px 0 !important;font-size:14px !important}
</style>

<div class="wrapper">

    <div class="row">
        <div class="col-sm-6">
            <div class="form-title">Put Stock Farmasi</div>
            <div class="form bg-white">
                <form id="farmasi_form" method="POST">
                    <div class="mb-3">
                        <label for="" class="form-label">Cabang</label>
                        <select type="text" class="form-select form-select-sm" name="cabang" required>
                            <?php
                                foreach($cabang as $ckey => $cval){
                                    echo "<option value='$cval->koders'>$cval->koders</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Gudang</label>
                        <select type="text" class="form-select form-select-sm" name="gudang" required>
                            <?php
                                foreach($gudang as $gkey => $gval){
                                    echo "<option value='$gval->depocode'>$gval->depocode</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm" type="button" id="save_farmasi">Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-title">Put Stock Logistik</div>
            <div class="form bg-white">
            <form id="logistik_form" method="POST">
                    <div class="mb-3">
                        <label for="" class="form-label">Cabang</label>
                        <select type="text" class="form-select form-select-sm" name="cabang" required>
                            <?php
                                foreach($cabang as $ckey => $cval){
                                    echo "<option value='$cval->koders'>$cval->koders</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gudang" class="form-label">Gudang</label>
                        <select type="text" class="form-select form-select-sm" name="gudang" required>
                            <?php
                                foreach($gudang as $gkey => $gval){
                                    echo "<option value='$gval->depocode'>$gval->depocode</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-success btn-sm" type="button" id="save_logistik">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-bordered table-striped mb-0" style="padding-bottom:0">
            <thead class="table-dark text-center">
                <tr>
                    <th>Cabang</th>
                    <?php
                        foreach($gudang as $tgkey => $tgval){
                            echo "<th>$tgval->depocode</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($cabang as $tckey => $tcval){
                        echo "
                            <tr class='text-center'>
                                <td>$tcval->koders</td>";
                                foreach($gudang as $tgkey => $tgval){
                                    $status = $this->db->query("SELECT * FROM tbl_putstockall WHERE cabang = '$tcval->koders' AND gudang = '$tgval->depocode'");
                                    if($status->num_rows() == 0){
                                        echo "<td><i class='fas fa-times-circle text-danger'></i></td>";
                                    } else {
                                        echo "<td><i class='fas fa-check-circle text-success'></i></td>";
                                    }
                                }
                        echo "</tr>
                        ";
                    }
                ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    $("#save_logistik").on("click", function(){
        var post_form = $("#logistik_form").serialize();
        $.ajax({
            url: "/putstockall/save",
            type: "POST",
            data: post_form,
            success: function (){
                location.href = '/putstockall';
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    });

    $("#save_farmasi").on("click", function(){
        var post_form = $("#farmasi_form").serialize();
        $.ajax({
            url: "/putstockall/savef",
            type: "POST",
            data: post_form,
            success: function (){
                alert('success');
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
    });
</script>