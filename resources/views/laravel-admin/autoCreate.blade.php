<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="{{ route('admin.autoCreate') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-twitter csv-import"><i class="fa fa-upload"></i><span class="hidden-xs"> Q手を発行する</span></button>
    </form>
</div>