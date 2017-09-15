<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AccountBackup extends Model
{

    /**
     * The attributes that can be assign.
     *
     * @var array
     */
    protected $fillable = ['account_id', 'filename', 'filesize', 'type', 'done_at'];

    /**
     * The database table name.
     *
     * @var string
     */
    protected $table = 'account_backup';

    /**
     * The appended attributes.
     *
     * @var array
     */
    protected $appends = ['download_url'];

    /**
     * Format the filezile of backup document.
     *
     * @param $value
     * @return string
     */
    public function getFilesizeAttribute($value)
    {
        if (! empty($value)) {
            $base = log($value, 1024);
            $suffixes = ['', 'Kb', 'Mb', 'Gb', 'Tb'];

            return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffixes[floor($base)];
        }
    }

    /**
     * Format the created at date time.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y \à\s H:i:s');
    }

    /**
     * Get S3 URL Download link.
     *
     * @return mixed
     */
    public function getDownloadUrlAttribute()
    {
        $path = "{$this->account->slug}/database/{$this->filename}";

        $command = Storage::disk('s3')->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
            'Bucket'                     => env('AWS_BUCKET'),
            'Key'                        => $path,
            'ResponseContentType'        => 'application/sql',
            'ResponseContentDisposition' => "attachment; filename:{$this->filename}",
        ]);

        return Storage::disk('s3')->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes')->getUri()->__toString();
    }

    /**
     * A backup belongs to an account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
