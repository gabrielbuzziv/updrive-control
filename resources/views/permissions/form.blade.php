<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $permission ? $permission->name : '' }}">
</div>

<div class="form-group">
    <label>Display Name</label>
    <input type="text" name="display_name" class="form-control" value="{{ $permission ? $permission->display_name : '' }}">
</div>

<button type="submit" class="btn btn-success">Save</button>