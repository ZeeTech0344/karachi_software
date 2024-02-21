<style>
    #supplier_ledger_view th {
        text-align: left;
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #supplier_ledger_view td {
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #supplier_ledger_view {
        border-collapse: collapse;
        width: 100%;
    }
</style>




@php

    $total_in_amount = 0;
    $total_out_amount = 0;
    $get_difference = 0;

    $create_grand_array = array_merge($recieved_amount, $supplier_data);

    usort($create_grand_array, function ($a, $b) {
        return strtotime($a['created_at']) - strtotime($b['created_at']);
    });


    $total_opening=  $supplier_opening_get + $older_supplier_amount_payable;

@endphp
<div class=" p-2 d-flex justify-content-end">

    <input type="text" id="search_supplier_data" name="search" placeholder="Search......."
        class="form-control w-25">

</div>
<table id="supplier_ledger_view">
    <thead>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>quantity</th>
            <th>Rate</th>
            <th>Total</th>
            <th>Payable</th>
            <th>Diff.</th>
            <th>Remarks</th>
            <th>Edit</th>
            <th>Delete</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="supplier_table">


        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>Prev.&nbsp;Total: {{  $total_opening }}</th>
        <th>Prev.&nbsp;Payable: {{ $older_supplier_amount_recieved }}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        @foreach ($create_grand_array as $get_data)
            <tr>
                <td>{{ date_format(date_create($get_data['created_at']), 'd-m-Y') }}</td>

                @php
                    $total = isset($get_data['head']) ? $get_data['head'] : 'Paid';
                @endphp

                <td>
                    @php
                        if ($total == 'Paid') {
                            echo '<b style="color:green;">' . $total . '</b>';
                        } else {
                            echo $total;
                        }
                    @endphp
                </td>
                <td>
                    {{ isset($get_data['quantity']) ? $get_data['quantity'] : '-' }}
                </td>
                <td>
                    {{ isset($get_data['amount']) ? $get_data['amount'] : '-' }}
                </td>
                <td class="amount_in">{{ $get_data['amount_status'] == 'In' ? $get_data['total'] : '-' }}
                </td>
                <td>
                    {{ $get_data['amount_status'] == 'Out' ? $get_data['amount'] : '-' }}
                </td>

                @php

                    if ($get_data['amount_status'] == 'In') {
                        $get_difference = $get_difference + $get_data['total'];
                        $total_in_amount = $total_in_amount + $get_data['total'];
                    } elseif ($get_data['amount_status'] == 'Out') {
                        $get_difference = $get_difference - $get_data['amount'];
                        $total_out_amount = $total_out_amount + $get_data['amount'];
                    }

                @endphp

                {{-- this is value that get old amount (Grand final old amount) --}}
                <td>{{ $get_difference + $total_opening - $older_supplier_amount_recieved }}</td>
                <td>{{ isset($get_data['remarks']) ? $get_data['remarks'] : '-' }}</td>
                
                 @if ($get_data['amount_status'] == 'In')
                <td class="d-none">Supplier_Payable</td>
                 @elseif ($get_data['amount_status'] == 'Out')
                  <td class="d-none">Supplier_Recieved</td>
                   @endif
                <td class="d-none">{{ $get_data['id'] }}</td>
                
                <td style="text-align: center;"><button class="edit-btn btn-sm btn-warning">Edit</button></td>
                
                

                @if ($get_data['amount_status'] == 'In')
                <td style="text-align: center;"><button class="delete-supplier-data-btn btn-sm btn-danger" data-id="{{$get_data['id'].',Supplier_Payable'}}" >Delete</button></td>
                    
                @elseif ($get_data['amount_status'] == 'Out')
                <td style="text-align: center;"><button class="delete-supplier-data-btn btn-sm btn-danger" data-id={{ $get_data['id'].',Supplier_Recieved' }}>Delete</button></td>
                @endif
              
                @if($get_data['amount_status'] == 'In')
                <td style="text-align: center;"><input type="checkbox" name="supplier_data_status" @php if( isset( $get_data['status']) && $get_data['status'] == 1 ) { echo "checked"; } @endphp  class="supplier_data_status" value={{ $get_data['id'] }}  ></td>
                @else
                <td style="text-align: center;"><b style="color:green;">Paid</b></td>
                @endif
            </tr>
        @endforeach



        <tr>
            <td colspan="11" id="set_in_amount">
                <b>Amount (Total Payable) : {{ number_format($total_in_amount + $total_opening) }}</b>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="11" id="set_out_amount">
                <b>Amount (Total Recieved) :
                    {{ number_format($total_out_amount + $older_supplier_amount_recieved) }}</b>
            </td>
        </tr> --}}

        <tr>
            <td colspan="11" id="remaining">
                <b style="color:#17a2b8;">Remaining:
                    {{ number_format($total_in_amount + $total_opening - ($total_out_amount + $older_supplier_amount_recieved)) }}</b>
            </td>
        </tr>

    </tbody>



</table>
<script>
    // $("#search_supplier_data").keyup(function () {

    // var value = this.value.toLowerCase().trim();

    // $("#supplier_ledger_view tr").each(function (index) {
    //     if (!index) return;
    //     $(this).find("td").each(function () {
    //         var id = $(this).text().toLowerCase().trim();
    //         var not_found = (id.indexOf(value) == -1);
    //         $(this).closest('tr').toggle(!not_found);
    //         return not_found;

    //     });
    // });

    // });

    $(".supplier_data_status").on("click", function(){
    // Use $(this) to refer to the clicked checkbox

    var supplier_data_id = $(this).val();
    var send_delivery_status = $(this).prop('checked') ? 1 : 0;

    $.ajax({
        headers: {
                        'X-CSRF-Token': csrfToken
                    },
        url:"{{ url('update-supplier-status') }}",
        type:"POST",
        data:{supplier_data_id:supplier_data_id,
            send_delivery_status:send_delivery_status},
        success:function(data){

                console.log(data);
        }
    })

   
    });



    $('#supplier_ledger_view').on('click', '.edit-btn', function() {

        var row = $(this).closest('tr');
        var date = row.find('td:eq(0)').text().trim();
        var head = row.find('td:eq(1)').text().trim();
        var quantity = row.find('td:eq(2)').text().trim();
        var amount = row.find('td:eq(3)').text().trim();
        var remarks = row.find('td:eq(7)').text().trim();
        var supplier_transaction_type = row.find('td:eq(8)').text().trim();
        var hidden_id = row.find('td:eq(9)').text().trim();


        // Replace the current row content with input fields
        row.html('<td><input style="width:100%;" type="text" disabled value="' + escapeHtml(date) +
            '"></td> <td><input style="width:100%;" type="text" value="' + escapeHtml(head) +
            '"></td> <td><input style="width:100%;" type="text" value="' + escapeHtml(quantity) +
            '"></td> <td><input style="width:100%;" type="text" value="' + escapeHtml(amount) +
            '"></td> <td></td> <td></td> <td></td> <td><input style="width:100%;" type="text" value="' +
            remarks +
            '"></td> <td><button class="save-btn btn btn-success save-data">Save</button></td> <td><input type="text" value="' +
            supplier_transaction_type +
            '"></td> <td><input style="width:100%;" type="text" value="' + escapeHtml(
                hidden_id) +
            '"></td>');
    });

    // Function to escape HTML special characters
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return text.replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    }


    // Save button click event
    $('#supplier_ledger_view').on('click', '.save-data', function() {
        var row = $(this).closest('tr');


        var date = row.find('input:eq(0)').val();
        var head = row.find('input:eq(1)').val();
        var quantity = row.find('input:eq(2)').val();
        var amount = row.find('input:eq(3)').val();
        var remarks = row.find('input:eq(4)').val();
        var supplier_transaction_type = row.find('input:eq(5)').val();
        var hidden_id = row.find('input:eq(6)').val();


        // Replace the current row content with input fields
        row.html('<td>' + date + '</td> <td>' + head + '</td> <td>' + quantity + '</td> <td>' + amount +
            '</td> <td></td> <td></td> <td></td> <td>' + remarks +
            '</td> <td class="d-none">' +
            supplier_transaction_type + '</td><td class="d-none">' + hidden_id + '</td><td style="text-align:center;"><button class="edit-btn btn btn-warning">Edit</button></td>');




        var create_data = {
            head: head,
            quantity: quantity,
            amount: amount,
            remarks: remarks,
            supplier_transaction_type: supplier_transaction_type,
            hidden_id: hidden_id,
        };

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('update-supplier-data') }}",
            type: "POST",
            data: create_data,
            success: function(data) {

                toastr.success('Supplier Data Update Successfully!');
            }

        })
        
        
        


    });
    
    
   
        
    


    $("#search_supplier_data").keyup(function() {

        var value = this.value.toLowerCase().trim();

        $("#supplier_table tr").each(function(index) {
            if (!index) return;
            $(this).find("td").each(function() {
                var id = $(this).text().toLowerCase().trim();
                var not_found = (id.indexOf(value) == -1);
                $(this).closest('tr').toggle(!not_found);
                return not_found;

            });
        });

    });


    
    $(".delete-supplier-data-btn").on("click", function(){

        var confirm_delete = confirm("Are you sure you want to delete!");

        if(confirm_delete){

            var delete_id_data = $(this).data("id");

        var myArray = delete_id_data.split(',');

        // Display the result in the console
        supplier_data_id = myArray[0];
        supplier_status = myArray[1];

            var element = this;

            $.ajax({
                headers: {
                'X-CSRF-Token': csrfToken
            },
                url: "{{ url('delete-supplier-data') }}",
                type: "POST",
                data: {
                    id: supplier_data_id,
                    status : supplier_status
                },
                success: function(data) {
                    $(element).parent().parent().fadeOut()
                }

            })

        }

      

    })
    






</script>
