<?php

namespace App\Http\Controllers;

use App\Models\addExpense;
use App\Models\AmountRecieved;
use App\Models\BuyerPurchaserDetail;
use App\Models\ItemData;
use App\Models\ItemRate;
use App\Models\Items;
use App\Models\RecievedSupplierAmount;
use App\Models\supplierData;
use App\Models\User;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{

    function rateListFilterView(Request $req, $item_id, $from_date, $to_date){

  
        if ($req->ajax()) {

            $item_id = $item_id;
            $from_date = $from_date;
            $to_date = $to_date;
    
            $filter_rate_list = ItemData::with("getItemName")->where("item_id", $item_id)->whereDate("created_at", ">=" ,$from_date)->whereDate("created_at", "<=", $to_date)->get();
    
            $html = [];
            $html["title"] = "Rate List";
            $html["view"] = view("rate-list-filter-view", compact("filter_rate_list"))->render();
            return response()->json($html, 200);
        }

        
    }

    function rateListFilter(){

        $items = Items::where("status", true)->get();
          return view("rate-list-filter", compact("items"));
    }


    function deleteItemData(Request $req){

        if($req->ajax()){

            $delete_item = ItemData::find($req->id);
            $delete_item->delete();
            return response()->json("deleted", 200);
        }

    }

    function editInvoiceOrItem(Request $req, $invoice_no)
    {



        $invoice_data = ItemData::with("getItemName")->where("invoice_no",  $invoice_no)->get();

        $items = Items::where("status", true)->get();
        return view("item-rate-form", compact("items", "invoice_data"));
    }

    function getCoustingReportView(Request $req)
    {
        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Item List";
            $html["view"] = view("get-cousting-report-view")->render();
            return response()->json($html, 200);
        }
    }

    function getCoustingReport(Request $req)
    {

        $sum = 0;
        if ($req->ajax()) {

            $query = ItemData::query();


            if ($req->search_item_data && $req->from_date && $req->to_date) {
                $query->whereHas('getItemName', function ($query) use ($req) {
                    $query->where('name', 'like', '%' . $req->search_item_data . '%');
                })->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->from_date);
            } elseif ($req->from_date && $req->to_date) {
                $query->with('getItemName:id,name')->whereDate("created_at", ">=", $req->from_date)
                    ->whereDate("created_at", "<=", $req->from_date);
            }
            // Apply search filters if provided
            elseif ($req->search_item_data) {
                // Eager load getItemName relationship and filter main query results
                $query->whereHas('getItemName', function ($query) use ($req) {
                    $query->where('name', 'like', '%' . $req->search_item_data . '%');
                });
            } else {
                // Eager load getItemName relationship without filtering main query results
                $query->with('getItemName:id,name');
            }

            // Get the total count before applying pagination
            $total_count = $query->count();

            $total_amount = $query->sum("total");

            // Apply pagination and ordering
            $data = $query->orderBy("id", "desc")
                ->offset($req->start)
                ->limit(10);

            return DataTables::of($data)
                ->addColumn('invoice_no', function ($row) {
                    return $row->invoice_no;
                })
                ->addColumn('item_name', function ($row) {
                    return $row->getItemName->name;
                })
                ->addColumn('old_rate', function ($row) {
                    return "<p class='text-center d-block'>" . $row->old_rate . "</p>";
                })
                ->addColumn('qty_or_length', function ($row) {
                    return "<p class='text-center d-block'>" . $row->qty_or_length . setScale($row->scale) . "</p>";
                })
                ->addColumn('total', function ($row) {
                    return "<p class='text-center d-block total_amount_calculate'>" . $row->total . "</p>";
                })
                ->addColumn('total_amount', function ($row) use ($total_amount) {
                    return  $total_amount;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center"><div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_item_data dropdown-item" data-id="' . $row->id . '" href="' . url('edit-invoice-or-item') . "/" . $row->invoice_no . '">Edit</a>
                        <a class="delete_item_data dropdown-item" data-id="' . $row->id . '" href="#">Delete</a>
                    </div>
                    </div></div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'old_rate', 'qty_or_length', 'total'])
                ->make(true);
        }
    }

    function insertItemData(Request $req)
    {

        $data = $req->all();

        $with_hidden_id = [];
        $without_hidden_id = [];


        if ($req->invoice_no) {

            $invoice_no =  $req->invoice_no;

        } else {
            $itemData = ItemData::latest('invoice_no')->first();
            if ($itemData) {
                $invoice_no = $itemData->invoice_no + 1;
            } else {
                $invoice_no = 1000;
            }
        }


        foreach ($data["item_data"]  as $data) {
            if ($data["hidden_id"] == "") {

                unset($data["hidden_id"]);
                unset($data["item_name"]);
                $data["invoice_no"] = $invoice_no;
                $data["created_at"] = Carbon::now();
                $data["updated_at"] = Carbon::now();
                $without_hidden_id[] = $data;
            } else {
                $with_hidden_id[] = $data;
            }

            ItemData::insert($without_hidden_id);

            $update_array = $with_hidden_id;
            if (count($with_hidden_id) > 0) {

                $updateQuery = 'UPDATE item_data SET ' . implode(', ', array_map(function ($data) {
                    return 'item_id = CASE WHEN id = ' . $data['hidden_id'] . ' THEN "' . $data['item_id'] . '" ELSE item_id END, ' .
                        'old_rate = CASE WHEN id = ' . $data['hidden_id'] . ' THEN "' . $data['old_rate'] . '" ELSE old_rate END, ' .
                        'qty_or_length = CASE WHEN id = ' . $data['hidden_id'] . ' THEN "' . $data['qty_or_length'] . '" ELSE qty_or_length END, ' .
                        'scale = CASE WHEN id = ' . $data['hidden_id'] . ' THEN "' . $data['scale'] . '" ELSE scale END, ' .
                        'total = CASE WHEN id = ' . $data['hidden_id'] . ' THEN "' . $data['total'] . '" ELSE total END';
                }, $update_array));

                $updateQuery .= ' WHERE id IN (' . implode(',', array_column($update_array, 'hidden_id')) . ')';

                DB::statement($updateQuery);
            }
        }




        return response()->json("saved", 200);
    }




    function itemRateListView(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Rate List";
            $html["view"] = view("item-rate-list-view")->render();
            return response()->json($html, 200);
        }
    }

    function getItemRate(Request $req)
    {

        if ($req->ajax()) {

            $item_rate = ItemRate::where("item_id", $req->item_id)->latest()->first();
            return response()->json($item_rate, 200);
        }
    }


    function itemRateList(Request $req)
    {

        if ($req->ajax()) {

            $query = ItemRate::query();

            if ($req->search) {
                $query->with(['getItemName' => function ($query) use ($req) {
                    $query->select('id', 'name')->where('name', 'like', '%' . $req->search_item_rate . '%');
                }]);
            }

            $data = $query->when($req->search_item_rate, function ($query) use ($req) {
                $query->whereHas('getItemName', function ($query) use ($req) {
                    $query->where('name', 'like', '%' . $req->search_item_rate . '%');
                });
            })
                ->when(!$req->search_item_rate, function ($query) {
                    $query->with('getItemName:id,name');
                })
                ->orderBy("id", "desc")
                ->offset($req->start)
                ->limit(10);


            // If total count is required
            $total_count = $data->count();


            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->getItemName->name;
                })
                ->addColumn('rate', function ($row) {
                    return '<label class="text-center d-block">' . $row->rate . '</label>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center"><div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                        <a class="view_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">View</a>
                        
                    </div>
                    </div></div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'rate'])
                ->make(true);
        }
    }

    function insertItemRate(Request $req)
    {

        $item = new ItemRate();
        $item->item_id = $req->item_id;
        $item->rate = $req->rate;
        $item->save();
        return response()->json("saved", 200);
    }



    function itemRateForm(Request $req)
    {


        $items = Items::where("status", true)->get();
        return view("item-rate-form", compact("items"));
    }

    function insertItems(Request $req)
    {

        $item = new Items();
        $item->name = $req->name;
        $item->save();
        return response()->json("saved", 200);
    }


    function createItems()
    {

        return view("create-items-form");
    }


    function home()
    {

        return view("index");
    }

    function logout()
    {



        Auth::logout();

        // If you want to redirect after logout
        return redirect('/'); // Redirect to the desired URL


    }

    function updateSupplierStatus(Request $req)
    {

        $data = $req->all();
        $send_delivery_status = $data["send_delivery_status"];
        $supplier_data_id = $data["supplier_data_id"];

        $supplier_data = supplierData::find($supplier_data_id);
        $supplier_data->status = $send_delivery_status;
        $supplier_data->save();
        return response()->json("saved", 200);
    }

    function getExpenseLedger(Request $req, $from_date, $to_date)
    {

        if ($req->ajax()) {

            $expenses = addExpense::whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get()->toArray();

            $expense_data = json_decode(json_encode($expenses), true);


            $html = [];
            $html["title"] = "Expense Ledger From <b>(" . date_format(date_create($from_date), "d-M-Y") . " to " . date_format(date_create($to_date), "d-M-Y") . ")</b>";
            $html["view"] = view("expense-ledger-view", compact("expense_data"))->render();
            return response()->json($html, 200);
        }
    }


    function getSupplierRecord(Request $req, $from_date, $to_date, $supplier_id, $supplier_name)
    {


        if ($req->ajax()) {


            $supplier_opening_get = BuyerPurchaserDetail::where('id', $supplier_id)->sum('opening_amount');


            $older_supplier_amount_recieved  = RecievedSupplierAmount::where("supplier_id", $supplier_id)
                ->whereDate("created_at", "<", $from_date)
                ->sum('amount');

            $older_supplier_amount_payable = supplierData::where("supplier_id", $supplier_id)
                ->whereDate("created_at", "<", $from_date)
                ->sum('total');



            $received_amount_data = RecievedSupplierAmount::where("supplier_id", $supplier_id)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

            $recieved_amount = json_decode(json_encode($received_amount_data), true);

            $supplier_data_get = supplierData::where("supplier_id", $supplier_id)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get()->toArray();

            $supplier_data = json_decode(json_encode($supplier_data_get), true);


            $html = [];
            $html["title"] = "Supplier Ledger (" . $supplier_name . ") From <b>(" . date_format(date_create($from_date), "d-M-Y") . " to " . date_format(date_create($to_date), "d-M-Y") . ")</b>";
            $html["view"] = view("supplier-ledger-view", compact("recieved_amount", "supplier_data", "older_supplier_amount_recieved", "older_supplier_amount_payable", "supplier_opening_get"))->render();
            return response()->json($html, 200);
        }
    }

    function getSupplierRecordFromHeader(Request $req, $duration, $supplier_id, $supplier_name)
    {

        if ($req->ajax()) {

            $from_date = date('Y-m-d', strtotime($duration));
            $to_date = date("Y-m-d");

            if ($duration == 'first day of current month') {
                $from_date = date("Y-m-01");
            }

            if ($duration == 'first day of last month') {

                $to_date = date('Y-m-d', strtotime("last day of last month"));
            }


            $supplier_opening_get = BuyerPurchaserDetail::where('id', $supplier_id)->sum('opening_amount');

            $older_supplier_amount_recieved  = RecievedSupplierAmount::where("supplier_id", $supplier_id)
                ->whereDate("created_at", "<", $from_date)
                ->sum('amount');

            $older_supplier_amount_payable = supplierData::where("supplier_id", $supplier_id)
                ->whereDate("created_at", "<", $from_date)
                ->sum('total');



            $received_amount_data = RecievedSupplierAmount::where("supplier_id", $supplier_id)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get();

            $recieved_amount = json_decode(json_encode($received_amount_data), true);

            $supplier_data_get = supplierData::where("supplier_id", $supplier_id)
                ->whereDate("created_at", ">=", $from_date)
                ->whereDate("created_at", "<=", $to_date)
                ->get()->toArray();

            $supplier_data = json_decode(json_encode($supplier_data_get), true);


            $html = [];
            $html["title"] = "Supplier Ledger (" . $supplier_name . ") From <b>(" . date_format(date_create($from_date), "d-M-Y") . " to " . date_format(date_create($to_date), "d-M-Y") . ")</b>";
            $html["view"] = view("supplier-ledger-view", compact("supplier_opening_get", "recieved_amount", "supplier_data", "older_supplier_amount_recieved", "older_supplier_amount_payable"))->render();
            return response()->json($html, 200);
        }
    }


    function deleteSupplierData(Request $req)
    {

        if ($req->ajax()) {


            if ($req->status == "Supplier_Payable") {

                $supplier_data = supplierData::find($req->id);
                $supplier_data->delete();
            } elseif ($req->status == "Supplier_Recieved") {
                $supplier_data = RecievedSupplierAmount::find($req->id);
                $supplier_data->delete();
            }
        }
    }


    function getExpenseFromHeader(Request $req, $duration, $expense_name = null)
    {

        if ($req->ajax()) {

            $from_date = date('Y-m-d', strtotime($duration));
            $to_date = date("Y-m-d");

            if ($expense_name !== null) {
                $expenses = addExpense::where("head", "like", '%' . $expense_name . '%')
                    ->whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get()->toArray();

                $expense_data = json_decode(json_encode($expenses), true);
            } else {

                $expenses = addExpense::whereDate("created_at", ">=", $from_date)
                    ->whereDate("created_at", "<=", $to_date)
                    ->get()->toArray();

                $expense_data = json_decode(json_encode($expenses), true);
            }


            echo "yes";
            $html = [];
            $html["title"] = "Expense Ledger From <b>(" . date_format(date_create($from_date), "d-M-Y") . " to " . date_format(date_create($to_date), "d-M-Y") . ")</b>";
            $html["view"] = view("expense-ledger-view", compact("expense_data"))->render();
            return response()->json($html, 200);
        }
    }


    function deleteSupplierRecord(Request $req)
    {

        $post = BuyerPurchaserDetail::find($req->id);
        $post->getSupplierData()->delete();
        $post->delete();
        return response()->json("deleted", 200);
    }


    function supplierDataPdf(Request $req)
    {


        $name = $req->name;
        $from_date = $req->from_date;
        $to_date = $req->to_date;
        $supplier_id = $req->supplier_id_get;


        $supplier_opening_get = BuyerPurchaserDetail::where('id', $supplier_id)->sum('opening_amount');



        $older_supplier_amount_recieved  = RecievedSupplierAmount::where("supplier_id", $supplier_id)
            ->whereDate("created_at", "<", $from_date)
            ->sum('amount');

        $older_supplier_amount_payable = supplierData::where("supplier_id", $supplier_id)
            ->whereDate("created_at", "<", $from_date)
            ->sum('total');



        $received_amount_data = RecievedSupplierAmount::where("supplier_id", $supplier_id)
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->get();

        $recieved_amount = json_decode(json_encode($received_amount_data), true);

        $supplier_data_get = supplierData::where("supplier_id", $supplier_id)
            ->whereDate("created_at", ">=", $from_date)
            ->whereDate("created_at", "<=", $to_date)
            ->get()->toArray();

        $supplier_data = json_decode(json_encode($supplier_data_get), true);



        // Your code to generate the PDF
        $pdf = PDF::loadView("supplier-data-pdf", compact("supplier_opening_get", "name", "from_date", "to_date", "recieved_amount", "supplier_data", "older_supplier_amount_recieved", "older_supplier_amount_payable"));
        $file = $pdf->download('supplier_pdf.pdf');

        // Return the file with appropriate headers
        return response()->json([base64_encode($file)], 200);
    }




    function selectSupplierForLedger(Request $req)
    {

        if ($req->ajax()) {
            $html = [];
            $html["title"] = "Supplier Ledger";
            $html["view"] = view("select-supplier-for-ledger")->render();
            return response()->json($html, 200);
        }
    }


    function insertExpense(Request $req)
    {
        if ($req->hidden_id) {
            $expense = addExpense::find($req->hidden_id);
        } else {
            $expense = new addExpense();
        }

        $expense->head = $req->head;
        $expense->amount = $req->amount;
        $expense->save();
        return response()->json("saved", 200);
    }


    function supplierAmountRecieved(Request $req)
    {

        $supplierAmountRecieved = new RecievedSupplierAmount();
        $supplierAmountRecieved->supplier_id = $req->buyer_purchaser_id;
        $supplierAmountRecieved->amount = $req->amount;
        $supplierAmountRecieved->remarks = $req->remarks;
        $supplierAmountRecieved->save();
        return response()->json("saved", 200);
    }

    function insertSupplierData(Request $req)
    {

        $data = $req->all();

        // $date = $req->date;
        $supplier_id = $req->supplier_id;
        $created_at = Carbon::now();
        $updated_at = Carbon::now();



        foreach ($data["supplier_data"] as $key => $get_data) {
            // $data["supplier_data"][$key]["date"] = $date;
            $data["supplier_data"][$key]["supplier_id"] = $supplier_id;
            $data["supplier_data"][$key]["created_at"] = $created_at;
            $data["supplier_data"][$key]["updated_at"] = $updated_at;
        }

        $final_array_insert =  $data["supplier_data"];



        supplierData::insert($final_array_insert);
        return response()->json("saved");
    }


    function updateSupplierData(Request $req)
    {

        if ($req->hidden_id && $req->supplier_transaction_type == "Supplier_Payable") {
            $supplierPayable = supplierData::find($req->hidden_id);
            $supplierPayable->head = $req->head;
            $supplierPayable->quantity = $req->quantity;
            $supplierPayable->amount = $req->amount;
            $supplierPayable->total = $req->quantity * $req->amount;
            $supplierPayable->remarks = $req->remarks;
            $supplierPayable->save();
            return response()->json("saved", 200);
        } elseif ($req->hidden_id && $req->supplier_transaction_type == "Supplier_Recieved") {

            $supplierRecieved = RecievedSupplierAmount::find($req->hidden_id);
            $supplierRecieved->amount = $req->amount;
            $supplierRecieved->remarks = $req->remarks;
            $supplierRecieved->save();
            return response()->json("saved", 200);
        }
    }


    function transactionFormNew(Request $req)
    {

        return view("transaction-form-new");
    }

    function index(Request $req)
    {

        return Inertia::location('/home');
    }




    function getSupplierList(Request $req)
    {

        if ($req->ajax()) {

            if ($req->search) {
                $query = BuyerPurchaserDetail::where("name", "like", '%' . $req->search . '%');
            } else {
                $query = BuyerPurchaserDetail::query();
            }

            $total_count = $query->count();

            $data = $query->offset($req->start)
                ->limit(10)
                ->orderBy("id", "desc")
                ->get();

            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->phone_no;
                })
                ->addColumn('status', function ($row) {
                    $statusClass = $row->status == "On" ? 'btn-success' : 'btn-danger';
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="update_status_buyer_purchaser_detail btn-block btn btn-sm ' . $statusClass . '">' . $row->status . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                        <a class="view_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">View</a>
                        
                    </div>
                    </div>';
                    return $btn;
                })
                ->setFilteredRecords($total_count)
                ->setTotalRecords($total_count) // Use the total count directly instead of counting again
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }


    function insertBuyerPurchaserRecord(Request $req)
    {

        $validation = [
            'name' => 'required',
        ];

        // if ($req->has('hidden_buyer_purchaser_id')) {
        //     $validation['phone_no'] = [
        //         'required',
        //         Rule::unique('buyer_purchaser_details', 'phone_no')->ignore($req->hidden_buyer_purchaser_id),
        //     ];
        // } else {
        //     $validation['phone_no'] = 'required|unique:buyer_purchaser_details,phone_no';
        // }


        $validator = Validator::make($req->all(), $validation);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if ($req->hidden_buyer_purchaser_id) {
            $buyer_purchaser = BuyerPurchaserDetail::find($req->hidden_buyer_purchaser_id);
        } else {
            $buyer_purchaser = new BuyerPurchaserDetail();
        }
        $buyer_purchaser->name = $req->name;
        $buyer_purchaser->phone_no = $req->phone_no;
        $buyer_purchaser->account_no = $req->account_no;
        $buyer_purchaser->cnic = $req->cnic;
        $buyer_purchaser->address = $req->address;
        $buyer_purchaser->opening_amount = $req->opening_amount;
        $buyer_purchaser->save();
        return response()->json("saved", 200);
    }


    function insertBuyerPurchaserRecordList(Request $req)
    {



        if ($req->ajax()) {
            $data = BuyerPurchaserDetail::select('*');
            return DataTables::of($data)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('account_no', function ($row) {
                    return $row->account_no;
                })
                ->addColumn('status', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="update_status_buyer_purchaser_detail btn-block btn btn-sm ' . ($row->status == "On" ? 'btn-success' : 'btn-danger') . '">' . $row->status . '</a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {

                    $btn = '<div class="dropdown">
                    <button class="btn btn-sm btn-block btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         <a class="edit_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Edit</a>
                        <a class="view_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">View</a>
                        <a class="delete_buyer_purchaser_detail dropdown-item" data-id="' . $row->id . '" href="#">Delete</a>
                    </div>
                    </div>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }


    function buyerPurchaserRecordStatusUpdate(Request $req)
    {

        $id = $req->id;
        $buyer_purchaser_record = BuyerPurchaserDetail::find($id);
        return response()->json($buyer_purchaser_record, 200);
    }


    function updateStatusBuyerPurchaserDetail(Request $req)
    {

        $id = $req->id;
        $buyer_purchaser_record = BuyerPurchaserDetail::find($id);
        $buyer_purchaser_record->status == "On" ? $buyer_purchaser_record->status = "Off" : $buyer_purchaser_record->status = "On";
        $buyer_purchaser_record->save();
        return response()->json("update", 200);
    }


    function transactionForm(Request $req, $type = null, $id = null)
    {


        if ($id == null && $type == null) {
            return view("transaction-form");
        } else {

            if ($type == "Expense") {
                $data = addExpense::find($id);
            } else if ($type == "Supplier") {
                $data = supplierData::find($id);
            }

            return view("transaction-form", compact("data", "type"));
        }
    }

    function buyerPurchaserList(Request $req)
    {

        $data = BuyerPurchaserDetail::where("status", "On")->get();

        return response()->json($data, 200);
    }

    function supplierInfoView(Request $req, $id)
    {

        if ($req->ajax()) {

            $supplier = BuyerPurchaserDetail::find($id);
            $html = [];
            $html["title"] = "Supplier";
            $html["view"] = view("supplier-info-view", compact("supplier"))->render();
            return response()->json($html, 200);
        }
    }
}
