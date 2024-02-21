<div class="card-body table-responsive p-2">
    <div class="d-flex justify-content-center p-2">

        <div class="border-label-container col-md-3" style="padding:0;">
            <label for="from_date" class="border-label">From</label>
            <input type="date" class="form-control" name="from_date" id="from_date">
        </div>

        <div class="border-label-container col-md-3 ml-2 mr-2" style="padding:0;">
            <label for="to_date" class="border-label">To</label>
            <input type="date" class="form-control" name="to_date" id="to_date">
        </div>

        <div class="border-label-container col-md-3" style="padding:0;">
            <label for="to_date" class="border-label">Search <i class="fas fa-search"></i></label>
            <input type="text" class="form-control" name="search_item_data" id="search_item_data">
        </div>
        <input type="button" name="search_all_data" value="Search" id="search_all_data"
            class="btn btn-sm btn-warning ml-2">

    </div>
    <table class="table table-head-fixed text-nowrap w-100" id="item_data_list">
        <thead>
            <tr>
                <th>Invoice#</th>
                <th>Item</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Qty/Length</th>
                <th class="text-center">Total</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div>
        <p id="total_amount_get" class="text-info"></p>
    </div>
</div>



<script>
    var item_data_list = $('#item_data_list').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: {
            url: "{{ url('get-cousting-report') }}",
            data: function(d) {
                d.search_item_data = $("#search_item_data").val();
                d.from_date = $("#from_date").val();
                d.to_date = $("#to_date").val();
            }
        },
        columns: [{
                data: 'invoice_no',
                name: 'invoice_no'
            },
            {
                data: 'item_name',
                name: 'item_name'
            },
            {
                data: 'old_rate',
                name: 'old_rate'
            },
            {
                data: 'qty_or_length',
                name: 'qty_or_length'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
        drawCallback: function(settings) {

            if(settings.json.data.length>0){
                var total_amount_get = settings.json.data[0].total_amount;
            }else{
                total_amount_get = 0;
            }
            
            $("#total_amount_get").text("Total Amount : "+total_amount_get);

        }



    });



    $('#search_item_data').keypress(function(event) {
        // Check if the pressed key is Enter (keyCode 13)
        if (event.which === 13) {
            item_data_list.draw();
        }
    });

    $('#search_all_data').click(function(event) {
        // Check if the pressed key is Enter (keyCode 13)

        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var search_item_data = $("#search_item_data").val();

        if ((from_date && to_date) || search_item_data) {
            item_data_list.draw();
        }


    });

   
</script>
