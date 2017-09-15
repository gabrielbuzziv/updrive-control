<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $module ? $module->name : '' }}">
</div>

<div class="form-group">
    <label>Display Name</label>
    <input type="text" name="display_name" class="form-control" value="{{ $module ? $module->display_name : '' }}">
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" rows="4" class="form-control">{{ $module ? $module->description : '' }}</textarea>
</div>

<div class="form-group">
    <label>Requirements</label>
    <select name="requirements[]" class="form-control" multiple>
        @foreach ($requirements as $requirement)
            <option value="{{ $requirement->id }}" {{ $module && in_array($requirement->id, $module->requirements->pluck('id')->all()) ? 'selected' : '' }}>{{ $requirement->name }}</option>
        @endforeach
    </select>
    <small class="helper-block">The modules that need to be active in the account to active this one.</small>
</div>

<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>

<button type="submit" class="btn btn-success">Save</button>