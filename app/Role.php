<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    /**
     * The attributes tha can be assign.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'default'];
}
