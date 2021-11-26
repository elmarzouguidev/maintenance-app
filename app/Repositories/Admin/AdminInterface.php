<?php


namespace App\Repositories\Admin;


use Illuminate\Database\Eloquent\Model;

interface AdminInterface
{


    public function getAdmins();

    public function getAdmin(int $id);

    public function addAdmin(array $data);

    public function getFirst();
}
