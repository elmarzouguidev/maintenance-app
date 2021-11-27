<?php


namespace App\Domain\Support\SaveModel\Contract;


interface CanBeSavedInterface
{

    public function saveableFields(): array ;
}
