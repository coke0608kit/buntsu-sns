<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="{{ route('admin.labelRePublish') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-yellow csv-export"><i class="fa fa-upload"></i><span class="hidden-xs"> {{ date('Y') }}年{{ date('n') }}月 secondの宛名CSVを復活</span></button>
    </form>
</div>