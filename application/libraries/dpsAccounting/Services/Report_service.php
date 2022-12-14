<?php 

require_once(APPPATH."libraries/dpsAccounting/Services/FPDF_extended.php");

/**
 * This is base class for reporting purpose, this library are created 
 * in top of FPDF library. You can extend this class if you want.
 * 
 * Author: tripletTrouble (DPS) https://github.com/triplettrouble
 */

class Report_service extends FPDF_extended
{
    protected $working_area;
    protected $unit_name;
    protected $logo;
    protected $address;
    protected $address2;
    protected $phone;
    protected $whatsapp;
    protected $tax_id;

    public function __construct(array $params)
    {
        parent::__construct($params['orientation'] ?? "P", $params['unit'] ?? "mm", $params['size'] ?? "A4");

        // Set the document header
        if (key_exists("unit_info", $params)){
            $this->unit_name = $params['unit_info']->namars;
            $this->logo = base_url("assets/img/")."{$params['unit_info']->logo}";
            $this->address = $params['unit_info']->alamat;
            $this->address2 = $params['unit_info']->alamat2;
            $this->phone = $params['unit_info']->phone;
            $this->whatsapp = $params['unit_info']->whatsapp;
            $this->tax_id = $params['unit_info']->npwp;
        }
        
        $this->working_area = $this->w - ($this->lMargin + $this->rMargin);
    }

    public function header(): void
    {
        // Logo
        $this->Image($this->logo ?? base_url("assets/img/logo.png"), 10, 8, 25);
        
        $this->SetFontSize(8);

        // Title
        // Move to the right
        $this->bold();
        $this->Cell(25);
        $this->Cell(120, 5, $this->unit_name ?? 'RSU Putri Bidadari', 0, 0, 'L');
        $this->Ln(4);

        // Move to the right
        $this->normal();
        $this->Cell(25);
        $this->Cell(120, 5, $this->address ?? 'Jalan Magelang No. 188, Karangwaru', 0, 0, 'L');
        $this->Ln(4);

        // Move to the right
        $this->Cell(25);
        $this->Cell(120, 5, $this->address2 ?? 'Kec. Tegalrejo, Kota Yogyakarta, DIY, 55223', 0, 0, 'L');
        $this->Ln(4);

        // Move to the right
        $this->Cell(25);
        $this->Cell(120, 5, 'Telp. ' . ($this->phone ?? '-') . ' WA: ' . ($this->whatsapp), 0, 0, 'L');
        $this->Ln(4);

        // Move to the right
        $this->Cell(25);
        $this->Cell(120, 5, 'NPWP: ' . ($this->tax_id ?? '-'), 0, 0, 'L');
        
        // Line break
        $this->SetLineWidth(0.5);
        $this->Line(10, 32, $this->working_area+10, 32);
        $this->Ln(12);
    }

    /**
     * Method for setting the font to bold.
     * 
     * @return void
     */
    public function bold (): void
    {
        $this->SetFont("", "B");
    }

    /**
     * Method for setting the font to italic.
     * 
     * @return void
     */
    public function italic (): void
    {
        $this->SetFont("", "I");
    }

    /**
     * Method for setting the font to bold italic.
     * 
     * @return void
     */
    public function bold_italic (): void
    {
        $this->SetFont("", "BI");
    }

    /**
     * Method for setting the font to underline.
     * 
     * @return void
     */
    public function underline (): void
    {
        $this->SetFont("", "U");
    }

    /**
     * Method for setting the font to bold underline.
     * 
     * @return void
     */
    public function bold_underline (): void
    {
        $this->SetFont("", "BU");
    }

    /**
     * Method for setting the font to italic underline.
     * 
     * @return void
     */
    public function italic_underline (): void
    {
        $this->SetFont("", "IU");
    }

    /**
     * Method for setting the font to normal.
     * 
     * @return void
     */
    public function normal (): void
    {
        $this->SetFont("", "");
    }

    /**
     * Methdo for creating document title. Document title are only showed in the first page.
     * It is decribe the content of the whole document.
     * 
     * @param string $text
     * @return void
     */
    public function write_document_title (string $text): void
    {
        $this->SetFont("", "B", 13);

        //Write the label
        $this->Cell($this->working_area, 8, $text, 0, 0, "C");
        $this->Ln(8);
    }

    /**
     * Method for creating document subtitle. Document subtitle are the more specific description
     * of the whole document. Subtitle are written in smaller font than its title.
     * 
     * @param string $text
     * @return void
     */
    public function write_document_subtitle (string $text): void
    {
        $this->SetFont("", "", 11);

        //Write the label
        $this->Cell($this->working_area, 8, $text, 0, 0, "C");
        $this->Ln(8);
    }

    /**
     * Method for writing table label, sometimes we need it for displaying multiple table
     * in the same document.
     * 
     * @param string $text
     * @return void
     */
    public function write_label (string $text): void
    {
        // We use lime as default background label
        $this->SetFillColor(152, 252, 0);
        $this->SetFont("", "B", 10);

        //Write the label
        $this->Cell($this->working_area, 8, $text, 0, 0, "L", true);
        $this->Ln(8);

        // Set back the fill color
        $this->SetFillColor(255);
    }

    /**
     * Method for calculating cell width based on given fraction. This class
     * will decide basen on the total fraction and thw working space, so our 
     * table will fit on the working space.
     * 
     * @param array $fraction
     * @return void
     */
    public function auto_width (array $fraction): void
    {
        $total = array_reduce($fraction, function ($carry, $item) {
            return $carry += $item;
        });

        $widths = [];

        foreach ($fraction as $key => $value) {
            $widths[$key] = ($value/$total * $this->working_area);
        }

        $this->SetWidths($widths);
    }

    /**
     * Method for creating table header, please ensure that you have set
     * the cell width. You can set with using autho_width function or
     * base SetWidths method. You can set differen color cell on 
     * header by using setFillColor method.
     * 
     * @param array $text
     */
    public function write_table_header (array $text): void
    {
        $this->bold();
        $this->Row($text, true);
    }

    /**
     * Method for creating table body. Once you have set the width you
     * can also write the table body by passing 2D array on its 
     * parameter, then this class will take its value. Please
     * ensure that the length of parameter you pass and 
     * width that you set are equal.
     * 
     * @param array $text
     * @return void
     */
    public function write_table_body (array $text): void
    {
        for ($i = 0; $i < count($text); $i++) {
            $this->Row(array_values((array) $text[$i]));
        }
    }
}