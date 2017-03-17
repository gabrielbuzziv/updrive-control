<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    /**
     * The attributes tha can be assign.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name'];
}
