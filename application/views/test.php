<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pcare Testing Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1 class="font-bold text-3xl text-center">P-Care Testing Center - Abiyosoft</h1>
    <div class="w-screen flex flex-col items-center mt-10">
        <div class="w-['80%']">
            <div class="relative mb-5">
                <input class="p-3 border-2 border-sky-500 rounded-full w-full" type="date" id="tglEstRujuk" name="tglEstRujuk">
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="tglEstRujuk">Tanggal Rujuk:</label>
            </div>
            <div class="relative mb-5">
                <input class="p-3 border-2 border-sky-500 rounded-full w-full" type="text" id="noKartu" name="noKartu">
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="noKartu">No Kartu:</label>
            </div>
            <div class="relative mb-5">
                <select class="p-3 border-2 border-sky-500 rounded-full w-full bg-white text-sm" type="text" id="spesialis" name="spesialis">
                    <option value="">-- Pilih spesialis --</option>
                    <?php foreach ($spesialis as $sp): ?>
                        <option value="<?= $sp->kdSpesialis ?>"><?= $sp->nmSpesialis ?></option>
                    <?php endforeach ?>
                </select>
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="spesialis">Spesialis:</label>
            </div>
            <div class="relative mb-5">
                <select class="p-3 border-2 border-sky-500 rounded-full w-full bg-white text-sm" type="text" id="sarana" name="sarana">
                    <?php foreach ($sarana as $srn): ?>
                        <option value="<?= $srn->kdSarana ?>"><?= $srn->nmSarana ?></option>
                    <?php endforeach ?>
                </select>
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="sarana">Sarana:</label>
            </div>
            <div class="relative mb-5">
                <select class="p-3 border-2 border-sky-500 rounded-full w-full bg-white text-sm" type="text" id="subspesialis" name="subspesialis">
                    <option value="">Pilih spesialis dulu</option>
                </select>
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="subspesialis">Subspesialis:</label>
            </div>
            <div class="relative mb-5">
                <select class="p-3 border-2 border-sky-500 rounded-full w-full bg-white text-sm" type="text" id="kdKhusus" name="kdKhusus">
                    <option value="">Bukan Kasus Khusus</option>
                    <?php foreach ($khusus as $kh): ?>
                        <option value="<?= $kh->kdKhusus ?>"><?= $kh->nmKhusus ?></option>
                    <?php endforeach ?>
                </select>
                <label class="absolute left-5 text-sm -top-3 bg-white text-sky-500 p-1 rounded-full" for="kdKhusus">Khusus:</label>
            </div>
            <button class="p-3 rounded-full bg-emerald-500 text-white text-sm block mx-auto" type="button" onclick="getRujukan()">Ambil Data Rujukan</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script>
        var subspesialis = <?= json_encode($subspesialis) ?>;

        document.getElementById("spesialis").addEventListener("change", function () {
            var filtered = subspesialis.filter((el) => {
                return el.kdSpesialis == this.value
            })

            $("#subspesialis").empty()
            filtered.forEach((el) => {
                $("#subspesialis").append(`<option value="${el.kdSubSpesialis}">${el.nmSubSpesialis}</option>`)
            })
        })

        function getRujukan () {
            var noKartu         = $("#noKartu").val()
            var spesialis       = $("#spesialis").val()
            var subspesialis    = $("#subspesialis").val()
            var sarana          = $("#sarana").val()
            var kdKhusus        = $("#kdKhusus").val()
            var tglEstRujuk     = $("#tglEstRujuk").val()
            var url             = '<?= base_url('api/pcare') ?>'

            if (kdKhusus != "") {
                url += "/get_rujukan_khusus"
            }else {
                url += "/get_rujukan_subspesialis"
            }

            $.ajax({
                url: url,
                dataType: "json",
                data: {
                    noKartu: noKartu,
                    subspesialis: subspesialis,
                    kdKhusus: kdKhusus,
                    sarana: sarana,
                    tglEstRujuk: tglEstRujuk,
                },
                success: (data) => {
                    console.log(data)
                }
            })
        }
    </script>
</body>
</html>