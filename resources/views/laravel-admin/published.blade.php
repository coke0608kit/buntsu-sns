<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="{{ route('admin.published') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger csv-import"><i class="fa fa-upload"></i><span class="hidden-xs"> Q手を発行済みにする</span></button>
    </form>
</div>