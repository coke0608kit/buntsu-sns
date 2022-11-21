<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="{{ route('admin.labelPublishedDone') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-twitter csv-import"><i class="fa fa-upload"></i><span class="hidden-xs"> {{ date('Y') }}年{{ date('n') }}月 second 宛名発行完了</button>
    </form>
</div>