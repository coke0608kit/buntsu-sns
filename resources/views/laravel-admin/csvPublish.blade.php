<div class="btn-group pull-right" style="margin-right: 10px">
    <form action="{{ route('admin.labelPublish') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-danger csv-export"><i class="fa fa-upload"></i><span class="hidden-xs"> {{ date('Y') }}年{{ date('n') }}月 secondの宛名CSVを発行</span></button>
    </form>
</div>