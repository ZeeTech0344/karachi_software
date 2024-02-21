</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        Created By<a href="https://zeetechservices4u.blogspot.com/"> ZeeTech-+923441207218</a>.</strong> All rights
        reserved.
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2023-24
</footer>
</div>



<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-default-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-default-body">
                <p>One fine body&hellip;</p>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-xl-title">Extra Large Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-xl-body">
                <p>One fine body&hellip;</p>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<script src="{{ url('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ url('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ url('dist/js/adminlte.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->

<!-- AdminLTE for demo purposes -->

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->


<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>

<!-- DataTables CSS and JS -->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

<!-- Yajra DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js">
</script>




<!-- Include Toastr.js from CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
</body>

</html>

<script>
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    function viewModal(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#modal-default').modal('show');
                    $('#modal-default-title').html(data["title"]);
                    $('#modal-default-body').html(data["view"]);

                }
            })
        }
    }


    function viewLargeModel(url) {
        if (url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $('#modal-xl').modal('show');
                    $('#modal-xl-title').html(data["title"]);
                    $('#modal-xl-body').html(data["view"]);

                }
            })
        }
    }





    $("#ledger").on("click", function() {

        console.log("yes");
        var url = "{{ url('select-supplier-for-ledger') }}";
        viewModal(url);

    })



    $("#get_cousting_report_view").on("click", function() {

        console.log("yes");
        var url = "{{ url('get-cousting-report-view') }}";
        viewLargeModel(url);
    
    })



    $('.select2').select2();


    $("#search_type").on("change", function() {

        var search_type = $(this).val();
        if (search_type == "Supplier") {
            $("#search_supplier_name_div").removeClass("d-none");
            $("#search_expense_div").addClass("d-none");

            $("#search_supplier_name").html("");
            $.ajax({
                url: "{{ url('buyer-purchaser-list') }}",
                type: "GET",
                cache: true,
                success: function(data) {

                    const selectElement = $('#search_supplier_name');
                    selectElement.append('<option value="">Select Suppliers/Expense</option>');
                    $.each(data, function(index, option) {
                        selectElement.append('<option value="' + option["id"] + '">' +
                            option["name"] +
                            '</option>');

                    });

                    selectElement.removeAttr("disabled");

                }
            })

        } else if (search_type == "Expense") {
            $("#search_expense_div").removeClass("d-none");
            $("#search_supplier_name_div").addClass("d-none");
        }

    })


    function getSupplierDataHeader() {


        var search_type = $("#search_type").val();
        var select_duration = $("#select_duration").val();
        var search_supplier_id = $("#search_supplier_name").val();
        var get_supplier_name = $("#search_supplier_name").find("option:selected").text();
        var expense_name = $("#search_expense").val();



        if (search_type !== "" && search_type == "Supplier" && select_duration !== "" && search_supplier_id !==
            "") {



            var url = "{{ url('get-supplier-record-from-header') }}" + "/" + select_duration + "/" +
                search_supplier_id + "/" + get_supplier_name;
            viewLargeModel(url);


        } else if (search_type !== "" && search_type == "Expense" && select_duration !== "") {

            var url = "{{ url('get-expense-ledger-from-header') }}" + "/" + select_duration + "/" +
                expense_name;
            viewLargeModel(url);

        }


    }







    $(document).on("click", "#edit_expense", function() {
        var id = $(this).data('id');
        // Trigger click event on all elements with class "close"
        $(".close").click();
        $("#add-supplier-expense-record").click();
        window.location.href = "{{ url('transaction-form') }}" + "/" + "Expense" + "/" + id;
    });
</script>

@yield('script')
