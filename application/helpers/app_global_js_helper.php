<?php

// function formatCurrency1(num) {
// 	num = num.toString().replace(/\$|\,/g,'');
// 	if(isNaN(num))
// 	num = "0";
// 	sign = (num == (num = Math.abs(num)));
// 	num = Math.floor(num*100+0.50000000001);
// 	cents = num%100;
// 	num = Math.floor(num/100).toString();
// 	if(cents<10)
// 	cents = "0" + cents;
// 	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
// 	num = num.substring(0,num.length-(4*i+3))+','+
// 	num.substring(num.length-(4*i+3));
// 	//return (((sign)?'':'-') + '' + num + '.' + cents);
// 	return (((sign)?'':'-') + '' + num);
// 	}

// function formatCurrency(fieldObj)
// {

// 	if (isNaN(fieldObj.value)) { return false; }
// 	fieldObj.value =formatCurrency1(fieldObj.value);
// 	return true;

// }

// function ERK_pesan(judul,isi,tipe){
// 	swal(judul,isi,tipe);
// }


// function ERK_pesan_halaman(judul,isi,tipe,tombol, halaman){
//     swal({
// 		  title: judul,
// 		  html: isi,
// 		  type: tipe,
// 		  confirmButtonText: tombol
// 		 }).then((value) => {
// 			location.href = "<?php echo base_url()?>"+halaman;
// });
// }




?>