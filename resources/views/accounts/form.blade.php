@if (! $account)
    <input type="hidden" name="slug" value="" v-model="slug">
    <blockquote>Domain: @{{ slug }}.saas.app</blockquote>
@endif

<div class="form-group">
    <label>Nome</label>
    <input type="text" name="name" class="form-control" v-model="name" value="{{ $account ? $account->name : '' }}">
</div>

<div class="form-group">
    <label>E-mail</label>
    <input type="email" name="email" class="form-control" value="{{ $account ? $account->email : '' }}">
</div>

<div class="form-group">
    <label>Logo</label>
    <input type="file" name="logo" class="form-control">
</div>

<button type="submit" class="btn btn-success margin-top-20">Save</button>

@push('scripts')
<script>
    new Vue({
        el: '#app',

        data: {
            name: '{{ $account ? $account->name : '' }}'
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