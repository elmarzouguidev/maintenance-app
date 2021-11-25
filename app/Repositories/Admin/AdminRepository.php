<?php


namespace App\Repositories\Admin;


use App\Models\Authentification\Admin;

class AdminRepository implements AdminInterface
{


    private $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
      return $this->admin->all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getAdmin(int $id)
    {
        return $this->admin->find($id);
    }
}
