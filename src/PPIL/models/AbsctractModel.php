<?php
namespace PPIL\models;

class AbstractModel extends \Illuminate\Database\Eloquent\Model{

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
