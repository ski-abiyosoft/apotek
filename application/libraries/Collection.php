<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Collection
{
    private $data;


    public function __construct(array $array = [])
    {
        $this->data = $array;
    }

    /**
     * Method for initialize collection class
     * 
     * @param array
     * @return object
     */
    public function collect(array $array)
    {
        return new self($array);
    }

    /**
     * Method for filtering collection using given parameter
     * 
     * @param string $column $search_value
     * @return void
     */
    public function where(string $column = NULL, string $operator = NULL, $search_value)
    {
        $this->result = array_values(array_filter($this->data, function ($item) use ($search_value, $column, $operator) {
            if ($column) {
                if (gettype($item) == 'array') {
                    switch ($operator) {
                        case '=':
                        case '==':
                            return $item[$column] == $search_value;
                            break;
                        case '===':
                            return $item[$column] === $search_value;
                            break;
                        case '!=':
                            return $item[$column] != $search_value;
                            break;
                        case '!==':
                            return $item[$column] !== $search_value;
                            break;
                        case '>':
                            return $item[$column] > $search_value;
                            break;
                        case '>=':
                            return $item[$column] >= $search_value;
                            break;
                        case '<':
                            return $item[$column] < $search_value;
                            break;
                        case '<=':
                            return $item[$column] <= $search_value;
                            break;
                    }
                } else {
                    switch ($operator) {
                        case '=':
                        case '==':
                            return $item->$column == $search_value;
                            break;
                        case '===':
                            return $item->$column === $search_value;
                            break;
                        case '!=':
                            return $item->$column != $search_value;
                            break;
                        case '!==':
                            return $item->$column !== $search_value;
                            break;
                        case '>':
                            return $item->$column > $search_value;
                            break;
                        case '>=':
                            return $item->$column >= $search_value;
                            break;
                        case '<':
                            return $item->$column < $search_value;
                            break;
                        case '<=':
                            return $item->$column <= $search_value;
                            break;
                    }
                }
            }

            switch ($operator) {
                case '=':
                case '==':
                    return $item == $search_value;
                    break;
                case '===':
                    return $item === $search_value;
                    break;
                case '!=':
                    return $item != $search_value;
                    break;
                case '!==':
                    return $item !== $search_value;
                    break;
                case '>':
                    return $item > $search_value;
                    break;
                case '>=':
                    return $item >= $search_value;
                    break;
                case '<':
                    return $item < $search_value;
                    break;
                case '<=':
                    return $item <= $search_value;
                    break;
            }
        }));
        return $this;
    }

    /**
     * Method for filtering collection using multiple search value
     * 
     * @param string $column
     * @param array $search_values
     * @return self
     */
    public function where_in(string $column = '', array $search_values): self
    {
        $this->result = [];

        foreach ($search_values as $value) {
            array_push($this->result, ...array_values(array_filter($this->data, function ($item) use ($value, $column) {
                if ($column) {
                    if (gettype($item) == 'array') {
                        return $item[$column] == $value;
                    } else {
                        return $item->$column == $value;
                    }
                }

                return $item == $value;
            })));
        }

        return $this;
    }

    /**
     * Method for filetering collection from where result
     * 
     * @param string
     * @return self
     */
    public function and(string $column = null, string $operator = NULL, $search_value)
    {
        if ($this->result) {
            $this->result = array_values(array_filter($this->result, function ($item) use ($search_value, $column, $operator) {
                if ($column) {
                    if (gettype($item) == 'array') {
                        switch ($operator) {
                            case '=':
                            case '==':
                                return $item[$column] == $search_value;
                                break;
                            case '===':
                                return $item[$column] === $search_value;
                                break;
                            case '!=':
                                return $item[$column] != $search_value;
                                break;
                            case '!==':
                                return $item[$column] !== $search_value;
                                break;
                            case '>':
                                return $item[$column] > $search_value;
                                break;
                            case '>=':
                                return $item[$column] >= $search_value;
                                break;
                            case '<':
                                return $item[$column] < $search_value;
                                break;
                            case '<=':
                                return $item[$column] <= $search_value;
                                break;
                        }
                    } else {
                        switch ($operator) {
                            case '=':
                            case '==':
                                return $item->$column == $search_value;
                                break;
                            case '===':
                                return $item->$column === $search_value;
                                break;
                            case '!=':
                                return $item->$column != $search_value;
                                break;
                            case '!==':
                                return $item->$column !== $search_value;
                                break;
                            case '>':
                                return $item->$column > $search_value;
                                break;
                            case '>=':
                                return $item->$column >= $search_value;
                                break;
                            case '<':
                                return $item->$column < $search_value;
                                break;
                            case '<=':
                                return $item->$column <= $search_value;
                                break;
                        }
                    }
                }

                switch ($operator) {
                    case '=':
                    case '==':
                        return $item == $search_value;
                        break;
                    case '===':
                        return $item === $search_value;
                        break;
                    case '!=':
                        return $item != $search_value;
                        break;
                    case '!==':
                        return $item !== $search_value;
                        break;
                    case '>':
                        return $item > $search_value;
                        break;
                    case '>=':
                        return $item >= $search_value;
                        break;
                    case '<':
                        return $item < $search_value;
                        break;
                    case '<=':
                        return $item <= $search_value;
                        break;
                }
            }));
            return $this;
        }
    }

    /**
     * Method for sorting collection
     * 
     * @param string $column, $order
     */
    public function order (string $order = "asc", string $column = ''): self
    {
        // Handle if the collection does not have column to sort
        if ($column == "") {
            // Handle if order is descending
            if ($order == 'desc') {
                // If result is set
                if (isset($this->result)) {
                    arsort($this->result);
                    $this->result = array_values($this->result);
    
                    return $this;
                }
    
                // If result is not set
                arsort($this->data);
                $this->result = array_values($this->data);
    
                return $this;
            } 

            // Handle if order is ascending

            // If result is set
            if (isset($this->result)) {
                asort($this->result);
                $this->result = array_values($this->result);

                return $this;
            }

            // If result is not set
            asort($this->data);
            $this->result = $this->data;
            $this->result = array_values($this->data);

            return $this;
        }

        // Handle if the collection has column to sort

        // Handle if order is descending
        if ($order == "desc") {
            // If result is set
            if (isset($this->result)) {
                usort($this->result, function ($item1, $item2) use ($column) {
                    if (is_array($item1)) {
                        if ($item1[$column] == $item2[$column]) {
                            return 0;
                        }
                        return ($item1[$column] < $item2[$column]) ? 1 : -1;
                    }
    
                    if ($item1->$column == $item2->$column) {
                        return 0;
                    }
                    return ($item1->$column < $item2->$column) ? 1 : -1;
                });
    
                $this->result = array_values($this->result);
    
                return $this;
            }

            // If result is not set
            usort($this->data, function ($item1, $item2) use ($column) {
                if (is_array($item1)) {
                    if ($item1[$column] == $item2[$column]) {
                        return 0;
                    }
                    return ($item1[$column] < $item2[$column]) ? 1 : -1;
                }

                if ($item1->$column == $item2->$column) {
                    return 0;
                }
                return ($item1->$column < $item2->$column) ? 1 : -1;
            });

            $this->result = array_values($this->data);

            return $this;
        }

        // Handle if order is ascending

        // If result is set
        if (isset($this->result)) {
            usort($this->result, function ($item1, $item2) use ($column) {
                if (is_array($item1)) {
                    if ($item1[$column] == $item2[$column]) {
                        return 0;
                    }
                    return ($item1[$column] < $item2[$column]) ? -1 : 1;
                }

                if ($item1->$column == $item2->$column) {
                    return 0;
                }
                return ($item1->$column < $item2->$column) ? -1 : 1;
            });

            $this->result = array_values($this->result);

            return $this;
        }

        // If result is not set
        usort($this->data, function ($item1, $item2) use ($column) {
            if (is_array($item1)) {
                if ($item1[$column] == $item2[$column]) {
                    return 0;
                }
                return ($item1[$column] < $item2[$column]) ? -1 : 1;
            }

            if ($item1->$column == $item2->$column) {
                return 0;
            }
            return ($item1->$column < $item2->$column) ? -1 : 1;
        });

        $this->result = array_values($this->data);

        return $this;
    }

    /**
     * Method for get the result data
     * 
     * @return array
     */
    public function get(): array
    {
        if (count($this->result) > 0) return $this->result;
        return [];
    }

    /**
     * Method for get the first of result data
     * 
     * @return mixed
     */
    public function first()
    {
        if (count($this->result) > 0) return $this->result[0];
        return null;
    }

    /**
     * Method for get all the data
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->data ?? [];
    }

    /**
     * Method untuk menjumlahkan seluruh anggota
     * 
     * @param string $column
     * @return float
     */
    public function sum(string $column = ""): float
    {
        if (isset($this->result)) {
            if ($column != "") {
                $result = array_reduce($this->result, function ($carry, $item) use ($column) {
                    if (is_array($item)) {
                        return $carry = bcadd(floatval($item[$column]), $carry, 2);
                    }

                    return $carry = bcadd(floatval($item->$column), $carry, 2);
                });

                return floatval($result);
            }

            $result = array_reduce($this->result, function ($carry, $item) {
                return $carry = bcadd(floatval($item), $carry, 2);
            });

            return $result;
        }

        if ($column != "") {
            $result = array_reduce($this->data, function ($carry, $item) use ($column) {
                return $carry = bcadd(floatval($item->$column), $carry, 2);
            });

            return floatval($result);
        }

        $result = array_reduce($this->data, function ($carry, $item) {
            return $carry = bcadd(floatval($item), $carry, 2);
        });

        return $result;
    }

    /**
     * Method for checking if all item are met the given condition. Return true, if
     * all item are met the given condition, otherwise false.
     * 
     * @param string
     * @return bool
     */
    public function every(string $column = NULL, string $operator = NULL, string $condition = NULL)
    {
        $result = true;

        foreach ($this->data as $item) {
            if ($column) {
                if (gettype($item) == 'array') {
                    switch ($operator) {
                        case '=':
                        case '==':
                            $item[$column] == $condition ? $result = true : $result = false;
                            break;
                        case '===':
                            $item[$column] === $condition ? $result = true : $result = false;
                            break;
                        case '!=':
                            $item[$column] != $condition ? $result = true : $result = false;
                            break;
                        case '!==':
                            $item[$column] !== $condition ? $result = true : $result = false;
                            break;
                        case '>':
                            $item[$column] > $condition ? $result = true : $result = false;
                            break;
                        case '>=':
                            $item[$column] >= $condition ? $result = true : $result = false;
                            break;
                        case '<':
                            $item[$column] < $condition ? $result = true : $result = false;
                            break;
                        case '<=':
                            $item[$column] <= $condition ? $result = true : $result = false;
                            break;
                    }
                } else {
                    switch ($operator) {
                        case '=':
                        case '==':
                            $item->$column == $condition ? $result = true : $result = false;
                            break;
                        case '===':
                            $item->$column === $condition ? $result = true : $result = false;
                            break;
                        case '!=':
                            $item->$column != $condition ? $result = true : $result = false;
                            break;
                        case '!==':
                            $item->$column !== $condition ? $result = true : $result = false;
                            break;
                        case '>':
                            $item->$column > $condition ? $result = true : $result = false;
                            break;
                        case '>=':
                            $item->$column >= $condition ? $result = true : $result = false;
                            break;
                        case '<':
                            $item->$column < $condition ? $result = true : $result = false;
                            break;
                        case '<=':
                            $item->$column <= $condition ? $result = true : $result = false;
                            break;
                    }
                }

                if ($result == false) return $result;
            }

            switch ($operator) {
                case '=':
                case '==':
                    return $item == $condition;
                    break;
                case '===':
                    return $item === $condition;
                    break;
                case '!=':
                    return $item != $condition;
                    break;
                case '!==':
                    return $item !== $condition;
                    break;
                case '>':
                    return $item > $condition;
                    break;
                case '>=':
                    return $item >= $condition;
                    break;
                case '<':
                    return $item < $condition;
                    break;
                case '<=':
                    return $item <= $condition;
                    break;
            }

            if ($result == false) return $result;
        }

        return $result;
    }
}