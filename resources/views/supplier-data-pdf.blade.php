<style>
    #supplier_ledger_pdf th {
        text-align: left;
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #supplier_ledger_pdf td {
        border: 1px solid rgb(203, 200, 200);
        padding: 3px;
    }

    #supplier_ledger_pdf {
        border-collapse: collapse;
        width: 100%;
    }

    h4,h3{
        padding: 0;
        margin: 0;
        text-align: center;
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

@endphp


<h3>{{ $name }} Ledger</h3>
<h4 style="margin-bottom:5px;"> ({{ date_format(date_create($from_date),"d-m-y") }} to {{ date_format(date_create($to_date),"d-m-y") }}) </h4>
<table id="supplier_ledger_pdf">
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
            
        </tr>
    </thead>

    <tbody id="supplier_table">
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>Prev.&nbsp;Total: {{ $supplier_opening_get + $older_supplier_amount_payable }}</th>
        <th>Prev.&nbsp;Payable: {{ $older_supplier_amount_recieved }}</th>
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
                <td>{{ $get_difference + $supplier_opening_get + $older_supplier_amount_payable - $older_supplier_amount_recieved }}</td>
                <td>{{ isset($get_data['remarks']) ? $get_data['remarks'] : '-' }}</td>
             
        
            </tr>
        @endforeach



        <tr>
            <td colspan="8" id="set_in_amount">
                <b>Amount (Total Payable) : {{ number_format($total_in_amount + $supplier_opening_get + $older_supplier_amount_payable) }}</b>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="8" id="set_out_amount">
                <b>Amount (Total Recieved) :
                    {{ number_format($total_out_amount + $older_supplier_amount_recieved) }}</b>
            </td>
        </tr> --}}

        <tr>
            <td colspan="8" id="remaining">
                <b style="color:#17a2b8;">Remaining:
                    {{ number_format($total_in_amount + $supplier_opening_get +$older_supplier_amount_payable - ($total_out_amount + $older_supplier_amount_recieved)) }}</b>
            </td>
        </tr>

    </tbody>



</table>

    