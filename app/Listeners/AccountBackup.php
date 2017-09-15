<?php

namespace App\Listeners;

use App\Events\DatabaseBackupDone;
use Carbon\Carbon;
use App\Events\BackupDatabaseBegin;
use App\Events\AccountBackupRequested;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountBackup implements ShouldQueue
{

    /**
     * Account instance.
     *
     * @var
     */
    protected $account;

    /**
     * Account database connection.
     *
     * @var
     */
    protected $connection;

    /**
     * The database host.
     *
     * @var mixed
     */
    protected $host;

    /**
     * The database port.
     *
     * @var mixed
     */
    protected $port;

    /**
     * The database username.
     *
     * @var mixed
     */
    protected $username;

    /**
     * The database password.
     *
     * @var mixed
     */
    protected $password;

    /**
     * The database name.
     *
     * @var
     */
    protected $database;

    /**
     * The backup filename;
     *
     * @var
     */
    protected $filename;

    /**
     * The backup filename.
     *
     * @var
     */
    protected $path;

    /**
     * AccountBackup constructor.
     */
    public function __construct()
    {
        $this->host = env('DB_HOST');
        $this->port = env('DB_PORT');
        $this->username = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');
    }

    /**
     * Handle the event.
     *
     * @param  AccountBackupRequested $event
     * @return void
     */
    public function handle(AccountBackupRequested $event)
    {
        $this->account = $event->account;
        $this->database = $this->account->slug;
        $this->filename = $event->backup->filename;
        $this->path = storage_path("app/backup/{$this->filename}");
        $this->dumpDatabase();
        $this->storageBackup($this->filename);

        $event->backup->update([
            'filesize' => filesize($this->path),
            'done_at'  => Carbon::now(),
        ]);
        
        event(new DatabaseBackupDone($this->account, $event->backup));
        \File::delete($this->path);
    }

    /**
     * Connect to the brand new database.
     */
    private function connect()
    {
        config()->set('database.connections.current_account', [
            'driver'    => 'mysql',
            'host'      => $this->host,
            'port'      => $this->port,
            'database'  => $this->database,
            'username'  => $this->username,
            'password'  => $this->password,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ]);

        $this->connection = DB::reconnect('current_account');
    }

    /**
     * Mysql Dump database.
     *
     * @return string
     */
    private function dumpDatabase()
    {
        return @exec(
            sprintf('mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
                escapeshellarg($this->host),
                escapeshellarg($this->port),
                escapeshellarg($this->username),
                escapeshellarg($this->password),
                escapeshellarg($this->database),
                escapeshellarg($this->path)
            )
        );
    }

    /**
     * Store backup in S3 storage.
     */
    private function storageBackup()
    {
        Storage::disk('s3')->put("{$this->account->slug}/database/{$this->filename}", file_get_contents($this->path));
    }
}
