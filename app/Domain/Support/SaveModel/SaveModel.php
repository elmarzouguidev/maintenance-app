<?php


namespace App\Domain\Support\SaveModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

            $fields = $this->model->saveableFields()[$column];

            switch ($fields) {

                case 'datetime':

                    $this->model->{$column} = Carbon::parse($value)->toDateTimeString();

                    break;

                case 'image':

                    $this->model->{$column} = $value->store($this->folder);

                    break;

                default:
                    $this->model->{$column} = $value;
                    break;
            }

        }

        $this->model->save();

        return $this->model;
    }
}
