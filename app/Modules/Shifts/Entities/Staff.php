<?php

namespace App\Modules\Shifts\Entities;

class Staff
{
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
