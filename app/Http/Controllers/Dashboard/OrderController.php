<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Status;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $this->authorize('view_orders');

        if ($request->ajax())
        {
            $data = getModelData( model : new Order() , relations :  [ 'employee' => ['id','name'], 'status' => ['id', 'name_ar', 'name_en'] ]);

            return response()->json($data);
        }

        $statuses = Status::select('id', 'name_en', 'name_ar')->get();

        return view('dashboard.orders.index', compact('statuses'));
    }



    public function show(Order $order)
    {
        $this->authorize('show_orders');
        $statuses = Status::get();
        $order->load(['car', 'city']);

        if(!$order->opened_at) {
            $order->update([
                "opened_at" => Carbon::now()->toDateTimeString(),
                "opened_by" => auth()->id()
            ]);
        }

        return view('dashboard.orders.show',compact('order', 'statuses'));
    }



    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete_orders');

        if($request->ajax())
        {
            $order->delete();
        }
    }

    public function changeStatus(Order $order , Request $request)
    {
        $request->validate(['status_id' => 'required|exists:statuses,id']);

        OrderHistory::create([
            'status_id' => $request['status_id'],
            'comment' => $request['comment'],
            'employee_id' => auth()->id(),
            'order_id' => $order['id'],
        ]);

        $order->update(['status_id' => $request['status_id'] ]);
    }

    public function excel(Request $request)
    {
        $ordersQuery = Order::with('employee', 'status', 'employee','city','bank','car')
                            ->select('id', 'salary', 'name', 'phone', 'created_at', 'price', 'type', 'opened_by', 'created_at', 'opened_at', 'status_id', 'salary_identification', 'commitments', 'work', 'bank_id', 'car_id', 'city_id', 'payment_type', 'city_name', 'car_name')
                            ->orderBy('created_at', 'DESC');
    
        // Apply status filter if provided
        if ($request->has('status')   && $request->status!=null && $request->status != 'all') {
            $ordersQuery->where('status_id', $request->status);
        }
        // Apply date range filter if provided
        if ($request->has('date_range')  &&$request->date_range !=null && $request->date_range) {

            $dateRange = explode(' - ', $request->date_range);
            if (count($dateRange) == 2) {
                $startDate = Carbon::parse($dateRange[0])->startOfDay(); // Set time to 00:00:00 on the first day
                $endDate = Carbon::parse($dateRange[1])->endOfDay();   // Set time to 23:59:59 on the last day

                if($dateRange[0]===$dateRange[1]){
                    $ordersQuery->where('created_at',  $startDate);

                }
                $ordersQuery->whereBetween('created_at', [ $startDate,$endDate]);
            }

        }
 
        // Fetch orders
        $orders = $ordersQuery->get();
      // Check if there are any orders
if ($orders->isEmpty()) {
    // Optionally, you can return a message or handle the case when there are no orders
    return redirect()->back();
}
        return (new FastExcel($orders->map(function($order){
            return [
                __('name') => $order->name ?? '-',
                __('phone') => $order->phone,
                __('Salary') => $order->salary,
                __('Commitments') => $order->commitments,
                __('Work') => $order->work,
                __('bank') => $order->bank ? __($order->bank->name) : '-',
                __('city') => $order->city ? __($order->city->name) : ($order->city_name ? __($order->city_name) : '-'),
                __('Payment Type') => __($order->payment_type),
                __('car') => $order->car ? __($order->car->name) : ($order->car_name ? __($order->car_name) : '-'),
                __('price') => $order->price,
                __('type') => __($order->type),
                __('status') => $order->status ? __($order->status->name) : '-',
                __('date') => $order->created_at->format('Y-m-d'),
            ];
        })))->download('orders.xlsx');
    }
    
    
}
