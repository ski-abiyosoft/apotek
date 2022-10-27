<?php

class TestJkn
{
   private $endLine;
   private $testSeparator;

   private $test_DecryptVclaimResponse    = true;
   private $test_VclaimTestSqlConnection  = true;
   private $test_VclaimPeserta            = true;
   private $test_PcarePeserta             = true;

   public function __construct()
   {
      $this->endLine       = "</br>";
      $this->testSeparator = "-----------------------------------------------------------------" . $this->endLine;
   }

   public function resetValues()
   {
   }

   public function testDecryptVclaimResponse()
   {
      echo $this->testSeparator;
      echo "testDecryptVclaimResponse... " . $this->endLine;

      $originalJson      = '{"peserta":{"noKartu":"0001425139852","nik":"5171047012580007","nama":"ni wayan leciani","pisa":"3","sex":"P","mr":{"noMR":null,"noTelepon":null},"tglLahir":"1958-12-30","tglCetakKartu":"2014-08-20","tglTAT":"2050-01-01","tglTMT":"2014-08-20","statusPeserta":{"kode":"9","keterangan":"NON AKTIF KARNA PREMI"},"provUmum":{"kdProvider":null,"nmProvider":null},"jenisPeserta":{"kode":"14","keterangan":"PEKERJA MANDIRI"},"hakKelas":{"kode":"1","keterangan":"KELAS I"},"umur":{"umurSekarang":"62 tahun, 9 bulan, 8 hari","umurSaatPelayanan":"62 tahun, 9 bulan, 8 hari"},"informasi":{"dinsos":null,"prolanisPRB":null,"noSKTM":null},"cob":{"noAsuransi":null,"nmAsuransi":null,"tglTMT":null,"tglTAT":null}}}';
      $cryptedCompressed = "LsQ62ws8OYFXuQDCqzW3yE4LMXcV/FToDF7FG4T90+tTu/YtWAoZyGE9+2OtC/IKaztoIKnZPCYuV38eBShrPmnKY6PdEj0CNm369J78ASAMUsqsuCBBn4Sf2VJlzQ/LVBWECDgGbQk505d4zCsUA+0sef3wLovo8EHG+7GaFzKI4+/J7xQFdMVYZelC9E/v2kLy9iSdwNgcE80um8NF6etGoMpjeTCBbct9tJLzC4IV+Oev3WizUsgs3sVp2+Jjs9OBXjvg5rSkRCqu6qaVK/5/M8dYqBQRgb7il94os0GHDuesWDV+FBF2r8ykMTGIMSb+WPXnqftqIaghETAIRPHDqQ4DDjbEKaLIq5EJPrfOI0Od5u9pcwKLG4ZH5mjqD+QiIgt/72hbqXacxVPQhCYyujyxk8o0tS22Oj+6B6K9qfVEljM1sA3ZcKaE7zhi6OQ867zw1o2U1AyDEwwbZ+HdGX2eEo3Gy5vVFkW/+c+t3IpbEPYoHceFcidPKhmUIERCWuuNFIBTr6nMMT/tn+8PZfVLOn4T6qKTy34Sf2AFPV0JDqDml9bisSTUsNgg0GMYYXke3+krH7ELvR7Qeg0gplZn1R8Ug7tGblYESjh1/ba6vYQwSpK+QsnRmP6wV5UP3gMeKaMfMCnK5ziNP8SvFXFThojHrcoAj4khELZQ2PGECZ55tZd0i8CP0acQKdK9SnY1WMoqjIeRxt6te6GhzVCoDfrpLqc7KvcUOArApGMxaOQi2pZ7JEqu/H8v0aU25UeIqJFiRZ6DFvE9OJsXujf5oBbwSZxY6TJjGuNX0tchlnqXb8kpLNfb5c7YvGpC+oJtK5QS9mfdXN/mVu9TrmBkGtSSzFr72K2pDro=";
      $decryptKey        = "77987qB19240021633700849";

      $clearResponse = vclaim_get_clear_response($decryptKey, $cryptedCompressed);

      echo $clearResponse . $this->endLine;

      if ( $clearResponse != $originalJson )
          echo "FAILED !". $this->endLine;
      else
          echo "SUCCESS !". $this->endLine;
   }

   public function testVclaimTestSqlConnection()
   {
      echo $this->testSeparator;
      echo "testVclaimTestSqlConnection... " . $this->endLine;

      try
      {
         $connString  = "Driver={MySQL ODBC 8.0 Unicode Driver};SERVER=127.0.0.1;PORT=3306;DATABASE=ski_faskes;USER=admin_rsbr;PASSWORD=@GAGV35GOPOP;charset=utf8";
         $sqlResult   = vclaim_test_sql_connection($connString);

         echo "SQL Result :" . $this->endLine;

         if (is_array($sqlResult))
            var_dump(json_decode($sqlResult["Table Bpjsset"]));
         else
            var_dump(json_decode($sqlResult));

         echo $this->testSeparator;

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

   public function testPcarePeserta()
   {
      $this->resetValues();
      echo $this->testSeparator;
      echo "testPcarePeserta... " . $this->endLine;

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

         if ($result)
         {
            $response = $service->response();
            echo "<code>" . $this->endLine;
            var_dump(json_decode($response));
            echo "</code>" . $this->endLine;
         }
         else
         {
            echo "<code>" . $this->endLine;
            echo $service->response() . $this->endLine;
            echo "</code>" . $this->endLine;
         }
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }

   public function testVclaimPeserta()
   {
      $this->resetValues();
      echo $this->testSeparator;
      echo "testVclaimPeserta... " . $this->endLine;

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

         if ($result)
         {
            $response = $service->response();
            var_dump(json_decode($response));
         }
         else {
            echo $service->response() . $this->endLine;
         }
      }
      catch (Exception $e)
      {
         echo "Error: " . $e->getMessage() . $this->endLine;
      }
   }


   public function runTests()
   {
      if ($this->test_DecryptVclaimResponse)
         $this->testDecryptVclaimResponse();

      if ($this->test_VclaimTestSqlConnection)
         $this->testVclaimTestSqlConnection();

      if ($this->test_PcarePeserta)
         $this->testPcarePeserta();

      if ($this->test_VclaimPeserta)
         $this->testVclaimPeserta();
   }
}

$testJkn = new TestJkn();
$testJkn->runTests();
