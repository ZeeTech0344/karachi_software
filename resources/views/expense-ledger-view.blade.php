<style>
    #editableTable th {
        text-align: left;
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #editableTable td {
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #editableTable {
        border-collapse: collapse;
        width: 100%;
    }
</style>

@php

    $total_expense_amount = 0;

@endphp

<div class=" p-2 d-flex justify-content-end">

    <input type="text" id="search_expense_result" name="search_expense_result" placeholder="Search Expense......."
        class="form-control w-25">

</div>

{{-- <table id="editableTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($expense_data as $expense)
            <tr>
                <td>{{ date_format(date_create($expense['created_at']), 'd-m-Y') }}</td>
                <td>{{ $expense['head'] }}</td>
                <td>{{ $expense['amount'] }}</td>
                <td><button class="edit-btn btn btn-warning">Edit</button></td>
            </tr>

            @php
                $total_expense_amount = $total_expense_amount + $expense['amount'];
            @endphp
        @endforeach
        <tr>
            <td colspan="4"><b style="color:#17a2b8;">Total Amount: {{ number_format($total_expense_amount) }}<b>
            </td>
        </tr>
    </tbody>
</table> --}}




<table id="editableTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Head</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="expense_table">
        @foreach ($expense_data as $expense)
            <tr>
                <td>{{ date_format(date_create($expense['created_at']), 'd-m-Y') }}</td>
                <td>{{ $expense['head'] }}</td>
                <td>{{ $expense['amount'] }}</td>
                <td class="d-none">{{ $expense['id'] }}</td>
                <td style="text-align: center;"><button class="edit-btn btn btn-warning">Edit</button></td>

            </tr>

            @php
                $total_expense_amount = $total_expense_amount + $expense['amount'];
            @endphp
        @endforeach
    </tbody>
</table>



<script>


    // $('#editableTable').on('click', '.edit-btn', function() {
    //     var row = $(this).closest('tr');
    //     var date = row.find('td:eq(0)').text();
    //     var head = row.find('td:eq(1)').text(); // Assuming the name is in the first column
    //     var amount = row.find('td:eq(2)').text(); // Assuming the email is in the second column
    //     var hidden_id = row.find('td:eq(3)').text(); // Assuming the email is in the second column

    //     console.log(hidden_id);


    //     // Replace the current row content with input fields
    //     row.html('<td><input type="text" disabled value="' + date + '"></td><td><input type="text" value="' +
    //         head + '"></td><td><input type="text" value="' + amount +
    //         '"></td><td class="d-none"><input type="text" value="' +
    //         hidden_id + '"></td><td><button class="save-btn btn btn-success save-data">Save</button></td>');
    // });

    // // Save button click event
    // $('#editableTable').on('click', '.save-btn', function() {
    //     var row = $(this).closest('tr');

    //     var date = row.find('input:eq(0)').val();
    //     var head = row.find('input:eq(1)').val();
    //     var amount = row.find('input:eq(2)').val();
    //     var hidden_id = row.find('input:eq(3)').val();

    //     console.log(hidden_id);

    //     // Replace input fields with the updated values
    //     row.html('<td>' + date + '</td><td>' + head + '</td><td>' + amount +
    //         '</td><td class="d-none">' + hidden_id +
    //         '</td><td><button class="edit-btn btn btn-warning">Edit</button></td>');


    //     var get_date = row.find('td:eq(0)').text();
    //     var get_head = row.find('td:eq(1)').text(); // Assuming the name is in the first column
    //     var get_amount = row.find('td:eq(2)').text(); // Assuming the email is in the second column
    //     var get_hidden_id = row.find('td:eq(3)').text(); // Assuming the email is in the second column


    //     var create_data = {

    //         data: {
    //             head: head,
    //             amount: amount
    //         },
    //         hidden_id: get_hidden_id

    //     }

    //     $.ajax({
    //         headers: {
    //             'X-CSRF-Token': csrfToken
    //         },
    //         url: "{{ url('insert-expense') }}",
    //         type: "POST",
    //         data:create_data,
    //         success:function(data){

    //             console.log(data);
    //         }

    //     })

    // });




    // Event delegation for both edit and save buttons
    $('#editableTable').on('click', '.edit-btn', function() {
        const row = $(this).closest('tr');
        const [date, head, amount, hidden_id] = row.find('td').map((index, td) => $(td).text());

        row.html(`
        <td><input type="text" disabled value="${date}" autocomplete="off"></td>
        <td><input type="text" value="${head}" autocomplete="off"></td>
        <td><input type="text" value="${amount}" autocomplete="off"></td>
        <td class="d-none"><input type="text" value="${hidden_id}" autocomplete="off"></td>
        <td style="text-align:center;" ><button class="save-btn btn btn-success save-data">Save</button></td>
    `);
    });

    // Event delegation for save button
    $('#editableTable').on('click', '.save-btn', function() {
        const row = $(this).closest('tr');
        const [date, head, amount, hidden_id] = row.find('input').map((index, input) => $(input).val());

        row.html(`
        <td>${date}</td>
        <td>${head}</td>
        <td>${amount}</td>
        <td class="d-none">${hidden_id}</td>
        <td style="text-align:center;" ><button class="edit-btn btn btn-warning">Edit</button></td>
    `);

        const create_data = {
                head: head,
                amount: amount,
                hidden_id: hidden_id
            };
        

        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            url: "{{ url('insert-expense') }}",
            type: "POST",
            data: create_data,
            success: function(data) {
                toastr.success('Expense Update Successfully!');
            }
        });
    });




    $("#search_expense_result").keyup(function () {

    var value = this.value.toLowerCase().trim();

    $("#expense_table tr").each(function (index) {
        if (!index) return;
        $(this).find("td").each(function () {
            var id = $(this).text().toLowerCase().trim();
            var not_found = (id.indexOf(value) == -1);
            $(this).closest('tr').toggle(!not_found);
            return not_found;

        });
    });

    });
</script>
