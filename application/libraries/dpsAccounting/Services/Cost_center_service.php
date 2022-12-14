<?php 

require_once(APPPATH."libraries/dpsAccounting/Repositories/CostCenterRepository.php");

class cost_center_service
{
    private $cost_center;

    public function __construct()
    {
        $this->cost_center = new CostCentreRepository();
    }

    /**
    * Method for saving data into table, you can provide id if you want to
    * perform an update action.
    * 
    * @param stdClass $data_set
    * @return std
    **/
    public function save (stdClass $data_set): stdClass
    {
        if (property_exists($data_set, 'id')){
            $result = $this->cost_center->find($data_set->id);
            $this->cost_center->save($data_set);
            return $result;
        }
        $this->cost_center->save($data_set);
        return $data_set;
    }
    /**
     * Method for providing data for jQuery dataTable
     * 
     * @param stdClass $request
     * @param array $where_clause
     * @return array
     */
    public function create_data_table_response(stdClass $request, array $where_clause = []): array
    {
        return $this->cost_center->create_data_table($request, $where_clause);
    }
}