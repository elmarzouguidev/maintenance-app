<?php


namespace App\Domain\Support\SaveModel;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Exception\FieldDoesNotExistException;
use App\Domain\Support\SaveModel\Exception\ModelDoesNotImplementInterface;
use Illuminate\Database\Eloquent\Model;

class SaveModel
{

    private $model;

    private $data;

    public function __construct(Model $model, array $data)
    {
        $this->model = $model;

        $this->data = $data;

        $className = get_class($this->model);

        $CanBeSavedInterface = CanBeSavedInterface::class;

        if(!($model instanceof CanBeSavedInterface))
        {
          throw new ModelDoesNotImplementInterface("The {$className} must implement {$CanBeSavedInterface}");
        }

        foreach ($data as $column => $value) {

            if(!$this->saveableFieldExists($column))
            {

                throw new FieldDoesNotExistException(" the field '{$column}' does not exist on the saveableFields method of '{$className}' ");
            }
        }
    }

    private function saveableFieldExists($column):bool
    {
        return array_key_exists($column,$this->model->saveableFields());
    }

    /**
     * @return Model
     */
    public function execute(): Model
    {

        foreach ($this->data as $column => $value) {

            $this->model->{$column} = $this->saveableField($column)->setValue($value)->execute();

        }

        $this->model->save();

        return $this->model;
    }

    private function saveableField($column) : Field
    {
       return  $this->model->saveableFields()[$column];
    }
}
