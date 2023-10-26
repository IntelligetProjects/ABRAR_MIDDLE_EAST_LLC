<?php

class Stock_adjustments_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'stock_adjustments';
        parent::__construct($this->table);
    }

}
