<style>
    #item_rate_list_filter_view_table{
        border:1px solid rgb(204 204 204);
        width: 100%;
        border-collapse: collapse;
    }

    #item_rate_list_filter_view_table th, #item_rate_list_filter_view_table td{
        border:1px solid rgb(204 204 204);
        padding: 4px;
        text-align: center;
    }


</style>

<table id="item_rate_list_filter_view_table">
    <thead>
        <tr>
            <th>Sr#</th>
            <th>Item Name</th>
            <th>Date</th>
            <th>Rate</th>
        </tr>
    </thead>

    @php
        $sr=1;
    @endphp

    <tbody>
        @foreach ($filter_rate_list as $data)
                <tr>
                    <td>
                        {{$sr++}}
                    </td>
                    <td>
                        {{$data->getItemName->name}}
                    </td>
                    <td>
                        {{date_format(date_create($data->created_at), "d-m-Y")}}
                    </td>
                    <td>
                        {{$data->old_rate}}
                    </td>
                </tr>
        @endforeach
    </tbody>
</table>