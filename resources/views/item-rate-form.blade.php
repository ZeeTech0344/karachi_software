@extends('layout.combine')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Items Invoice Form</h3>
                    {{-- <div class="d-flex justify-content-end"><button class="btn btn-sm btn-warning"
                            id="item_rate_list_view">Rate List</button></div> --}}
                </div>

                <!-- /.card-header -->
                <!-- form start -->


                <form class="form-horizontal" id="items-rate-form">
                    <div class="card-body" style="padding-bottom:0;">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <select name="item_name" id="item_name" class="form-control"
                                    onchange="setItemId(this.value)">
                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="item_id" name="item_id">




                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Total</label>
                            <div class="col-sm-10 d-flex">
                                <div class="border-label-container col-md-3" style="padding:0;">

                                    <label for="firstName" class="border-label">Rate</label>
                                    <input type="number" class="form-control" name="old_rate" id="old_rate"
                                        onkeyup="calculate()">
                                </div>

                                <div class="border-label-container col-md-3">

                                    <label for="firstName" class="border-label">Qty/Length</label>
                                    <input type="number" class="form-control mr-2" name="qty_or_length" id="qty_or_length"
                                        oninput="convertToUpperCase(this)" onkeyup="calculate()">
                                </div>

                                <div class="border-label-container col-md-3">

                                    <label for="firstName" class="border-label">Scale</label>
                                    <select name="scale" id="scale" class="form-control">
                                        <option value="">Scale</option>
                                        <option>Inch</option>
                                        <option>ft</option>
                                        <option>m</option>
                                        <option>cm</option>
                                        <option>No</option>
                                    </select>
                                </div>

                                <div class="border-label-container col-md-3 " style="padding:0;">

                                    <label for="firstName" class="border-label">Total</label>
                                    <input type="number" class="form-control bg-white" readonly name="total_amount"
                                        id="total_amount">
                                </div>

                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="hidden_buyer_purchaser_id"
                            id="hidden_buyer_purchaser_id">


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Add</button>
                    </div>
                    <!-- /.card-footer -->
                </form>

                <input type="hidden" id="invoice_no_edit" name="invoice_no_edit"
                    value="{{ isset($invoice_data) && $invoice_data ?  $invoice_data[0]->invoice_no : '' }}">
            </div>
            <!-- /.card -->
        </div>



        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Item Invoice</h2>
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
                                <th>Name</th>
                                <th>Rate</th>
                                <th>Qty/Length</th>
                                <th>Scale</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($invoice_data))
                            {{-- {{$invoice_data}} --}}
                            @foreach ($invoice_data as $data)
                                <tr>
                                    <td>{{ $data->getItemName->name }}</td>
                                    <td class="d-none">{{ $data->getItemName->id }}</td>
                                    <td>{{ $data->old_rate }}</td>
                                    <td>{{ $data->qty_or_length }}</td>
                                    <td>{{ $data->scale }}</td>
                                    <td>{{ $data->total }}</td>
                                    <td class="d-none">{{ $data->id }}</td>
                                    <td><button class='edit-btn btn-sm btn-success'>Edit</button></td>
                                    <td><button class='delete-btn btn-sm btn-danger'
                                            onclick="deleteItemDataId(this)" data-id="{{$data->id}}">Delete</button></td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="p-3"><input type="button" class="btn btn-warning" value="Save" onclick="saveData()">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection



@section('script')
    <script>
        function deleteItemDataId(e) {

            var item_data_id = e.getAttribute("data-id");

           

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('delete-item-data') }}",
                type: "POST",
                data: {
                    id: item_data_id,
                },
                success:function(data){
                    $(e).closest("tr").fadeOut();
                }

            })

        }


        function setItemId(item_name) {
            $("#item_id").val(item_name);
        }


        function calculate() {
            var old_rate = $("#old_rate").val();
            var qty_or_length = $("#qty_or_length").val();
            var total_amount = $("#total_amount").val(old_rate * qty_or_length);
        }


        var purchase_table = $('#purchase_table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ url('item-rate-list') }}",
                data: function(d) {
                    d.search_purchase = $("#search_purchase").val();
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


        $('#search_purchase').keypress(function(event) {

            console.log("yes");
            // Check if the pressed key is Enter (keyCode 13)
            if (event.which === 13) {
                purchase_table.draw();
            }
        });


        var editedRow = null;
        // It has the name attribute "registration"
        $("#items-rate-form").validate({

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
                item_id: "required",
                old_rate: "required",
                qty_or_length: "required",
                scale: "required",

            },
            submitHandler: function(form) {


                // Serialize the form data
                var formData = $(form).serializeArray();

                // Extract values from the serialized form data
                var values = formData.map(function(obj) {
                    return obj.value;
                });

                if (editedRow) {
                    const newArray = values.slice(0, 6);
                    // Update the values of the edited row
                    editedRow.find("td").each(function(index) {

                        var fieldName = formData[index] ? formData[index].name : null;
                        if (fieldName) {
                            if (fieldName == "item_name") {
                                var item_name = $('#item_name').children("option:selected").text();
                                $(this).text(item_name);
                            } else {
                                $(this).text(newArray[index]);
                            }

                        }

                    });
                    editedRow = null; // Reset editedRow after updating

                } else {
                    // Create a new row with td elements containing the form values and action buttons
                    var newRow = "<tr>";
                    values.forEach(function(value, index) {

                        var fieldName = formData[index] ? formData[index].name : null;
                        if (fieldName && fieldName !== 'hidden_id') {

                            console.log(fieldName);

                            if (fieldName == "item_name") {
                                var item_name = $('#item_name').children("option:selected").text();
                                newRow += "<td>" + item_name + "</td>";
                            } else if (fieldName == "item_id") {
                                newRow += "<td class='d-none'>" + value + "</td>";
                            } else if (fieldName == "hidden_buyer_purchaser_id") {
                                newRow += "<td class='d-none' >" + value + "</td>";
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



                // $("#amount_status").val("");
                $("#item_id").val("");
                $("#old_rate").val("");
                $("#qty_or_length").val("");
                $("#scale").val("");
                $("#total_amount").val("");


                //     var formData = new FormData(form);

                //     $.ajax({
                //         headers: {
                //             'X-CSRF-Token': csrfToken
                //         },
                //         url: "{{ url('insert-item-rate') }}",
                //         type: "POST",
                //         data: formData,
                //         processData: false,
                //         contentType: false,
                //         success: function(response) {
                //             form.reset();
                //             purchase_table.draw();
                //             $("#hidden_buyer_purchaser_id").val("");
                //             // Example toastr notification
                //             toastr.success('Saved Successfully!');
                //         },
                //         error: function(error) {
                //             // Handle any errors here
                //             console.error("Error:", error);
                //         }
                //     });

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
            // console.log(rowData);

            // $("#date").val(rowData[0]); 

            $("#item_name").val(rowData[1]); // Replace 'field2' with the actual ID or name of your form field
            $("#item_id").val(rowData[1]);
            $("#old_rate").val(rowData[2]); // Replace 'field2' with the actual ID or name of your form field
            $("#qty_or_length").val(rowData[3]); // Replace 'field2' with the actual ID or name of your form field
            $("#scale").val(rowData[4]); // Replace 'field2' with the actual ID or name of your form field
            $("#total_amount").val(rowData[5]); // Replace 'field2' with the actual ID or name of your form field


            // Repeat for other form fields
        });







        $("#add_new_rate").on("click", function() {
            var item_id = $("#item_id").val();
            var rate = $("#current_rate").val();


            if (item_id == "" || rate == "") {
                return false;
            }

            var confirm_rate = confirm("Are you sure! You want to add rate");

            if (confirm_rate) {
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
                        purchase_table.draw();
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


        // $("#item_id").on("change", function() {

        //     var item_id = $(this).val();

        //     console.log(item_id);

        //     $.ajax({
        //         url: "{{ url('get-item-rate') }}",
        //         type: "GET",
        //         data: {
        //             item_id: item_id
        //         },
        //         success: function(data) {

        //             $("#old_rate").val(data["rate"]);
        //         }
        //     })
        // })






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
                var slice_array = tableDataArray[index].slice(0, 7);
                send_data_to_server.push(slice_array);
            });

            var data_length = tableDataArray.length;




            var indexNames = ["item_name", "item_id", "old_rate", "qty_or_length", "scale", "total", "hidden_id"];

            var result = [];

            send_data_to_server.forEach(function(row) {
                var rowData = {};

                row.forEach(function(value, colIndex) {
                    var indexName = indexNames[colIndex];
                    rowData[indexName] = value;
                });

                result.push(rowData);
            });

            var invoice_no_edit = $("#invoice_no_edit").val();

            console.log(invoice_no_edit);

            var jsonData = JSON.stringify({
                item_data: result,
                invoice_no: invoice_no_edit
            });


            console.log($("#buyer_purchaser_id").val());

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                url: "{{ url('insert-item-data') }}",
                type: "POST",
                data: jsonData,
                contentType: "application/json", // Set content type to JSON
                dataType: "json", // Expect JSON response from the server
                success: function(data) {
                    $("#bill_table tbody").html("");
                    toastr.success('Data Saved Successfully!');
                }
            })



        }





        $("#item_rate_list_view").on("click", function() {

            url = "{{ url('item-rate-list-view') }}";
            viewModal(url);

        })
    </script>
@endsection
