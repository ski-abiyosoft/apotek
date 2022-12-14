<?php

require_once(APPPATH."libraries/dpsAccounting/Repositories/CashOutHeaderRepository.php");

class Cash_disbursement_service
{
    private $cash_out_header;
    private $cash_out_detail;
    
    public function __construct ()
    {
        $this->cash_out_header = new CashOutHeaderRepository();
    }

    /**
     * Method for saving data into database, you can provide id in the dataset if you want
     * to perform an update action.
     * 
     * @param stdClass $data_set
     * @return stdClass
     */
    public function save (stdClass $data_set): stdClass
    {
        if (property_exists($data_set, "id")) {
            $result = $this->cash_out_header->find($data_set->id);

            $this->cash_out_header->save($data_set);
            return $result;
        }

        $this->cash_out_header->save($data_set);
        return $data_set;
    }
}