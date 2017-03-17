<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $user ? $user->name : '' }}">
</div>

<div class="form-group">
    <label>E-mail</label>
    <input type="text" name="email" class="form-control" value="{{ $user ? $user->email : '' }}">
</div>

<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control">
</div>

<div class="form-group">
    <label>Roles</label>
    <select name="roles[]" class="form-control" multiple>
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $user && in_array($role->id, $user->roles->pluck('id')->all()) ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-success">Save</button>