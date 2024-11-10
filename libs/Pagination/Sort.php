<?php
class Sort implements JsonSerializable{
    // El campo por el cual se está ordenando
    private $sortedField;
    // El orden que se aplicará (ascendiente o descendiente)
    private $order;

    function __construct(){
    }

    public function getSortedField() {
        return $this->sortedField;
    }

    public function setSortedField($sortedField) {
        $this->sortedField = $sortedField;
    }

    public function getOrder() {
        return $this->order;
    }

    public function setOrder($order) {
        $this->order = $order;
    }
    // Esta función es necesaria para que se pueda convertir el objeto en json, debido a que los campos son privados
    public function jsonSerialize():mixed  {
        return [
            'field' => $this->sortedField,
            'order' => $this->order
        ];
    }
}