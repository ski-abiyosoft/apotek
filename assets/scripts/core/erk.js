
var Erk = function () {

function initailizeSelect2_register(){   
   $(".select2_el_registrasi").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Register ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Nomor minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_register",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_pasien(){   
   $(".select2_el_pasien").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Pasien ---',  
     minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_pasien",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_poli(){   
   $(".select2_el_poli").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Poli ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_poli",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_dokter(){   
   $(".select2_el_dokter").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Dokter ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_dokter",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_pekerjaan(){   
   $(".select2_el_pekerjaan").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Pekerjaan ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_pekerjaan",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_agama(){   
   $(".select2_el_agama").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Agama ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_agama",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_pendidikan(){   
   $(".select2_el_pendidikan").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Pendidikan ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_pendidikan",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_provinsi(){   
   $(".select2_el_provinsi").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Provinsi ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Provinsi minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_provinsi",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   
   function initailizeSelect2_dept(){
   
   $(".select2_el_dept").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Dept ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Dept minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_dept",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_cabang(){
   
   $(".select2_el_cabang").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Cabang ---',  
     //minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_cabang",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_kasbank(){
   
   $(".select2_el_kasbank").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Kas/Bank ---',  
     minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_kasbank",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2(){
   
   $(".select2_el").select2({
	 allowClear: true,
     multiple: false,  
	 placeholder: '--- Pilih Akun ---',  
     minimumInputLength: 2,
	 dropdownAutoWidth : true,
	 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Akun minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
   function initailizeSelect2_barang(){
   
   $(".select2_el_barang").select2({
	  allowClear: true,
		 multiple: false,  
		 placeholder: '--- Pilih Barang ---',  
		 minimumInputLength: 2,
		 dropdownAutoWidth : true,
		 language: {
		inputTooShort: function() {
			return 'Ketikan Kode/Nama Barang minimal 2 huruf';
		}
	  },  
     ajax: {
       url: "<?php echo base_url();?>app/search_barang",
       type: "post",
       dataType: 'json',
	   delay: 250,
       data: function (params) {
          return {
            searchTerm: params.term // search term
          };
       },
	   
       processResults: function (response) {
          return {
             results: response
          };
       },
       cache: true
     }
   });
   }
   
$('#provinsi').change(function(event) {
	var option = "<option value=''>--- Pilih Kota/Kab ---</option>"
	var id_prov = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getKota/'+id_prov,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodekab+"'>"+val.namakab+"</option>";
				});

				$('#kota').html(option);
			}
		}
	});

});

$('#poli').change(function(event) {
	var option = "<option value=''>--- Pilih Poli ---</option>"
	var id_poli = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getDokterpoli/'+id_poli,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodokter+"'>"+val.nadokter+"</option>";
				});

				$('#dokter').html(option);
			}
		}
	});

});


$('#kota').change(function(event) {
	var option = "<option value=''>--- Pilih Kecamatan ---</option>"
	var id_kota = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getKecamatan/'+id_kota,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodekec+"'>"+val.namakec+"</option>";
				});

				$('#kecamatan').html(option);
			}
		}
	});

});

$('#kecamatan').change(function(event) {
	var option = "<option value=''>--- Pilih Kelurahan/Desa ---</option>"
	var id_kec = $(this).val();			
	$.ajax({
		url: '<?php echo base_url() ?>app/getDesa/'+id_kec,
		type: 'GET',
		dataType: 'json',
		success:function(data){					
			if(data.message == "Success"){						
				$.each(data.data, function(index, val) {
					option += "<option value='"+val.kodedesa+"'>"+val.namadesa+"</option>";
				});

				$('#kelurahan').html(option);
			}
		}
	});

});

return {

        //main function to initiate the theme
        init: function () {
				
			initailizeSelect2();
			initailizeSelect2_barang();
			initailizeSelect2_kasbank();
			initailizeSelect2_cabang();
			initailizeSelect2_dept();
			initailizeSelect2_provinsi();
			initailizeSelect2_agama();
			initailizeSelect2_pendidikan();
			initailizeSelect2_pekerjaan();
			initailizeSelect2_pasien();
			initailizeSelect2_poli();
			initailizeSelect2_dokter();
			initailizeSelect2_register();
      },
	  };	
		
			
}();
	
