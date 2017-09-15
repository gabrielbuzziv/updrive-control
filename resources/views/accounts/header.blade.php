<div class="pull-right margin-top-10">
    <a href="/accounts/{{ $account->id }}">Voltar</a>

    <div class="dropdown inline-block margin-left-10">
        <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="mdi mdi-settings margin-right-5"></i>
            <i class="mdi mdi-chevron-down"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="/accounts/{{ $account->id }}/edit">Edit</a></li>
            <li><a href="/accounts/{{ $account->id }}/backup">Backup</a></li>
            <li><a href="/accounts/{{ $account->id }}/settings">Settings</a></li>
        </ul>
    </div>
</div>
<h1 class="page-title margin-top-0 margin-bottom-30">{{ $account->name }}: {{ $title }}</h1>