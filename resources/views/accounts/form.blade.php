<input type="hidden" name="slug" value="" v-model="slug">

<blockquote>Domain: @{{ slug }}.saas.app</blockquote>

<div class="form-group">
    <label>Nome</label>
    <input type="text" name="name" class="form-control" v-model="name">
</div>

<div class="form-group">
    <label>E-mail</label>
    <input type="email" name="email" class="form-control">
</div>

<div class="form-group">
    <label>Logo</label>
    <input type="file" name="logo" class="form-control">
</div>

@if ($account)
    <div class="form-group">
        <label>Active</label>
        <select name="active">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
@endif

<button type="submit" class="btn btn-success">Save</button>

@push('scripts')
<script>
    new Vue({
        el: '#app',

        data: {
            name: ''
        },

        computed: {
            slug () {
                if (this.name != null && this.name != '') {
                    return this.name.toString().toLowerCase()
                            .replace(/\s+/g, '')           // Replace spaces with -
                            .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                            .replace(/\-\-+/g, '')         // Replace multiple - with single -
                            .replace(/^-+/, '')             // Trim - from start of text
                            .replace(/-+$/, '');
                }

                return 'domain'
            }
        }
    })
</script>
@endpush