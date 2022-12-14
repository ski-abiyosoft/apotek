<?php

require_once(APPPATH."libraries/Excel/xlsxwriter.class.php");

class Export_service
{
    private $writer;
    protected $unit_name;
    protected $address;
    protected $address2;
    protected $phone;
    protected $whatsapp;
    protected $tax_id;

    public function __construct (array $param)
    {
        if (key_exists("unit_info", $param)){
            $this->unit_name    = $param["unit_info"]->namars;
            $this->address      = $param["unit_info"]->alamat;
            $this->address2     = $param["unit_info"]->alamat2;
            $this->phone        = $param["unit_info"]->phone;
            $this->whatsapp     = $param["unit_info"]->whatsapp;
            $this->tax_id       = $param["unit_info"]->npwp;
        }

        $this->writer = new XLSXWriter();
    }

    /**
     * Method for creating document header.
     * 
     * @return void
     */
    public function set_header(): void
    {
        $this->writer->writeSheetRow('Sheet1', [$this->unit_name]);
        $this->writer->writeSheetRow('Sheet1', [$this->address]);
        $this->writer->writeSheetRow('Sheet1', [$this->address2]);
        $this->writer->writeSheetRow('Sheet1', ["Phone: " . $this->phone]);
        $this->writer->writeSheetRow('Sheet1', ["WA: " . $this->whatsapp]);
        $this->writer->writeSheetRow('Sheet1', ["NPWP: " . $this->tax_id]);
        $this->writer->writeSheetRow('Sheet1', [" "]);
    }

    /**
     * Method for writing text row.
     * 
     * @param array $text
     * @return void
     */
    public function write_text_row (array $text): void
    {
        $this->writer->writeSheetRow("Sheet1", $text);
    }

    /**
     * Method for writing self format row. This class will decide whether
     * an input is a number, string, or date based on its pattern.
     * 
     * @param array $text
     * @return void
     */
    public function write_self_format_row (array $text): void
    {
        $this->writer->writeSelfFormatSheetRow("Sheet1", $text);
    }

    /**
     * Method for write table body. You can provide 2D array as a paramter, then
     * this class will looping through your array and takes it value.
     * 
     * @param array $text
     * @return void
     */
    public function write_table_body (array $text): void
    {
        for ($i = 0; $i < count($text); $i++) {
            $this->write_self_format_row(array_values((array) $text[$i]));
        }
    }

    /**
     * Method for clearing buffer object
     * 
     * @return void
     */
    public function clear_buffer (): void
    {
        ob_clean();
        flush();
    }

    /**
     * Method for stream the object
     * 
     * @return void
     */
    public function stream (string $file_name = "document.xlsx"): void
    {
        $this->writer->writeToFile($file_name);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        readfile($file_name);
        unlink($file_name);
    }
}