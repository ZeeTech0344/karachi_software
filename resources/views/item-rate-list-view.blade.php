
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                   
                    <label id="sum" style="margin-left:180px;"> </label>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search_item_rate" id="search_item_rate"
                                class="form-control float-right" placeholder="Search">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-2">
                    <table class="table table-head-fixed text-nowrap w-100" id="item_rate_list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>



    <script>
        var item_rate_list = $('#item_rate_list').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ url('item-rate-list') }}",
                data: function(d) {
                    d.search_item_rate = $("#search_item_rate").val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'rate',
                    name: 'rate'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });


        $('#search_item_rate').keypress(function(event) {

            console.log("yes");
            // Check if the pressed key is Enter (keyCode 13)
            if (event.which === 13) {
                item_rate_list.draw();
            }
        });






        $("#add_new_rate").on("click", function() {
            var item_id = $("#item_id").val();
            var rate = $("#current_rate").val();


            if(item_id == "" ||  rate == ""){
                return false;               
            }
           
            var confirm_rate = confirm("Are you sure! You want to add rate");
           
            if(confirm_rate){
                $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('insert-item-rate') }}",
                type: "POST",
                data: {
                    item_id: item_id,
                    rate: rate
                },
                success: function(response) {

                    $("#item_id")[0].value = "";
                    $("#current_rate").val('')
                    item_rate_list.draw();
                    // $("#hidden_buyer_purchaser_id").val("");
                    // Example toastr notification
                    toastr.success('Saved Successfully!');
                },
                error: function(error) {
                    // Handle any errors here
                    console.error("Error:", error);
                }
            });

            }
           

        })


        $("#item_id").on("change", function() {

            var item_id = $(this).val();

            console.log(item_id);

            $.ajax({
                url: "{{ url('get-item-rate') }}",
                type: "GET",
                data: {
                    item_id: item_id
                },
                success: function(data) {
                    
                    $("#old_rate").val(data["rate"]);
                }
            })
        })






        // convert small letter to capital
        function convertToUpperCase(input) {
            input.value = input.value.toUpperCase();
        }

        $(document).on("click", ".edit_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('edit-buyer-purchaser-detail') }}",
                type: "POST",
                data: {
                    id
                },
                success: function(data) {
                    $("#name").val(data["name"]);
                    $("#phone_no").val(data["phone_no"]);
                    $("#account_no").val(data["account_no"]);
                    $("#cnic").val(data["cnic"]);
                    $("#address").val(data["address"]);
                    $("#opening_amount").val(data["opening_amount"]);
                    $("#hidden_buyer_purchaser_id").val(data["id"]);

                }
            })

        });



        $(document).on("click", ".delete_buyer_purchaser_detail", function() {

            var confirm_delete = confirm(
                "Are you sure you want to delete supplier and its all record! You data will not restored");
            if (confirm_delete) {
                var id = $(this).data('id');
                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('delete-supplier-record') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(data) {

                        buyer_purchaser_table.draw();

                    }
                })

            }


        });
    </script>

