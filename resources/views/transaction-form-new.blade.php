@extends('layout.combine')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <!-- Horizontal Form -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Description/Transaction Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" id="buyer_purchaser_detail">
                    <div class="card-body" style="padding-bottom:0;">



                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <select disabled class="form-control select2" name="buyer_purchaser_id"
                                    id="buyer_purchaser_id">


                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="amount_status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select name="amount_status" id="amount_status" class="form-control"
                                    onclick="disableInputTage(this)">
                                    <option value="">Select Amount Status</option>
                                    <option>Payable</option>
                                    <option>Recieved</option>
                                </select>
                            </div>
                        </div>



                        <div class="form-group row">
                            <label for="head" class="col-sm-2 col-form-label">Head</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="head" id="head"
                                    placeholder="Head.......">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="total" class="col-sm-2 col-form-label">Amount</label>
                            <div class="col-sm-10 d-flex">
                                <input type="text" class="form-control" name="quantity" id="quantity" onkeyup="calculate(this)"
                                    placeholder="Quantity">
                                <input type="text" class="form-control ml-1" name="amount" id="amount" onkeyup="calculate(this)"
                                    placeholder="Amount">
                                <input type="text" class="form-control ml-1" name="total" id="total"
                                    placeholder="Total">
                            </div>
                        </div>
                        <input type="hidden" class="form-control" name="hidden_id" id="hidden_id">

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
                    <h3 class="card-title">Buyer/Purchaser Detail</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

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
                    <table class="data-table table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>A/C#</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>




    </div>
@endsection



@section('script')
    <script>
        var editedRow = null;

        $("#buyer_purchaser_detail").submit(function(event) {
            event.preventDefault();


            if ($("#amount_status").val() !== "Recieved") {



                // Serialize the form data
                var formData = $(this).serializeArray();

                // Extract values from the serialized form data
                var values = formData.map(function(obj) {
                    return obj.value;
                });

                if (editedRow) {
                    // Update the values of the edited row
                    editedRow.find("td").each(function(index) {
                        var fieldName = formData[index] ? formData[index].name : null;
                        if (fieldName && fieldName !== 'buyer_purchaser_id') {
                            $(this).text(values[index]);
                        } else if (fieldName === 'buyer_purchaser_id') {
                            // Do nothing for hidden field
                        }
                    });
                    editedRow = null; // Reset editedRow after updating
                } else {
                    // Create a new row with td elements containing the form values and action buttons
                    var newRow = "<tr>";
                    values.forEach(function(value, index) {
                        var fieldName = formData[index] ? formData[index].name : null;

                        if (fieldName && fieldName !== 'buyer_purchaser_id') {
                            newRow += "<td>" + value + "</td>";
                        } else if (fieldName === 'buyer_purchaser_id') {
                            newRow += "<td style='display:none;'>" + value +
                            "</td>"; // Hide the td for 'buyer_purchaser_id'
                        }
                    });

                    // Add action buttons (edit and delete)
                    newRow += "<td><button class='edit-btn btn-sm btn-success'>Edit</button></td>";
                    newRow += "<td><button class='delete-btn btn-sm btn-danger'>Delete</button></td>";

                    newRow += "</tr>";

                    // Append the new row to the tbody of the table
                    $("#bill_table tbody").append(newRow);
                }


                $("#head").val("");
                $("#quantity").val("");
                $("#amount").val("");
                $("#total").val("");

            } else if ("Payable") {


            }


            // Clear the form fields after saving
            // $(this)[0].reset();
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
            $("#buyer_purchaser_id").val(rowData[
            0]); // Replace 'field1' with the actual ID or name of your form field
            $("#amount_status").val(rowData[1]); // Replace 'field2' with the actual ID or name of your form field
            $("#head").val(rowData[2]); // Replace 'field2' with the actual ID or name of your form field
            $("#quantity").val(rowData[2]); // Replace 'field2' with the actual ID or name of your form field
            $("#amount").val(rowData[4]); // Replace 'field2' with the actual ID or name of your form field
            $("#total").val(rowData[5]); // Replace 'field2' with the actual ID or name of your form field

            // Repeat for other form fields
        });

        $(document).on("click", ".delete-btn", function() {
            // Handle delete button click
            $(this).closest("tr").remove(); // Remove the closest tr
        });



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

        }


        function getNames() {
            //buyer_and_purchaser_name

            $.ajax({
                url: "{{ url('buyer-purchaser-list') }}",
                type: "GET",
                cache: true,
                success: function(data) {

                    const selectElement = $('#buyer_purchaser_id');
                    selectElement.append('<option value="">Select Name</option>');
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

        getNames();





        // It has the name attribute "registration"
        // $("#buyer_purchaser_detail").validate({

        //     highlight: function(element) {
        //         element.style.border = "1px solid red";
        //     },
        //     unhighlight: function(element) {
        //         // Remove the red border when the input field is valid
        //         element.style.border = "";
        //     },
        //     errorPlacement: function(error, element) {
        //         // Do nothing to suppress the default error message placement
        //     },

        //     rules: {
        //         amount_status: "required",
        //         buyer_purchaser_id: "required",
        //         head: "required",
        //         quantity: "required",
        //         amount: "required",
        //         total: "required",
        //     },

        //     submitHandler: function(form) {

        //         var formData = new FormData(form);

        //         $.ajax({
        //             headers: {
        //                 'X-CSRF-Token': csrfToken
        //             },
        //             url: "{{ url('insert-buyer-purchaser-record') }}",
        //             type: "POST",
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             success: function(response) {
        //                 form.reset();
        //                 buyer_purchaser_table.draw();
        //                 $("#hidden_buyer_purchaser_id").val("");
        //                 // Example toastr notification
        //                 toastr.success('Saved Successfully!');
        //             },
        //             error: function(error) {
        //                 // Handle any errors here
        //                 console.error("Error:", error);
        //             }
        //         });

        //     }
        // });


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

        $('.select2').select2();
    </script>
@endsection
