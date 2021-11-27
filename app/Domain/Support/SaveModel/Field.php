<?php


namespace App\Domain\Support\SaveModel;


use Illuminate\Database\Eloquent\Model;

abstract class Field
{


    protected $value;

    protected $column;

    protected $model;

    abstract public  function execute();

    public function setValue($value): Field
    {
        $this->value = $value;

        return $this;
    }

    public static function new(): Field
    {
        return new static;
    }


    public function onColumn(string $column): Field
    {
         $this->column = $column;

         return $this;
    }

    public function ofModel(Model $model): Field
    {
        $this->model = $model;

        return $this;
    }

    public function isUpdate():bool
    {
      return $this->model->exists;
    }


}
