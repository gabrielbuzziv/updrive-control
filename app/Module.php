<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'status'];

    /**
     * A module can have many accounts.
     *
     * @return mixed
     */
    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    /**
     * A module belongs to many requirements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requirements()
    {
        return $this->belongsToMany(Module::class, 'module_requirements', 'module_id', 'requirement_id');
    }
}
