<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'email', 'logo', 'active'];

    public function getSettingAttribute()
    {
        return (object) array_reduce($this->settings->toArray(), function ($result, $data) {
            $result[$data['label']] = $data['value'];
            return $result;
        }, []);
    }

    /**
     * A account can have manu modules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules()
    {
        return $this->belongstoMany(Module::class);
    }

    /**
     * A account has many backups.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function backups()
    {
        return $this->hasMany(AccountBackup::class);
    }

    /**
     * A account has many settings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(AccountSetting::class);
    }
}
