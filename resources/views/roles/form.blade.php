<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $role ? $role->name : '' }}">
</div>

<div class="form-group">
    <label>Display Name</label>
    <input type="text" name="display_name" class="form-control" value="{{ $role ? $role->display_name : '' }}">
</div>

<div class="form-group">
    <label>Permissions</label>
    <select name="permissions[]" class="form-control" multiple>
        @foreach ($permissions as $permission)
            <option value="{{ $permission->id }}" {{ $role && in_array($permission->id, $role->perms->pluck('id')->all()) ? 'selected' : '' }}>{{ $permission->name }}</option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-success">Save</button>