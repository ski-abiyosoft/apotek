<script>

    function alltrim(kata) {
      b = (kata.split(' ').join(''));
      c = (b.replace(/\s/g, ""));
      return c
    }
   

    function playAudio(kodepos = "Z", noantri1 = "Z", antriangka = "", noantri = "satu", noreg) {

        var bel = new Audio('audio/bell_long.wav');
        var bel2 = new Audio('audio/nomor_antrian.wav');
        var belh = new Audio('audio/' + noantri1 + '.wav');
        var bel4 = new Audio('audio/belas.wav');
        var bel5 = new Audio('audio/puluh.wav');
        var bel7 = new Audio('audio/ratus.wav');
        var to = new Audio('audio/silakan_menuju_ke.wav');
        var poli = new Audio('audio/poli.wav');

        if (kodepos == 'pumum') {
            var cek_pol = new Audio('audio/umum.wav');
        } else if (kodepos == 'pgigi') {
            var cek_pol = new Audio('audio/gigi.wav');
        } else if (kodepos == 'bidan') {
            var cek_pol = new Audio('audio/ibu_dan_anak.wav');
        } else if (kodepos == 'farmasi') {
            var cek_pol = new Audio('audio/customer_service.wav');
        } else if (kodepos == 'ugd') {
            var cek_pol = new Audio('audio/customer_service.wav');
        } else {
            var cek_pol = new Audio('audio/umum.wav');
        }
        totalwaktu = 0;

        setTimeout(function() {
            bel.pause();
            bel.currentTime = 0;
            bel.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1500;
        setTimeout(function() {
            bel2.pause();
            bel2.currentTime = 0;
            bel2.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1500;
        setTimeout(function() {
            belh.pause();
            belh.currentTime = 0;
            belh.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;

        if (antriangka <= 11 || antriangka == 100) {

            var bel3 = new Audio('audio/' + noantri + '.wav');
            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1500;

        } else if (antriangka > 11 && antriangka < 20) {

            noantri2 = alltrim(noantri.split("belas").join(""));
            var bel3 = new Audio('audio/' + noantri2 + '.wav');

            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel4.pause();
                bel4.currentTime = 0;
                bel4.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        } else if (antriangka == 20 || antriangka == 30 || antriangka == 40 || antriangka == 50 || antriangka == 60 || antriangka == 70 || antriangka == 80 || antriangka == 90) {

            noantri2 = alltrim(noantri.split("puluh").join(""));
            var bel3 = new Audio('audio/' + noantri2 + '.wav');

            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            // belas
            setTimeout(function() {
                bel5.pause();
                bel5.currentTime = 0;
                bel5.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        } else if (antriangka > 20 && antriangka < 100 && antriangka != 20 && antriangka != 30 && antriangka != 40 && antriangka != 50 && antriangka != 60 && antriangka != 70 && antriangka != 80 && antriangka != 90) {

            noantri2 = alltrim(noantri.split("puluh").join("-"));
            $dat = noantri2.split("-");
            $bel3 = $dat[0];
            $bel6 = $dat[1];

            var bel3 = new Audio('audio/' + $bel3 + '.wav');
            var bel6 = new Audio('audio/' + $bel6 + '.wav');

            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            // puluh
            setTimeout(function() {
                bel5.pause();
                bel5.currentTime = 0;
                bel5.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel6.pause();
                bel6.currentTime = 0;
                bel6.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
        } else if (antriangka <= 110 || antriangka == 111) {

            noantri2 = alltrim(noantri.split("seratus").join(""));
            var bel3 = new Audio('audio/seratus.wav');
            var bel4 = new Audio('audio/' + noantri2 + '.wav');

            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel4.pause();
                bel4.currentTime = 0;
                bel4.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        } else if (antriangka > 111 && antriangka < 120) {

            noantri2 = alltrim(noantri.split("seratus").join(""));
            noantri3 = alltrim(noantri2.split("belas").join(""));
            var bel3 = new Audio('audio/seratus.wav');
            var bel11 = new Audio('audio/' + noantri3 + '.wav');

            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel11.pause();
                bel11.currentTime = 0;
                bel11.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel4.pause();
                bel4.currentTime = 0;
                bel4.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        } else if (antriangka > 120 && antriangka < 200) {

            noantri2 = alltrim(noantri.split("seratus").join(""));
            noantri3 = alltrim(noantri2.split("puluh").join("-"));
            $dat = noantri3.split("-");
            $bel8 = $dat[0];
            $bel9 = $dat[1];

            var bel3 = new Audio('audio/seratus.wav');
            var bel8 = new Audio('audio/' + $bel8 + '.wav');
            var bel9 = new Audio('audio/' + $bel9 + '.wav');

            // seratus
            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

            setTimeout(function() {
                bel8.pause();
                bel8.currentTime = 0;
                bel8.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            // puluh
            setTimeout(function() {
                bel5.pause();
                bel5.currentTime = 0;
                bel5.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel9.pause();
                bel9.currentTime = 0;
                bel9.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        } else if (antriangka > 200) {

            noantri2 = alltrim(noantri.split("ratus").join("-"));
            noantri3 = alltrim(noantri2.split("puluh").join("-"));
            $dat = noantri3.split("-");
            $bel3 = $dat[0];
            $bel8 = $dat[1];
            $bel9 = $dat[2];

            var bel3 = new Audio('audio/' + $bel3 + '.wav');
            var bel8 = new Audio('audio/' + $bel8 + '.wav');
            var bel9 = new Audio('audio/' + $bel9 + '.wav');

            // seratus
            setTimeout(function() {
                bel3.pause();
                bel3.currentTime = 0;
                bel3.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            // seratus
            setTimeout(function() {
                bel7.pause();
                bel7.currentTime = 0;
                bel7.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

            setTimeout(function() {
                bel8.pause();
                bel8.currentTime = 0;
                bel8.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            // puluh
            setTimeout(function() {
                bel5.pause();
                bel5.currentTime = 0;
                bel5.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;
            setTimeout(function() {
                bel9.pause();
                bel9.currentTime = 0;
                bel9.play();
            }, totalwaktu);
            totalwaktu = totalwaktu + 1000;

        }

        setTimeout(function() {
            to.pause();
            to.currentTime = 0;
            to.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1700;

        setTimeout(function() {
            poli.pause();
            poli.currentTime = 0;
            poli.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1000;

        setTimeout(function() {
            cek_pol.pause();
            cek_pol.currentTime = 0;
            cek_pol.play();
        }, totalwaktu);
        totalwaktu = totalwaktu + 1500;

    }
</script>