<?php
class Filter implements JsonSerializable{
    // El filtro que se aplicará
    private $filter;
    // El campo al que se aplicará el filtro
    private $field;

    function __construct(){
    }

    public function getFilter() {
        return $this->filter;
    }

    public function setFilter($filter) {
        $this->filter = $filter;
    }

    public function getField() {
        return $this->field;
    }

    public function setField($field) {
        $this->field = $field;
    }
    // Esta función es necesaria para que se pueda convertir el objeto en json, debido a que los campos son privados
    public function jsonSerialize():mixed {
        return [
            'filter' => $this->filter,
            'field' => $this->field
        ];
    }
}