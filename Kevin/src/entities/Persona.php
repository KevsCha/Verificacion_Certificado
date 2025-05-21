<?php
class Persona
{
    private $id;
    private $name;
    private $last_name;

    public function __construct($id, $name, $last_name){
        $this->id = $id;
        $this->name = $name;
        $this->last_name = $last_name;
    }
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getLastName(){
        return $this->last_name;
    }
    public function setLastName($last_name){
        $this->last_name = $last_name;
    }
    public function __toString(){
        return "ID: " . $this->id . ", Name: " . $this->name . ", Last Name: " . $this->last_name;
    }
}
