@extends('layout.combine')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Supplier/Transaction Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" id="buyer_purchaser_detail">
                    <div class="card-body" style="padding-bottom:0;">


                        {{-- <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Date</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="date" id="date">
                            </div>
                        </div> --}}


                        <div class="form-group row d-none">
                            <label for="type" class="col-sm-2 col-form-label">Type</label>
                            <div class="col-sm-10">
                                <select class="form-control" {{ isset($type) && ($type == 'Suppliers' || $type == 'Expense') ? 'disabled' : '' }} name="type" onchange="selectOption()" id="type">
                                    <option value="">Select Type</option>
                                    <option {{ isset($type) && ($type == 'Suppliers') ? 'selected' : '' }} selected>Suppliers</option>
                                    {{-- <option {{ isset($type) && ($type == 'Expense') ? 'selected' : '' }}>Expense</option> --}}
                                </select>
                                
                                
                            </div>
                        </div>
                        
                        

                        <div class="form-group row supplier_field">
                            <label for="name" class="col-sm-2 col-form-label ">Name</label>
                            <div class="col-sm-10">
                                <select disabled class="form-control select2" name="buyer_purchaser_id"
                                    id="buyer_purchaser_id">
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="buyer_purchaser_hidden_id" id="buyer_purchaser_hidden_id">

                        <div class="form-group row supplier_field">
                            <label for="amount_status" class="col-sm-2 col-form-label ">Status</label>
                            <div class="col-sm-10">
                                <select name="amount_status" id="amount_status" class="form-control"
                                    onclick="disableInputTage(this)">
                                    <option value="">Select Amount Status</option>
                                    <option>Bill</option>
                                    <option>Supplier Amount Recieved</option>
                                </select>
                            </div>
                        </div>





                        <input type="hidden" name="amount_status_hidden_value" id="amount_status_hidden_value">

                        {{-- 
                        <div class="justify-content-end pb-2 d-none" id="buttons_for_bill">
                            <button type="button" class="btn btn-warning" id="create">Create</button>
                            <button type="button" class="btn btn-secondary ml-2" id="reset_bill">Reset</button>
                        </div> --}}


                        <div class="form-group row d-none" id="head_row">
                            <label for="head" class="col-sm-2 col-form-label">Head</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="head" id="head"
                                    placeholder="Head......."
                                    value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->head : '' }}">
                            </div>
                        </div>

                        <div class="form-group row d-none" id="calculation_row">
                            <label for="total" class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10 d-flex">
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                    onkeyup="calculate(this)" placeholder="Quantity">
                                <input type="number" class="form-control ml-1" value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->amount : '' }}"  name="amount" id="amount"
                                    onkeyup="calculate(this)" placeholder="Amount">
                                <input type="text" class="form-control ml-1" name="total" value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->amount : '' }}" id="total" readonly
                                    placeholder="Total">
                            </div>
                        </div>


                        <div class="form-group row" id="remarks_row">
                            <label for="remarks" class="col-sm-2 col-form-label">Remarks</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="remarks" id="remarks"
                                    placeholder="Remarks.......">
                            </div>
                        </div>



                        {{-- <input type="hidden" class="form-control" name="hidden_id" id="hidden_id"> --}}
                        <div class="d-flex justify-content-end pb-2">
                            <button type="submit" class="btn btn-info">Add</button>
                        </div>

                        <input type="hidden" name="hidden_id" id="hidden_id"  value="{{ isset($data) && isset($type) && $type == 'Expense' ? $data->id : '' }}">
                        <input type="hidden" id="hidden_type" value="{{ isset($type) && $type == 'Expense' ? $type : '' }}">
                    </div>
                    <!-- /.card-body -->

                    <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->

        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Supplier/Transaction Detail</h2>
                    <label id="sum" style="margin-left:180px;"> </label>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" id="table_search" class="form-control float-right"
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
                <div class="card-body table-responsive p-0">
                    <table class="table table-head-fixed text-nowrap" id="bill_table">
                        <thead>
                            <tr>
                                {{-- <th class="d-none">Date</th> --}}
                                <th>Head</th>
                                <th>Qty</th>
                                <th>Amount</th>
                                <th>Total</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="p-3"><input type="button" class="btn btn-warning" value="Save"
                            onclick="saveData()"> </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
@endsection



@section('script')
    <script>
        @if (isset($type) && !empty($type))
            // Call the selectOption function automatically

            selectOption();
        @endif




        $("#table_search").keyup(function() {

            var value = this.value.toLowerCase().trim();

            $("#bill_table tr").each(function(index) {
                if (!index) return;
                $(this).find("td").each(function() {
                    var id = $(this).text().toLowerCase().trim();
                    var not_found = (id.indexOf(value) == -1);
                    $(this).closest('tr').toggle(!not_found);
                    return not_found;

                });
            });

        });


        function tableToArray() {
            var dataArray = [];

            // Iterate through each row in the table
            $('#bill_table tbody tr').each(function(rowIndex, row) {
                var rowData = [];

                // Iterate through each cell in the row
                $(row).find('td').each(function(colIndex, cell) {
                    rowData.push($(cell).text());
                });

                dataArray.push(rowData);
            });

            return dataArray;
        }

        // Example usage
        function saveData() {

            var send_data_to_server = [];

            var tableDataArray = tableToArray();
            $.each(tableDataArray, function(index, value) {
                var slice_array = tableDataArray[index].slice(0, 5);
                send_data_to_server.push(slice_array);
            });

            var data_length = tableDataArray.length;




            var indexNames = ["head", "quantity", "amount", "total","remarks"];

            var result = [];

            send_data_to_server.forEach(function(row) {
                var rowData = {};

                row.forEach(function(value, colIndex) {
                    var indexName = indexNames[colIndex];
                    rowData[indexName] = value;
                });

                result.push(rowData);
            });



            if (data_length > 0 && $("#type").val() == "Suppliers" && $("#amount_status").val() == "Bill") {

                var jsonData = JSON.stringify({
                    supplier_data: result,
                    supplier_id: $("#buyer_purchaser_id").val(),
                    // date:$("#date").val()
                });


                console.log($("#buyer_purchaser_id").val());

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('insert-supplier-data') }}",
                    type: "POST",
                    data: jsonData,
                    contentType: "application/json", // Set content type to JSON
                    dataType: "json", // Expect JSON response from the server
                    success: function(data) {
                        $("#bill_table tbody").html("");
                        toastr.success('Supplier Data Saved Successfully!');
                    }
                })

            }

        }






        var editedRow = null;

        $("#buyer_purchaser_detail").submit(function(event) {



            event.preventDefault();


            if ($("#type").val() == "Suppliers" && $("#amount_status").val() == "Bill") {


                // Serialize the form data
                var formData = $(this).serializeArray();

                // Extract values from the serialized form data
                var values = formData.map(function(obj) {
                    return obj.value;
                });

                if (editedRow) {
                    
                    const newArray = values.slice(5,10);

                    console.log(newArray);

                    // Update the values of the edited row
                    editedRow.find("td").each(function(index) {
                        var fieldName = formData[index] ? formData[index].name : null;
                        if (fieldName) {
                            $(this).text(newArray[index]);
                        }
                    });
                    editedRow = null; // Reset editedRow after updating
                    calculateSum();
                } else {
                    // Create a new row with td elements containing the form values and action buttons
                    var newRow = "<tr>";
                    values.forEach(function(value, index) {
                        var fieldName = formData[index] ? formData[index].name : null;

                        if (fieldName && fieldName !== 'buyer_purchaser_id' && fieldName !==
                            'buyer_purchaser_hidden_id' && fieldName !== 'amount_status' &&
                            fieldName !== 'amount_status_hidden_value' && fieldName !== 'type' &&
                             fieldName !== 'hidden_id') {

                            if (fieldName == "total") {
                                newRow += "<td class='total'>" + value + "</td>";
                            } else {
                                newRow += "<td>" + value + "</td>";
                            }

                        }
                    });

                    // Add action buttons (edit and delete)
                    newRow += "<td><button class='edit-btn btn-sm btn-success'>Edit</button></td>";
                    newRow += "<td><button class='delete-btn btn-sm btn-danger'>Delete</button></td>";

                    newRow += "</tr>";

                    // Append the new row to the tbody of the table
                    $("#bill_table tbody").append(newRow);
                }

                // Clear the form fields after saving

                calculateSum();

                // $("#amount_status").val("");
                $("#head").val("");
                $("#quantity").val("");
                $("#amount").val("");
                $("#total").val("");
                $("#remarks").val("");

            } else if ($("#type").val() == "Suppliers" && $("#amount_status").val() == "Supplier Amount Recieved") {

                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('supplier-amount-recieved') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        $("#amount").val("");
                        $("#total").val("");
                        $("#remarks").val("");


                        toastr.success('Supplier Amount Recieved Successfully!');


                    }
                })


            } else if ($("#type").val() == "Expense") {

                var formData = new FormData(this);

                $.ajax({
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    url: "{{ url('insert-expense') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {

                        $("#head").val("");
                        $("#amount").val("");
                        $("#total").val("");
                        $("#hidden_id").val("");
                        $("#hidden_type").val("");

                        toastr.success('Expense Inserted Successfully!');

                    }
                })


            }


        });

        // Add event handlers for edit and delete buttons
        $(document).on("click", ".edit-btn", function() {
            // Handle edit button click
            editedRow = $(this).closest("tr");
            var rowData = editedRow.find("td").map(function() {
                return $(this).text();
            }).get();

            // Populate the form fields with the row data

            // this is hidden id value



            // $("#date").val(rowData[0]); 
            $("#head").val(rowData[0]); // Replace 'field2' with the actual ID or name of your form field
            $("#quantity").val(rowData[1]); // Replace 'field2' with the actual ID or name of your form field
            $("#amount").val(rowData[2]); // Replace 'field2' with the actual ID or name of your form field
            $("#total").val(rowData[3]); // Replace 'field2' with the actual ID or name of your form field
            $("#remarks").val(rowData[4]); // Replace 'field2' with the actual ID or name of your form field
            

            // Repeat for other form fields
        });

        $(document).on("click", ".delete-btn", function() {
            // Handle delete button click
            $(this).closest("tr").remove(); // Remove the closest tr
            calculateSum();
        });





        function calculateSum() {
            var sum = 0;
            // Iterate through each element with class "myClass"
            $('.total').each(function() {

                console.log($(this)[0].innerText);
                // Parse the value as a float and add it to the sum
                sum += parseFloat($(this)[0].innerText) || 0;
            });
            // Display the sum in the designated element (e.g., a div with id "sum")
            $('#sum').text('Sum: ' + sum);
        }





        function calculate(e) {

            var qty = $("#quantity").val();
            var amount = $("#amount").val();

            if (qty == "") {
                $("#total").val(amount);
            } else {
                $("#total").val(qty * amount);
            }


        }


        function disableInputTage(e) {

            if (e.value == "Recieved") {
                $("#head").prop("disabled", true);
                $("#quantity").prop("disabled", true);
            } else {
                $("#head").prop("disabled", false);
                $("#quantity").prop("disabled", false);
            }

            // $("#buyer_purchaser_detail")[0].reset();

        }



        function selectOption() {
            var get_type_value = $("#type").val();
            if (get_type_value == "Suppliers") {
                getNames();

                $(".supplier_field").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").removeClass("d-none");

                if ($("#amount_status").val() == "Supplier Amount Recieved") {
                    $("#head_row").addClass("d-none");

                }


            } else if (get_type_value == "Expense") {

                $(".supplier_field").addClass("d-none");
                $("#remarks_row").addClass("d-none");
                $("#head_row").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").addClass("d-none");
                $("#bill_table tbody").html("");
                calculateSum();

            } else {
                $(".supplier_field").removeClass("d-none");
                $("#remarks_row").addClass("d-none");
                $("#head_row").addClass("d-none");
                $("#calculation_row").addClass("d-none");
                $("#quantity").addClass("d-none");
                calculateSum();
            }

            // $("#buyer_purchaser_detail")[0].reset();
        }

        selectOption();


        $("#amount_status").on("change", function() {

            if (this.value !== "Bill") {
                // $("#remarks_row").removeClass("d-none");

                var check = confirm("Are you sure! You dont want to create a bill");
                if (check) {

                    $("#remarks_row").removeClass("d-none");
                    $("#bill_table tbody").html("");
                    $("#head_row").addClass("d-none");
                    $("#calculation_row").removeClass("d-none");
                    $("#quantity").addClass("d-none");
                } else {
                    // $("#remarks_row").addClass("d-none");
                }

            } else {

                // $("#remarks_row").addClass("d-none");
                $("#head_row").removeClass("d-none");
                $("#calculation_row").removeClass("d-none");

                $("#quantity").removeClass("d-none");
            }


        })



        function getNames() {
            //buyer_and_purchaser_name
            $("#buyer_purchaser_id").html("");
            $.ajax({
                url: "{{ url('buyer-purchaser-list') }}",
                type: "GET",
                cache: true,
                success: function(data) {

                    const selectElement = $('#buyer_purchaser_id');
                    selectElement.append('<option value="">Select Suppliers</option>');
                    $.each(data, function(index, option) {
                        selectElement.append('<option value="' + option["id"] + '">' + option["name"] +
                            '</option>');

                    });

                    selectElement.removeAttr("disabled");

                }
            })

            // $.each(options, function(index, option) {
            //     selectElement.append('<option value="' + option + '">' + option + '</option>');
            // });

        }



        if ($("#head").prop("readonly")) {
            $("#buyer_purchaser_detail").rules("remove", "head");
        }

        if ($("#quantity").prop("readonly")) {
            $("#buyer_purchaser_detail").rules("remove", "quantity");
        }



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
                    $("#hidden_buyer_purchaser_id").val(data["id"]);

                }
            })

        });


        $(document).on("click", ".update_status_buyer_purchaser_detail", function() {

            var id = $(this).data('id');

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('update-status-buyer-purchaser-detail') }}",
                type: "POST",
                data: {
                    id
                },
                success: function(data) {

                    buyer_purchaser_table.draw();

                }
            })

        });


        $("#supplier-ledger").on("click", function() {

            var url = "{{ url('select-supplier-for-ledger') }}";
            viewModal(url);

        })


        $('.select2').select2();
    </script>
@endsection
