@extends('layout.combine')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Items Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" id="items-form">
                    <div class="card-body" style="padding-bottom:0;">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" id="name"
                                    oninput="convertToUpperCase(this)" placeholder="Item Name.......">
                            </div>
                        </div>
                      

                        <input type="hidden" class="form-control" name="hidden_buyer_purchaser_id"
                            id="hidden_buyer_purchaser_id">

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>


        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Items List</h2>
                    <label id="sum" style="margin-left:180px;"> </label>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" id="search_value" class="form-control float-right"
                                placeholder="Search">

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
                    <table class="table table-head-fixed text-nowrap" id="supplier_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone#</th>
                                <th>Status</th>
                                <th>Action</th>
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
@endsection



@section('script')
    <script>
        var buyer_purchaser_table = $('#supplier_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ url('get-supplier-list') }}",
                data: function(d) {
                    d.search = $("#search_value").val();
                }
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone_no',
                    name: 'phone_no'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });


        $('#search_value').keypress(function(event) {
            // Check if the pressed key is Enter (keyCode 13)
            if (event.which === 13) {
                buyer_purchaser_table.draw();
            }
        });



        // It has the name attribute "registration"
        $("#items-form").validate({

            highlight: function(element) {
                element.style.border = "1px solid red";
            },
            unhighlight: function(element) {
                // Remove the red border when the input field is valid
                element.style.border = "";
            },
            errorPlacement: function(error, element) {
                // Do nothing to suppress the default error message placement
            },

            rules: {
                name: "required",
            },
            submitHandler: function(form) {

                var formData = new FormData(form);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('insert-items') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        form.reset();
                        buyer_purchaser_table.draw();
                        $("#hidden_buyer_purchaser_id").val("");
                        // Example toastr notification
                        toastr.success('Saved Successfully!');
                    },
                    error: function(error) {
                        // Handle any errors here
                        console.error("Error:", error);
                    }
                });

            }
        });

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

            var confirm_delete = confirm("Are you sure you want to delete supplier and its all record! You data will not restored");
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
@endsection
