<?php
/**
 * Base class for this library repository
 * 
 * Author: tripletTrouble (DPS) https://github.com/triplettrouble
 */

<<<<<<< HEAD
 class Repository
 {
=======
class Repository
{
>>>>>>> development
    private $_CI;
    private $db;
    
    /**
<<<<<<< HEAD
     * Constructor method, here we load all CI core system such as, database, or something
=======
     * Constructor method, here we load all CI core system such as, database, or something.
>>>>>>> development
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
<<<<<<< HEAD
     * your table has id column
     * 
     * @param stdClass $data_set
     * @return object
     */
    public function save(stdClass $data_set)
=======
     * your table has id column.
     * 
     * @param stdClass $data_set
     * @return bool
     */
    public function save(stdClass $data_set): bool
>>>>>>> development
    {
        // If the $data_set has id property
        if (property_exists($data_set, 'id')){
            $id = $data_set->id;
            unset($data_set->id);

            // Update database
            return $this->db->update($this->table, $data_set, ['id' => $id]);
        }

        // Otherwise, insert into table
        return $this->db->insert($this->table, $data_set);
    }

    /**
<<<<<<< HEAD
     * Method for updating data in the table, this method is created for handling table that 
     * its primary key is not ID
     * 
     * @param stdClass $data_set
     * @param array $where_clause
     * @return bool
     */
    public function update(stdClass $data_set, array $where_clause): bool
    {
        return $this->db->update($this->table, $data_set, $where_clause);
    }

    /**
     * Method for handling save or update based on given data
     * 
     * @param stdClass $data_set
     * @param array $unique_column
     */
    public function save_or_update (stdClass $data_set, array $unique_column)
    {
        $where_clause = [];
        $column_str   = implode(",", $unique_column);

        foreach ($unique_column as $value) {
            $where_clause[$value] = $data_set->$value;
        }

        $is_exists = $this->db->select($column_str)->where($where_clause)->get($this->table)->row();

        if ($is_exists) {
            return $this->update($data_set, $where_clause);
        }

        return $this->save($data_set);
    }

    /**
     * Method for getting all data from database
=======
     * Method for deleting records from database.
     * 
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->db->delete($this->table, ["id" => $id]);
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
>>>>>>> development
     * 
     * @param int $limit, $offset
     * @return array
     */
<<<<<<< HEAD
    public function findAll($limit = NULL, $offset = NULL)
=======
    public function findAll($limit = NULL, $offset = NULL): array
>>>>>>> development
    {
        return $this->db->get($this->table, $limit, $offset)->result();
    }

    /**
<<<<<<< HEAD
     * Method for getting record from table using its id
     * 
     * @param int $id
     * @return mixed stdClass || null
     */
    public function find(int $id)
=======
     * Method for getting record from table using its id.
     * 
     * @param int $id
     * @return stdClass
     */
    public function find(int $id): stdClass
>>>>>>> development
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    /**
<<<<<<< HEAD
     * Method for starting select query
=======
     * Method for starting select query.
>>>>>>> development
     * 
     * @param string $select_query
     * @return db
     */
    public function select(string $select_query = '*')
    {
        return $this->db->select($select_query)->from($this->table);
    }

    /**
<<<<<<< HEAD
     * Method for getting list of table fields
     * 
     */
    public function get_fields ()
    {
        return $this->db->list_fields($this->table);
    }

    /**
=======
>>>>>>> development
     * Method for processing data table request
     * 
     * @return array
     */
<<<<<<< HEAD
    public function create_data_table(stdClass $request, array $where_clause = []): array
    {
        $sEcho = $request->sEcho;
        $columns = $request->columns;
=======
    public function create_data_table( stdClass $request, array $where_clause = []): array
    {
        $sEcho = $request->sEcho;
        $columns = ["depid", "namadep", "id"]; 
>>>>>>> development
        $limit = intval($request->iDisplayLength); 
        $offset = intval($request->iDisplayStart); 
        $search_value = $request->sSearch; 
        $order_column = $request->iSortCol_0; 
<<<<<<< HEAD
        $order_type = $request->sSortDir_0;

=======
        $order_type = $request->sSortDir_0; 
        
>>>>>>> development
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
<<<<<<< HEAD
 }
=======
}
>>>>>>> development
