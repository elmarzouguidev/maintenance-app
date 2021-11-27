<?php


namespace App\Domain\Support\SaveModel;


abstract class Field
{


   protected $value;

    /*public function __construct($value)
   {
       $this->value = $value;
   }*/

    public function setValue($value): Field
    {
        $this->value = $value;

        return $this;
    }

    public static function new(): Field
    {
        return new static;
    }

    abstract public  function execute();

}
