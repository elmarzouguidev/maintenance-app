<?php


namespace App\Repositories\Admin;


use App\Models\Authentification\Admin;

class AdminRepository implements AdminInterface
{


    private $admin;

    private $instance;

    private $options;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;

        $this->options   = config('app-config');
    }

    public function __instance(): Admin
    {
        if (!$this->instance) {
            $this->instance = $this->admin;
        }

        return $this->instance;
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
        if($this->options ){

            dd($this->options);
        }
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

    /**
     * @param array $data
     * @return mixed
     */
    public function addAdmin(array $data)
    {
        return $this->admin->create($data);
    }
}
