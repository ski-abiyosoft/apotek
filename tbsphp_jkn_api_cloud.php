<?php

class TestJkn
{
   private $endLine;
   private $testSeparator;

   private $test_EncryptDecryptPassword   = true;
   private $test_DecryptVclaimResponse    = false;
   private $test_VclaimTestSqlConnection  = false;
   private $test_VclaimPeserta            = true;

   private $test_PcareDiagnosa            = true;
   private $test_PcareDokter              = true;
   private $test_PcareKelompok            = true;
   private $test_PcareKesadaran           = true;
   private $test_PcareKunjungan           = true;
   private $test_PcareMcu                 = true;
   private $test_PcareObat                = true;
   private $test_PcarePendaftaran         = true;
   private $test_PcarePeserta             = true;
   private $test_PcarePoli                = true;
   private $test_PcareProvider            = true;
   private $test_PcareSpesialis           = true;
   private $test_PcareStatusPulang        = true;
   private $test_PcareTindakan            = true;

   public function __construct()
   {
      $this->endLine       = "</br>\n";
      $this->testSeparator = "-----------------------------------------------------------------" . $this->endLine;
   }

   public function testEncryptDecryptPassword()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test EncryptDecryptPassword... " . $this->endLine;

      // We can handle UPPER Case and lower Case
      $encrypted = "54ec4f3bf1478201aeca635ffb05a701";
      //$encrypted = "54EC4F3BF1478201AECA635FFB05A701";
      $clearPwd  = "p@55woorD_*&*l";

      $resultDec = tbsDecryptPassword($encrypted);
      if ( $clearPwd ==$resultDec  )
         echo "Decrypt Success!". $this->endLine;
      else
         echo "Decrypt Failed!". $this->endLine;

      // roundtrip
      $resultEnc  = tbsEncryptPassword($clearPwd);
      $resultDec2 = tbsDecryptPassword($resultEnc);
      if ( $clearPwd == $resultDec2 )
         echo "Encrypt Success!". $this->endLine;
      else
         echo "Encrypt Failed!". $this->endLine;
   }


   public function testDecryptVclaimResponse()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test DecryptVclaimResponse... " . $this->endLine;

      $originalJson      = '{"peserta":{"noKartu":"0001425139852","nik":"5171047012580007","nama":"ni wayan leciani","pisa":"3","sex":"P","mr":{"noMR":null,"noTelepon":null},"tglLahir":"1958-12-30","tglCetakKartu":"2014-08-20","tglTAT":"2050-01-01","tglTMT":"2014-08-20","statusPeserta":{"kode":"9","keterangan":"NON AKTIF KARNA PREMI"},"provUmum":{"kdProvider":null,"nmProvider":null},"jenisPeserta":{"kode":"14","keterangan":"PEKERJA MANDIRI"},"hakKelas":{"kode":"1","keterangan":"KELAS I"},"umur":{"umurSekarang":"62 tahun, 9 bulan, 8 hari","umurSaatPelayanan":"62 tahun, 9 bulan, 8 hari"},"informasi":{"dinsos":null,"prolanisPRB":null,"noSKTM":null},"cob":{"noAsuransi":null,"nmAsuransi":null,"tglTMT":null,"tglTAT":null}}}';
      $cryptedCompressed = "LsQ62ws8OYFXuQDCqzW3yE4LMXcV/FToDF7FG4T90+tTu/YtWAoZyGE9+2OtC/IKaztoIKnZPCYuV38eBShrPmnKY6PdEj0CNm369J78ASAMUsqsuCBBn4Sf2VJlzQ/LVBWECDgGbQk505d4zCsUA+0sef3wLovo8EHG+7GaFzKI4+/J7xQFdMVYZelC9E/v2kLy9iSdwNgcE80um8NF6etGoMpjeTCBbct9tJLzC4IV+Oev3WizUsgs3sVp2+Jjs9OBXjvg5rSkRCqu6qaVK/5/M8dYqBQRgb7il94os0GHDuesWDV+FBF2r8ykMTGIMSb+WPXnqftqIaghETAIRPHDqQ4DDjbEKaLIq5EJPrfOI0Od5u9pcwKLG4ZH5mjqD+QiIgt/72hbqXacxVPQhCYyujyxk8o0tS22Oj+6B6K9qfVEljM1sA3ZcKaE7zhi6OQ867zw1o2U1AyDEwwbZ+HdGX2eEo3Gy5vVFkW/+c+t3IpbEPYoHceFcidPKhmUIERCWuuNFIBTr6nMMT/tn+8PZfVLOn4T6qKTy34Sf2AFPV0JDqDml9bisSTUsNgg0GMYYXke3+krH7ELvR7Qeg0gplZn1R8Ug7tGblYESjh1/ba6vYQwSpK+QsnRmP6wV5UP3gMeKaMfMCnK5ziNP8SvFXFThojHrcoAj4khELZQ2PGECZ55tZd0i8CP0acQKdK9SnY1WMoqjIeRxt6te6GhzVCoDfrpLqc7KvcUOArApGMxaOQi2pZ7JEqu/H8v0aU25UeIqJFiRZ6DFvE9OJsXujf5oBbwSZxY6TJjGuNX0tchlnqXb8kpLNfb5c7YvGpC+oJtK5QS9mfdXN/mVu9TrmBkGtSSzFr72K2pDro=";
      $decryptKey        = "77987qB19240021633700849";

      $clearResponse = vclaimGetClearResponse($decryptKey, $cryptedCompressed);

      echo $clearResponse . $this->endLine;

      if ( $clearResponse != $originalJson )
          echo "FAILED !". $this->endLine;
      else
          echo "SUCCESS !". $this->endLine;
   }

   public function testVclaimTestSqlConnection()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test VclaimTestSqlConnection... " . $this->endLine;

      try
      {
         $connString  = "Driver={MySQL ODBC 8.0 Unicode Driver};SERVER=127.0.0.1;PORT=3306;DATABASE=ski_faskes;USER=admin_rsbr;PASSWORD=@GAGV35GOPOP;charset=utf8";
         $sqlResult   = testSqlConnection($connString);

         echo "SQL Result :" . $this->endLine;
         echo "<code>" . $this->endLine;

         if (is_array($sqlResult))
            var_dump(json_decode($sqlResult["Table Bpjsset"]));
         else
            var_dump(json_decode($sqlResult)). $this->endLine;

         echo "</code>" . $this->endLine . $this->endLine;

         if (is_array($sqlResult))
            echo $sqlResult["Table Bpjsset"] . $this->endLine;
         else
            echo $sqlResult . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareDiagnosa()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareDiagnosa... " . $this->endLine;

      try
      {
         $service     = new pcare\Diagnosa("0233U003");
         $result      = $service->getDiagnosa("A001", 0, 100);

         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         $kodeRs      = $service->getKodeRs();
         echo "httpCode    : " . $httpCode. $this->endLine;
         echo "metaCode    : " . $metaCode. $this->endLine;
         echo "metaMessage : " . $metaMessage. $this->endLine;
         echo "kodeRs      : " . $kodeRs. $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareDokter()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareDokter... " . $this->endLine;

      try
      {
         $service     = new pcare\Dokter("0233U003");
         $result      = $service->getDokter(0, 100);

         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         $kodeRs      = $service->getKodeRs();
         echo "httpCode    : " . $httpCode. $this->endLine;
         echo "metaCode    : " . $metaCode. $this->endLine;
         echo "metaMessage : " . $metaMessage. $this->endLine;
         echo "kodeRs      : " . $kodeRs. $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareKelompok()
   {
   }

   public function testPcareKesadaran()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareKesadaran... " . $this->endLine;

      try
      {
         $service     = new pcare\Kesadaran("0233U003");
         $result      = $service->getKesadaran();
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode. $this->endLine;
         echo "metaCode    : " . $metaCode. $this->endLine;
         echo "metaMessage : " . $metaMessage. $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareKunjungan()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareKunjungan... " . $this->endLine;

      try
      {
         $service     = new pcare\Kunjungan("0233U003");
         $result      = $service->getRujukan("0114U1630316Y000003");
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareMcu()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareMcu... " . $this->endLine;

      try
      {
         $service     = new pcare\Mcu("0233U003");
         $result      = $service->getMcu("0114U1630316Y000003");
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareObat()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareObat... " . $this->endLine;

      try
      {
         $service     = new pcare\Obat("0233U003");
         $result      = $service->getObatDpho("130199999",0,100);
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcarePendaftaran()
   {
   }

   public function testPcarePeserta()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcarePeserta... " . $this->endLine;

      try
      {
         $service     = new pcare\Peserta("0233U003");
         //$service->setKodeRs("0233U003");

         //$result      = $service->getPeserta("0001425139852");
         $result      = $service->getPesertaByJenisKartu("noka","0001425139852");

         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         $kodeRs      = $service->getKodeRs();
         echo "httpCode    : " . $httpCode. $this->endLine;
         echo "metaCode    : " . $metaCode. $this->endLine;
         echo "metaMessage : " . $metaMessage. $this->endLine;
         echo "kodeRs      : " . $kodeRs. $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcarePoli()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcarePoli... " . $this->endLine;

      try
      {
         $service     = new pcare\Poli("0233U003");
         $result      = $service->getPoli(0,100);
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareProvider()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareProvider... " . $this->endLine;

      try
      {
         $service     = new pcare\Provider("0233U003");
         $result      = $service->getProvider(0,100);
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareSpesialis()
   {
   }

   public function testPcareStatusPulang()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test PcareStatusPulang... " . $this->endLine;

      try
      {
         $service     = new pcare\StatusPulang("0233U003");
         $result      = $service->getStatusPulang(true);
         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         echo "httpCode    : " . $httpCode . $this->endLine;
         echo "metaCode    : " . $metaCode . $this->endLine;
         echo "metaMessage : " . $metaMessage . $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response() . $this->endLine;

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testPcareTindakan()
   {
   }

   public function testVclaimPeserta()
   {
      echo $this->endLine.$this->testSeparator;
      echo "Test VclaimPeserta... " . $this->endLine;

      try
      {
         $service     = new vclaim\Peserta("0033R009");
         $result      = $service->getPeserta("noka", "0001425139852", "2022-10-20");

         $httpCode    = $service->httpStatusCode();
         $metaCode    = $service->metaCode();
         $metaMessage = $service->metaMessage();
         $kodeRs      = $service->getKodeRs();
         echo "httpCode    : " . $httpCode. $this->endLine;
         echo "metaCode    : " . $metaCode. $this->endLine;
         echo "metaMessage : " . $metaMessage. $this->endLine;
         echo "kodeRs      : " . $kodeRs. $this->endLine;

         echo "<code>" . $this->endLine;

         if ($result)
         {
            $response = $service->response();
            if ($response=="null")
               echo $response;
            else
               var_dump(json_decode($response)) . $this->endLine;
         }
         else
            echo $service->response();

         echo "</code>" . $this->endLine;
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }


   public function runTests()
   {
      if ($this->test_EncryptDecryptPassword)
         $this->testEncryptDecryptPassword();

      if ($this->test_DecryptVclaimResponse)
         $this->testDecryptVclaimResponse();

      if ($this->test_VclaimTestSqlConnection)
         $this->testVclaimTestSqlConnection();

      if ($this->test_PcareDiagnosa)
         $this->testPcareDiagnosa();

      if ($this->test_PcareDokter)
         $this->testPcareDokter();

      if ($this->test_PcareKelompok)
         $this->testPcareKelompok();

      if ($this->test_PcareKesadaran)
         $this->testPcareKesadaran();

      if ($this->test_PcareKunjungan)
         $this->testPcareKunjungan();

      if ($this->test_PcareMcu)
         $this->testPcareMcu();

      if ($this->test_PcareObat)
         $this->testPcareObat();

      if ($this->test_PcarePendaftaran)
         $this->testPcarePendaftaran();

      if ($this->test_PcarePeserta)
         $this->testPcarePeserta();

      if ($this->test_PcarePoli)
         $this->testPcarePoli();

      if ($this->test_PcareProvider)
         $this->testPcareProvider();

      if ($this->test_PcareSpesialis)
         $this->testPcareSpesialis();

      if ($this->test_PcareStatusPulang)
         $this->testPcareStatusPulang();

      if ($this->test_PcareTindakan)
         $this->testPcareTindakan();

      if ($this->test_VclaimPeserta)
         $this->testVclaimPeserta();
   }
}

$testJkn = new TestJkn();
$testJkn->runTests();