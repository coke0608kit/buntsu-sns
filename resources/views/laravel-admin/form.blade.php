<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools">
            {!! $form->renderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    {!! $form->open() !!}

    <div class="box-body">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($layout->columns() as $column)
                        <div class="col-md-{{ $column->width() }}">
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>
    <!-- /.box-body -->

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

<!-- /.box-footer -->
    {!! $form->close() !!}
</div>

<p id="reload" style="color:red">リロード！</p>
<button id="btnFetch" type="button" onclick="fetch()" style="display:none;">データ取得</button>
<button id="btnFetchPreview" type="button" onclick="fetch('Preview.bmp')" style="display:none;">データ取得（プレビュー）</button>

<p style="margin-top:10px;">
    <img id='previewArea'></img>
</p>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    
window.onload=function(){
setTimeout("document.getElementById('btnFetch').style.display='block';document.getElementById('reload').style.display='none'",100);
}
    function fetch(strExport)
    {
        const columns = document.querySelectorAll('.form-group');
        const toId = columns[13].getElementsByClassName("box-body")[0].innerText.trim();
        axios.get('/shipmentInfo', {
                params: {
                    userId: toId,
                }
            })
            .then(response => {
                if (response.data.shipment.status > 1) {
                    if (strExport == "Preview.bmp") {
                        //プレビュー
                        DoPrint('label','Preview.bmp')
                    } else {
                        //印刷
                        DoPrint('label','')
                    }
                } else if (response.data.shipment.status == 1) {
                    if (strExport == 'Preview.bmp') {
                        //プレビュー
                        DoPrint('NamePlate3','Preview.bmp')
                    } else {
                        //印刷
                        DoPrint('NamePlate3','')
                    }
                } else if (response.data.shipment.status == 'none') {
                    if (strExport == 'Preview.bmp') {
                        //印刷
                        DoLabelPrint('Preview.bmp')
                    } else {
                        //プレビュー
                        DoLabelPrint('')
                    }
                }
            })
            .catch(error => {
                console.log(error);
            })
    }

</script>
<script type="module">
    import * as bpac from "{{ asset('/js/bpac.js') }}";
	const DATA_FOLDER = "C:\\Program Files\\Brother bPAC3 SDK\\Templates\\";
	//const DATA_FOLDER = "http://your_server/";
    //------------------------------------------------------------------------------
    //   Function name   :   DoPrint
    //   Description     :   印刷を行う。
    //------------------------------------------------------------------------------
	window.DoPrint = async function DoPrint(label, strExport)
    {
		if(bpac.IsExtensionInstalled() == false)
		{
			const agent = window.navigator.userAgent.toLowerCase();
			const ischrome = (agent.indexOf('chrome') !== -1) && (agent.indexOf('edge') === -1)  && (agent.indexOf('opr') === -1)
			if(ischrome)
				window.open('https://chrome.google.com/webstore/detail/ilpghlfadkjifilabejhhijpfphfcfhb', '_blank');
			return;
		}

        const columns = document.querySelectorAll('.form-group');
        const qutte = columns[5].getElementsByClassName("box-body")[0].innerText.trim();
        const zipcode1 = columns[6].getElementsByClassName("box-body")[0].innerText.trim();
        const zipcode2 = columns[7].getElementsByClassName("box-body")[0].innerText.trim();
        const baseAddress1 = columns[8].getElementsByClassName("box-body")[0].innerText.trim();
        const baseAddress2 = columns[9].getElementsByClassName("box-body")[0].innerText.trim();
        const name = columns[10].getElementsByClassName("box-body")[0].innerText.trim();
        const delivery_id = columns[11].getElementsByClassName("box-body")[0].innerText.trim();
        const plan = columns[12].getElementsByClassName("box-body")[0].innerText.trim();
        const toId = columns[13].getElementsByClassName("box-body")[0].innerText.trim();

		try{
            const strPath = DATA_FOLDER + label +'.lbx';
			const objDoc = bpac.IDocument;
			const ret = await objDoc.Open(strPath);
			if(ret == true)
			{

                if (label == 'label') {
				    const objNumber = await objDoc.GetObject("number");
				    objNumber.Text = delivery_id + ' ' + plan;
				    const objMessage = await objDoc.GetObject("message");
				    objMessage.Text = "";
                    if(strExport == "Preview.bmp")
                    {
                        const image = await objDoc.GetImageData(4, 0, 100);
                        const img = document.getElementById("previewArea");
                        img.src = image;
                    }
                    else
                    {
                        objDoc.StartPrint("", 0);
                        objDoc.PrintOut(1, 0);
                        objDoc.EndPrint();
                        document.getElementById("done").value = 1;
                        document.querySelector('form').setAttribute('name','doneUpdate');
                        objDoc.Close();

                        // (1)XMLHttpRequestオブジェクトを作成
                        var xmlHttpRequest = new XMLHttpRequest();

                        // (2)onreadystatechangeイベントで処理の状況変化を監視
                        xmlHttpRequest.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                document.location.href = "/admin/manager/tickets";
                            }
                        }

                        // (3)HTTPのGETメソッドとアクセスする場所を指定
                        xmlHttpRequest.open('POST','/ticket/update',true);
                        xmlHttpRequest.setRequestHeader('x-csrf-token', '{{ csrf_token() }}'); 
                        xmlHttpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                        // (4)HTTPリクエストを送信
                        xmlHttpRequest.send('id='+ qutte +'&done=1');
                    }
                    objDoc.Close();


                } else if  (label == 'NamePlate3') {
                    const zipcode = await objDoc.GetObject("zipcode");
                    zipcode.Text = zipcode1 + ' - ' + zipcode2;
                    const address1 = await objDoc.GetObject("address1");
                    address1.Text = baseAddress1;
                    const address2 = await objDoc.GetObject("address2");
                    address2.Text = baseAddress2;
                    const objName = await objDoc.GetObject("name");
                    objName.Text = name + ' 様';
                    const objNumber = await objDoc.GetObject("number");
                    objNumber.Text = delivery_id + ' ' + plan;

                    if(strExport == "Preview.bmp")
                    {
                        const image = await objDoc.GetImageData(4, 0, 100);
                        const img = document.getElementById("previewArea");
                        img.src = image;
                    }
                    else
                    {
                        objDoc.StartPrint("", 0);
                        objDoc.PrintOut(1, 0);
                        objDoc.EndPrint();
                        document.getElementById("done").value = 1;
                        document.querySelector('form').setAttribute('name','doneUpdate');
                        objDoc.Close();

                        // (1)XMLHttpRequestオブジェクトを作成
                        var xmlHttpRequest = new XMLHttpRequest();

                        // (2)onreadystatechangeイベントで処理の状況変化を監視
                        xmlHttpRequest.onreadystatechange = function(){
                            if(this.readyState == 4 && this.status == 200){
                                document.location.href = "/admin/manager/tickets";
                            }
                        }

                        // (3)HTTPのGETメソッドとアクセスする場所を指定
                        xmlHttpRequest.open('POST','/ticket/update',true);
                        xmlHttpRequest.setRequestHeader('x-csrf-token', '{{ csrf_token() }}'); 
                        xmlHttpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                        // (4)HTTPリクエストを送信
                        xmlHttpRequest.send('id='+ qutte +'&done=1');
                    }
                    objDoc.Close();

                }

			}
		}
		catch(e)
		{
            console.log(e);
		}
	} 

    window.DoLabelPrint = async function DoLabelPrint(strExport)
    {
		if(bpac.IsExtensionInstalled() == false)
		{
			const agent = window.navigator.userAgent.toLowerCase();
			const ischrome = (agent.indexOf('chrome') !== -1) && (agent.indexOf('edge') === -1)  && (agent.indexOf('opr') === -1)
			if(ischrome)
				window.open('https://chrome.google.com/webstore/detail/ilpghlfadkjifilabejhhijpfphfcfhb', '_blank');
			return;
		}

		try{
			const strPath = DATA_FOLDER + 'label.lbx';
			const objDoc = bpac.IDocument;
			const ret = await objDoc.Open(strPath);
			if(ret == true)
			{
				const objMessage = await objDoc.GetObject("message");
				objMessage.Text = "It's a light plan, so we'll turn it over to the next second term.";
                const objNumber = await objDoc.GetObject("number");
                objNumber.Text = '';

				if (strExport == 'Preview.bmp')
				{
					const image = await objDoc.GetImageData(4, 0, 100);
					const img = document.getElementById("previewArea");
					img.src = image;
				}
				else
				{
					objDoc.StartPrint("", 0);
					objDoc.PrintOut(1, 0);
					objDoc.EndPrint();
				    objDoc.Close();
                    document.location.href = "/admin/manager/tickets";
				}
				objDoc.Close();
			}
		}
		catch(e)
		{
            console.log(e);
		}
	}   
</script>  