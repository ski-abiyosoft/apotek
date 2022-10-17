<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $nama ?></title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/barcodes/JsBarcode.code128.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js" integrity="sha512-01CJ9/g7e8cUmY0DFTMcUw/ikS799FHiOA0eyHsUWfOetgbx/t6oV4otQ5zXKQyIrQGTHSmRVPIgrgLcZi/WMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div id="barcode-wrapper" style="padding: 10px; width: 580px; height: 250px;">
        <div style="border: 1px solid black; padding: 10px; display: flex; flex-direction: column; align-items: center;">
            <div style="text-align: center; font-family: monospace;">NAMA BRG: <?= $nama; ?></div>
            <svg id="barcode" data-serialno="<?= $serial; ?>"></svg>
            <div style="text-align: center; font-family: monospace;">TGL PAKAI: <?= $tglpakai; ?></div>
            <div style="text-align: center; font-family: monospace;">TGL KALIBRASI: <?= $tglkalibrasi; ?></div>
        </div>
    </div>

    <script>
    var serialno = document.getElementById('barcode').dataset.serialno
    JsBarcode("#barcode", serialno)
    var node = document.getElementById('barcode-wrapper');

    domtoimage.toPng(node, {width: 600, height: 250, bgcolor: 'white',})
        .then(function (dataUrl) {
            var link = document.createElement('a');
            link.download = serialno;
            link.href = dataUrl;
            link.click();
        })
        .catch(function (error) {
            console.error('oops, something went wrong!', error);
        });
    </script>
</body>
</html>