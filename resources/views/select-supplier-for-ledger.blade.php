<div class="form-group row d-none">
    <label class="col-sm-2 col-form-label">Type: </label>
    <div class="col-sm-10">
        <select class="form-control" name="type_get" onchange="selectOptionForRecord(this)" id="type_get">
            <option value="">Select Type</option>
            <option selected >Suppliers</option>
            {{-- <option>Expense</option> --}}
        </select>
    </div>
</div>



<div class="form-group row" id="supplier_id_get_row">
    <label class="col-sm-2 col-form-label">Name: </label>
    <div class="col-sm-10">
        <select name="supplier_id_get" id="supplier_id_get" class="form-control select2"></select>
    </div>
</div>



<div class="form-group row">
    <label class="col-sm-2 col-form-label">From: </label>
    <div class="col-sm-10">
        <input type="date" class="form-control" id="from_date">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">To: </label>
    <div class="col-sm-10">
        <input type="date" class="form-control" id="to_date">
    </div>
</div>

<div class="pt-2 d-flex justify-content-between">
    <input type="button" class="btn btn-danger btn-sm" id="get_supplier_data_pdf" value="Get PDF" >
    <input type="button" class="btn btn-success btn-sm" value="Get Record" onclick="showRecord()">
    
</div>

<script>
    function showRecord() {

      
        var supplier_id_get = $("#supplier_id_get").val();
        var type_get = $("#type_get").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

      

        var supplier_name = $("#select2-supplier_id_get-container").text();

        if (type_get == "Suppliers") {

           var url = "{{ url('get-supplier-record') }}" + "/" + from_date + "/" + to_date + "/" + supplier_id_get + "/" + supplier_name;
           viewLargeModel(url);


        }else if(type_get == "Expense"){

            var url = "{{ url('get-expense-ledger') }}" + "/" + from_date + "/" + to_date;
            viewLargeModel(url);

        }

    }


    function selectOptionForRecord(e) {
        if (e.value == "Suppliers") {
            getNamesForRecord();
            $("#supplier_id_get_row").removeClass("d-none");

        } else if (e.value == "Expense") {
            getNamesForRecord();

            $("#supplier_id_get_row").addClass("d-none");

        } else {

        }
    }

    getNamesForRecord();


    function getNamesForRecord() {
        //buyer_and_purchaser_name

        $.ajax({
            url: "{{ url('buyer-purchaser-list') }}",
            type: "GET",
            cache: true,
            success: function(data) {

                const selectElement = $('#supplier_id_get');
                selectElement.append('<option value="">Select Suppliers</option>');
                $.each(data, function(index, option) {
                    selectElement.append('<option value="' + option["id"] + '">' + option["name"] +
                        '</option>');

                });

                selectElement.removeAttr("disabled");

            }
        })

    }


    $(document).on("click", "#get_supplier_data_pdf", function() {

        var supplier_id_get = $("#supplier_id_get")[0].value;
        var from_date = $("#from_date")[0].value;
        var to_date = $("#to_date")[0].value;
        var name = $("#supplier_id_get").find('option:selected').text();



if(from_date!=="" && to_date!=="")

{
$.ajax({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
    url:"{{ url('supplier-data-pdf') }}",
    type:"GET",
    data:{from_date:from_date, to_date:to_date, name:name, supplier_id_get:supplier_id_get},
    success:function(data){
        const pdfData = data[0];
        // Create a blob object from the base64-encoded data
        const byteCharacters = atob(pdfData);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], {type: 'application/pdf'});


        // Create a URL for the blob object
        const url = URL.createObjectURL(blob);

        // Create a link element with the URL and click on it to download the PDF file
        const link = document.createElement('a');
        link.href = url;
        link.download = 'supplier_data.pdf';
        document.body.appendChild(link);
        link.click();
    }
})

}

})




    $('.select2').select2();
</script>
