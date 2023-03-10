<?php
/**
 * Base class for this library repository
 * 
 * Author: tripletTrouble (DPS) https://github.com/triplettrouble
 */

class Repository
{
    private $_CI;
    private $db;
    
    /**
     * Constructor method, here we load all CI core system such as, database, or something.
     * 
     */
    public function __construct()
    {
        $this->_CI  = &get_instance();

        // Bind CI instance to this class property
        $this->db   = $this->_CI->db;
    }

    /**
     * Method for saving data into database, we use id as primary key, so please sure that
     * your table has id column.
     * 
     * @param stdClass $data_set
     * @return bool
     */
    public function save(stdClass $data_set = null): bool
    {
        if (empty($data_set)) $data_set = $this;

        $primary_key = $this->primary_key ?? 'id';

        // If the $data_set has id property
        if (property_exists($data_set, 'id')){
            $id = $data_set->id;
            unset($data_set->id);

            // Update database
            return $this->db->update($this->table, $data_set, [$primary_key => $id]);
        }

        // Otherwise, insert into table
        return $this->db->insert($this->table, $data_set);
    }

    /**
     * Method for deleting records from database.
     * 
     * @param int $id
     * @return bool
     */
    public function destroy(int $id = null): bool
    {
        return $this->db->delete($this->table, ["id" => ($id ?? $this->id)]);
    }

    /**
     * Method for batch delete from database
     * 
     * @param string $search_value
     * @param string $column
     * @return bool
     */
    public function batch_destroy(string $search_value, string $column): bool
    {
        return $this->db->delete($this->table, [$column => $search_value]);
    }

    /**
     * Method for getting all data from database.
     * 
     * @param int $limit, $offset
     * @return array
     */
    public function findAll($limit = NULL, $offset = NULL): array
    {
        return $this->db->get($this->table, $limit, $offset)->result();
    }

    /**
     * Method for getting record from table using its id.
     * 
     * @param string $primary_value
     * @return object
     */
    public function find(string $primary_value)
    {
        $result = $this->db->where($this->primary_key ?? 'id', $primary_value)->get($this->table)->row();

        foreach ($result as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Method for starting select query.
     * 
     * @param string $select_query
     * @return db
     */
    public function select(string $select_query = '*')
    {
        return $this->db->select($select_query)->from($this->table);
    }

    /**
     * Method for processing data table request
     * 
     * @return array
     */
    public function create_data_table( stdClass $request, array $where_clause = []): array
    {
        $sEcho = $request->sEcho;
        $columns = ["depid", "namadep", "id"]; 
        $limit = intval($request->iDisplayLength); 
        $offset = intval($request->iDisplayStart); 
        $search_value = $request->sSearch; 
        $order_column = $request->iSortCol_0; 
        $order_type = $request->sSortDir_0; 
        
        $columns_str    = implode(',', $columns);
        $total_count = $this->select("COUNT(*) as jumlah");

        if (count($where_clause) > 0) $total_count = $total_count->where($where_clause);

        $total_count = $total_count->get()->row()->jumlah;

        $filtered_count = $this->select("COUNT(*) as jumlah");

        if (count($where_clause) > 0) $filtered_count = $filtered_count->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($columns as $key => $column) {
                if ($key == 0) $where .= "$column like '%{$search_value}%'";
                else $where .= "or $column like '%{$search_value}%'";
            }

            $filtered_count = $filtered_count->where("($where)");
        }

        $filtered_count = $filtered_count->get()->row()->jumlah;
        $filtered_data  = $this->select("$columns_str");

        if (count($where_clause) > 0) $filtered_data = $filtered_data->where($where_clause);

        if ($search_value != '') {
            $where = '';

            foreach ($columns as $key => $column) {
                if ($key == 0) $where .= "$column like '%{$search_value}%'";
                else $where .= "or $column like '%{$search_value}%'";
            }

            $filtered_data = $filtered_data->where("($where)");
        }

        $filtered_data = $filtered_data->limit($limit, $offset)->order_by($columns[$order_column], $order_type)->get()->result();

        return [
            'iTotalRecords' => $total_count,
            'iTotalDisplayRecords' => $filtered_count,
            'sEcho' => intval($sEcho),
            'aaData' => $filtered_data
        ];
    }
}