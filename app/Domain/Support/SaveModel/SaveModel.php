<?php


namespace App\Domain\Support\SaveModel;

use Illuminate\Database\Eloquent\Model;

class SaveModel
{

    private $model;

    private $data;

    private $folder;

    /**
     * SaveModel constructor.
     * @param Model $model
     * @param array $data
     * @param string $folder
     */
    public function __construct(Model $model, array $data, string $folder = 'images')
    {
        $this->model = $model;

        $this->data = $data;

        $this->folder = $folder;
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
