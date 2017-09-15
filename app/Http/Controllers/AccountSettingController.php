<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountSetting;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{

    /**
     * Save backup settings.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Account $account)
    {
        $settings = $this->requestedSettings();
        $this->updateOrCreateSettings($account, $settings);

        flash('Settings has been updated.', 'success');

        return redirect("/accounts/{$account->id}/settings");
    }

    /**
     * Restore storage default settings.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreStorage(Account $account)
    {
        $settings = [
            ['label' => 'storage_limit', 'value' => 1024],
            ['label' => 'storage_listable', 'value' => 0],
        ];
        $this->updateOrCreateSettings($account, $settings);

        flash('Storage settings has been restored to default.', 'success');

        return redirect("/accounts/{$account->id}/settings");
    }

    /**
     * Restore backup default settings.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function restoreBackup(Account $account)
    {
        $settings = [
            ['label' => 'backup_database_limit', 'value' => 7]
        ];
        $this->updateOrCreateSettings($account, $settings);

        flash('Backup settings has been restored to default.', 'success');

        return redirect("/accounts/{$account->id}/settings");
    }

    /**
     * Get settings from request.
     *
     * @return array
     */
    private function requestedSettings()
    {
        $settings = app('request')->input('settings');

        return array_map(function ($label, $value) {
            return [
                'label' => $label,
                'value' => $value,
            ];
        }, array_keys($settings), $settings);
    }

    /**
     * Update or create requested settings in the account.
     *
     * @param Account $account
     * @param $settings
     */
    private function updateOrCreateSettings(Account $account, $settings)
    {
        array_walk($settings, function ($setting) use ($account) {
            $setting = (object) $setting;
            AccountSetting::updateOrCreate([
                'account_id' => $account->id,
                'label'      => $setting->label,
            ], ['value' => $setting->value]);
        });
    }
}
