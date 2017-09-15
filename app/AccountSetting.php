<?php

namespace App;

use App\Events\SettingUpdated;
use Illuminate\Database\Eloquent\Model;

class AccountSetting extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['account_id', 'label', 'value'];

    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'account_settings';

    /**
     * Disable timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * A setting belongs to an account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
