<script>
   // $( document ).ready(function() {

      // saya nambah ini
      initailizeSelect2_resep();

      // aha nambah ini
      initailizeSelect2_depo();
      // Nambah ini

      // Bedah Central


      initailizeSelect2();
      initailizeSelect2_promo();
      initailizeSelect2_vouchersource();
      initailizeSelect2_penjamin();
      initailizeSelect2_hadiah();
      initailizeSelect2_barang();
      initailizeSelect2_kasbank();
      initailizeSelect2_kasbankedc();
      initailizeSelect2_cabang();
      initailizeSelect2_cabang_all();
      initailizeSelect2_pendapatan();
      initailizeSelect2_dept();
      initailizeSelect2_provinsi();
      initailizeSelect2_kota('');
      initailizeSelect2_kecamatan('');
      initailizeSelect2_agama();
      initailizeSelect2_pendidikan();
      initailizeSelect2_pekerjaan();
      initailizeSelect2_pasien();
      initailizeSelect2_poli();
      initailizeSelect2_dokter('');
      initailizeSelect2_perawat();
      initailizeSelect2_register('');
      initailizeSelect2_registerresep('');
      initailizeSelect2_tarif_tindakan('');
      initailizeSelect2_vendor();
      initailizeSelect2_rekening_vendor();
      initailizeSelect2_resepobat();
      initailizeSelect2_farmasi_barang();
      initailizeSelect2_farmasi_barang_cbg();
      initailizeSelect2_farmasi_barang2();
      initailizeSelect2_poli_tindakan('');
      initailizeSelect2_farmasi_baranggud();
      select2_el_alkes();
      initailizeSelect2_farmasi_barangdata();
      initailizeSelect2_log_barangdata();
      initailizeSelect2_farmasi_user_2();
      initailizeSelect2_farmasi_user();
      initailizeSelect2_farmasi_baranggudso();
      initailizeSelect2_farmasi_depo();
      initailizeSelect2_logistik_depo();
      initailizeSelect2_farmasi_permohonan();
      initailizeSelect2_icdind('');
      initailizeSelect2_jnsicd();
      initailizeSelect2_farmasi_po('');
      initailizeSelect2_farmasi_po2('');
      initailizeSelect2_farmasi_po3('');
      initailizeSelect2_pembayaran();
      initailizeSelect2_log_barang();
      initailizeSelect2_log_baranggud();
      initailizeSelect2_logistik_permohonan();
      initailizeSelect2_preposition();
      initailizeSelect2_statuspasien();
      initailizeSelect2_goldarah();
      initailizeSelect2_jenispasien();
      initailizeSelect2_akunBiaya();
      initailizeSelect2_voucher_penjualan();
      initailizeSelect2_pos();
      initailizeSelect2_costcentre();
      initailizeSelect2_jenisfaktur();
      initailizeSelect2_akundiskonadjust();
      initailizeSelect2_custid();
      initailizeSelect2_akunpendapatan();
      initailizeSelect2_tarif();
      initailizeSelect2_cabangg();
      initailizeSelect2_jenis_penyakit();
      initailizeSelect2_tarif_erad('');
      initailizeSelect2_tarif_erad2();
      initailizeSelect2_resep_retur();
   // });

   function initailizeSelect2_tarif_erad(unit){
      $(".select2_el_tarif_erad").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Tindakan ---',
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_tindakan_erad/?unit="+ unit,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_tarif_erad2(unit){
      $(".select2_el_tarif_erad2").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Tindakan ---',
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_tindakan_erad2/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_akunpendapatan() {
      $(".select2_el_akunpendapatan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Akun Pendapatan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_akunpendapatan",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_cabangg() {
      $(".select2_el_cabangg").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Cabang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_cabangg",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   
   function initailizeSelect2_tarif() {
      $(".select2_el_tarif").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Kelompok Tarif ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_tarif",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_depo() {
      $(".select2_depo").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih depo ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_depo",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_user_2() {
      $(".select2_el_farmasi_user_2").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Orang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_user_2",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_user_2() {
      $(".select2_el_farmasi_user_2").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Orang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_user_2",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_user() {
      $(".select2_el_farmasi_user").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Orang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_user",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_voucher_penjualan() {
      $(".select2_el_voucher_penjualan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Voucher ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan No Voucher Untuk Mencari';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_voucher",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_jenispasien() {
      $(".select2_el_jenispasien").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih  ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_jenispasien",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_goldarah() {
      $(".select2_el_goldarah").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih  ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_goldarah",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_statuspasien() {
      $(".select2_el_statuspasien").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih  ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_statuspasien",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_preposition() {
      $(".select2_el_preposition").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih  ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_preposition",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_pembayaran() {
      $(".select2_el_pembayaran").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pembayaran---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_seting_hms/?kode=PAYM",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_farmasi_po(vendor) {
      $(".select2_el_farmasi_po").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih PO ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_po/?vendor=" + vendor,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_po2(vendor) {
      $(".select2_el_farmasi_po2").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih PO ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_logistik_po/?vendor=" + vendor,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_po3(vendor) {
      $(".select2_el_farmasi_po3").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih PO ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_logistik_po2/?vendor=" + vendor,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   /*function initailizeSelect2_farmasi_permohonan( dari, ke ){      
      $(".select2_el_farmasi_permohonan").select2({
   	 allowClear: true,
        multiple: false,  
   	 placeholder: '--- Pilih PO ---',  
        //minimumInputLength: 2,
   	 dropdownAutoWidth : true,
   	 language: {
   		inputTooShort: function() {
   			return 'Ketikan Nomor minimal 2 huruf';
   		}
   	  },  
        ajax: {
          url: "<?php echo base_url(); ?>app/search_farmasi_permohonan/?dari="+dari+'&ke='+ke,
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
   }*/

   function initailizeSelect2_farmasi_permohonan() {
      $(".select2_el_farmasi_permohonan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Depo ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Minimal 2 Huruf';
            }
         },
         ajax: {
            url: "/app/search_farmasi_permohonan/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_logistik_permohonan() {
      $(".select2_el_logistik_permohonan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih PO ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_logistik_permohonan/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_barang(modal = null) {
      $(".select2_el_farmasi_barang").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownParent: $(modal),
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_barang2",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_barang() {
      $(".select2_el_farmasi_barang").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_barang2",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_barang_cbg() {
      $(".select2_el_farmasi_barang_cbg").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_barang_cbg",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function select2_el_alkes(gudang = "") {
      $(".select2_el_alkes").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_barang_alkes/"+ gudang,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function select2_el_resep(gud) {
      $(".select2_el_farmasi_baranggud").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_baranggud/?gud=" + gud,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_barangdata() {
      $(".select2_el_farmasi_barangdata").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>App/databarang",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_log_barangdata() {
      $(".select2_el_log_barangdata").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>App/databaranglog",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_barang2() {
      $(".select2_el_farmasi_barang2").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_barang2",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_poli_tindakan(kodpos) {
      $(".select2_el_poli_tindakan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Tindakan    ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_poli_tindakan/?kodpos=" + kodpos,
            type: "post",
            dataType: 'json',
            delay: 200,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_log_baranggud(gud) {
      $(".select2_el_log_baranggud").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_log_baranggud/?gud=" + gud,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_baranggud(gud) {
      $(".select2_el_farmasi_baranggud").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_baranggud/?gud=" + gud,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_jnsicd() {
      $(".select2_el_jnsicd").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Jenis ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_jnsicd",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_icdind(sab) {
      $(".select2_el_icdind").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_icdind/?sab=" + sab,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_baranggudso(gud) {
      $(".select2_el_farmasi_baranggudso").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_baranggudso/?gud=" + gud,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },
            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_log_barang() {
      $(".select2_el_log_barang").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_log_barang",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_logistik_depo() {
      $(".select2_el_logistik_depo").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Gudang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_logistik_depo",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_farmasi_depo() {
      $(".select2_el_farmasi_depo").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Gudang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_farmasi_depo",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_vendor() {
      $(".select2_el_vendor").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Vendor ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_vendor",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_rekening_vendor() {
      $(".select2_el_rekening_vendor").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Rekening ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_rekening_vendor",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }



   function initailizeSelect2_jenisfaktur() {
      $(".select2_el_jenisfaktur").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Jenis Faktur ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_jenis_faktur",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_resepobat() {
      $(".select2_el_resepobat").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Resep---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_resep_obat",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_tarif_tindakan(poli) {
      $(".select2_el_tarif_tindakan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Tindakan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_tarif_tindakan/?poli=" + poli,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_register(poli) {
      $(".select2_el_registrasi").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Register ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_register/?poli=" + poli,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_registerresep(poli) {
      $(".select2_el_registrasiresep").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Register ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Nomor minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_register_resep/?poli=" + poli,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_pasien() {
      $(".select2_el_pasien").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pasien ---',
         minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Rek-Med/Nama/Alamat/Tgl. Lahir (format : yyyy-mm-dd)/No. HP minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_pasien",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               console.log(response);
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   // function initailizeSelect2_resep(){   
   // $(".select2_el_resep").select2({
   //  allowClear: true,
   //   multiple: false,  
   //  placeholder: '--- Pilih Kwitansi Obat ---',  
   //   minimumInputLength: 2,
   //  dropdownAutoWidth : true,
   //  language: {
   // 	inputTooShort: function() {
   // 		return 'Ketikan';
   // 	}
   //   },  
   //   ajax: {
   //     url: "<?php echo base_url(); ?>app/search_all_resep_obat",
   //     type: "post",
   //     dataType: 'json',
   //    delay: 250,
   //     data: function (params) {
   //        return {
   //          searchTerm: params.term // search term
   //        };
   //     },

   //     processResults: function (response) {
   //        return {
   //           results: response
   //        };
   //     },
   //     cache: true
   //   }
   // });
   // }

   function initailizeSelect2_resep() {
      $(".select2_el_resep").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Poli ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_all_resep_obat",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_resep_retur() {
      $(".select2_el_resep_retur").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Poli ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_all_resep_obat_retur",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_poli() {
      $(".select2_el_poli").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Unit Bisnis ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_poli",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_dokter(poli) {
      $(".select2_el_dokter").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Dokter ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_dokter/"+ poli,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_jenis_penyakit() {
      $(".select2_jenis_penyakit").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Penyakit ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_warnao",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_perawat(poli) {
      $(".select2_el_perawat").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Perawat ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama/Alamat minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_perawat/"+ poli,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_pekerjaan() {
      $(".select2_el_pekerjaan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pekerjaan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_pekerjaan",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_agama() {
      $(".select2_el_agama").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Agama ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_agama",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_pendidikan() {
      $(".select2_el_pendidikan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pendidikan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_pendidikan",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_provinsi() {
      $(".select2_el_provinsi").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Provinsi ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Provinsi minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_provinsi",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_kota(prov) {
      $(".select2_el_kota").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Kabupaten/Kota ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kabupaten/Kota minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_kota/?kode=" + prov,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_kecamatan(kota) {
      $(".select2_el_kecamatan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Kecamatan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kecamatan minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_kecamatan/?kode=" + kota,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_dept() {

      $(".select2_el_dept").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Dept ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Dept minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_dept",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_cabang() {

      $(".select2_el_cabang").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Cabang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_cabang",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_cabang_all() {

      $(".select2_el_cabang_all").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Cabang ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_cabang_all",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_pendapatan() {

      $(".select2_el_pendapatan").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pendapatan ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_pendapatan",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_custid() {

      $(".select2_custid").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Pembeli ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>Penjualan_cabang/search_cust",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_pos() {

      $(".select2_el_pos").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih POS ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama POS minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_pos",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_costcentre() {

      $(".select2_el_costcentre").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Cost Centre ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Cost Centre minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_costcentre",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_kasbank() {

      $(".select2_el_kasbank").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Kas/Bank ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_kasbank",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }



   function initailizeSelect2_akundiskonadjust() {

      $(".select2_el_akundiskonadjust").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_akundiskonadjust",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_kasbankedc() {

      $(".select2_el_kasbankedc").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Kas/Bank ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_bankedc",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_promo() {

      $(".select2_el_promo").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_promo",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_vouchersource() {

      $(".select2_el_vouchersource").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_vouchersource",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_penjamin() {

      $(".select2_el_penjamin").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_penjamin",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_hadiah() {

      $(".select2_el_hadiah").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Kas/Bank minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_hadiah",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2() {

      $(".select2_el").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Akun ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Akun minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function initailizeSelect2_barang() {

      $(".select2_el_barang").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Barang ---',
         minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Barang minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_barang",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }


   function initailizeSelect2_akunBiaya() {

      $(".select2_el_akunbiaya").select2({
         allowClear: true,
         multiple: false,
         placeholder: '--- Pilih Akun Biaya ---',
         //minimumInputLength: 2,
         dropdownAutoWidth: true,
         language: {
            inputTooShort: function() {
               return 'Ketikan Kode/Nama Akun Biaya minimal 2 huruf';
            }
         },
         ajax: {
            url: "<?php echo base_url(); ?>app/search_akunbiaya",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function(params) {
               return {
                  searchTerm: params.term // search term
               };
            },

            processResults: function(response) {
               return {
                  results: response
               };
            },
            cache: true
         }
      });
   }

   function hitung_usia(tgllahir) {
      var birthDate = new Date(tgllahir);
      const EPOCH = new Date(0);
      const EPOCH_YEAR = EPOCH.getUTCFullYear();
      const EPOCH_MONTH = EPOCH.getUTCMonth();
      const EPOCH_DAY = EPOCH.getUTCDate();

      const diff = new Date(Date.now() - birthDate.getTime());

      var years = Math.abs(diff.getUTCFullYear() - EPOCH_YEAR);
      var months = Math.abs(diff.getUTCMonth() - EPOCH_MONTH);
      var days = Math.abs(diff.getUTCDate() - EPOCH_DAY);
      var age = years + ' Tahun ' + months + ' Bulan ' + days + ' Hari';
      return age;
   }
</script>

<script type="text/javascript">
   var idleTime = 0;
   $(document).ready(function() {

      // Increment the idle time counter every minute.
      var idleInterval = setInterval(timerIncrement, 60000); // 1 minute 60000

      // Zero the idle timer on mouse movement.
      $(this).mousemove(function(e) {
         idleTime = 0;
      });
      $(this).keypress(function(e) {
         idleTime = 0;
      });


   });

   function timerIncrement() {
      idleTime = idleTime + 1;

      if (idleTime > 9) {
         var currentLocation = window.location;
         location.href = "<?= base_url(); ?>app/lock/?sesi=" + currentLocation;
      }
   }


   $('#kecamatan').click(function(event) {
      var option = "<option value=''>--- Pilih Kelurahan/Desa ---</option>"
      var id_kec = $(this).val();
      $.ajax({
         url: '<?php echo base_url() ?>app/getDesa/' + id_kec,
         type: 'GET',
         dataType: 'json',
         success: function(data) {

            console.log('test footer all');
            if (data.message == "Success") {
               $.each(data.data, function(index, val) {
                  option += "<option value='" + val.kodedesa + "'>" + val.namadesa + "</option>";
               });

               $('#kelurahan').html(option);
            }
         }
      });

   });


   $('#kelurahan').click(function(event) {
      var option = "<option value=''>--- Pilih Kelurahan/Desa ---</option>"
      var id_desa = $(this).val();
      $.ajax({
         url: '<?php echo base_url() ?>app/getKodepos/' + id_desa,
         type: 'GET',
         dataType: 'json',
         success: function(data) {
            if (data.message == "Success") {
               $('#kodepos').val(data.data);
            }
         }
      });

   });

   $('#provinsi').click(function(event) {
      var option = "<option value=''>--- Pilih Kota/Kab ---</option>"
      var id_prov = $(this).val();
      $.ajax({
         url: '<?php echo base_url() ?>app/getKota/' + id_prov,
         type: 'GET',
         dataType: 'json',
         success: function(data) {
            if (data.message == "Success") {
               $.each(data.data, function(index, val) {
                  option += "<option value='" + val.kodekab + "'>" + val.namakab + "</option>";
               });

               $('#kota').html(option);
            }
         }
      });

   });

   $('#kota').click(function(event) {
      var option = "<option value=''>--- Pilih Kecamatan ---</option>"
      var id_kota = $(this).val();
      $.ajax({
         url: '<?php echo base_url() ?>app/getKecamatan/' + id_kota,
         type: 'GET',
         dataType: 'json',
         success: function(data) {
            if (data.message == "Success") {
               $.each(data.data, function(index, val) {
                  option += "<option value='" + val.kodekec + "'>" + val.namakec + "</option>";
               });

               $('#kecamatan').html(option);
            }
         }
      });

   });
</script>