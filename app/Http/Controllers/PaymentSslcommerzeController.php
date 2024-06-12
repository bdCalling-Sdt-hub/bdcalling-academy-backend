<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PaymentSslcommerzeController extends Controller
{
    public function index(Request $request)
    {
        $price = $request->price;
        $batch_id = $request->batch_id;
        $discount_price = $request->discount_price ?? null;
        $user = auth()->user();
        $student = Student::with('user')->where('user_id',$user->id)->first();
        $course = Batch::with('course')->where('id',$batch_id)->first();
        $course_fee = $course->course->price;
        $course_name = $course->course->course_name;

        if ($user) {

            if ($user->role === "STUDENT") {

                $product = Course::find($request->course_id);

                $post_data = array();
                $post_data['total_amount'] = $price; # You cant not pay less than 10
                $post_data['currency'] = "BDT";
                $post_data['tran_id'] = uniqid(); // tran_id must be unique



                # CUSTOMER INFORMATION
                $post_data['cus_name'] = $user->name;
                $post_data['cus_email'] = $user->email;
//                $post_data['cus_add1'] = $user->image;
                $post_data['cus_country'] = "Bangladesh";
                $post_data['cus_phone'] = $student->phone_number;

                $post_data['shipping_method'] = "NO";
                $post_data['product_name'] = $course_name;
                $post_data['product_category'] = 1;
                $post_data['product_profile'] = "Educational Course";


                #Before  going to initiate the payment order status need to insert or update as Pending.
                $update_product = DB::table('orders')
                    ->where('transaction_id', $post_data['tran_id'])
                    ->updateOrInsert([
                        'batch_id' => $request->batch_id,
                        'amount' => $post_data['total_amount'],
                        'course_fee' => $course_fee,
                        'discount_price' => $discount_price,
                        'price' => $post_data['total_amount'],
                        'due' => 0,
                        'transaction_id' => $post_data['tran_id'],
                        "student_id" => $student->id,
                        'payment_type' => 'online',
                        "currency" => "BDT",
                        'status' => 'Pending',
                        'gateway_name'=>$request->gateway_name,
                        'created_at' => now(),
                        'updated_at' => now()

                    ]);

                $sslc = new SslCommerzNotification();
                # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
                $payment_options = $sslc->makePayment($post_data);

                if (!is_array($payment_options)) {
                    return json_decode($payment_options);
//                    $payment_options = array();
                }
            } else {
                return response()->json(["message" => "You are unauthorized"], 401);
            }
        } else {
            return response()->json(["message" => "You are unauthorized"], 401);
        }

        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing','updated_at' => now()]);

                Redirect::away('[http://103.43.151.135:8000/payment/status/success');
                echo "<br >Transaction is successfully Completed";
            }
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */

            Redirect::away('[http://103.43.151.135:8000/payment/status/success');
            echo "Transaction is successfully Completed";
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed','updated_at' => now()]);
            Redirect::away('[http://103.43.151.135:8000/payment/status/failed');
            echo "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            Redirect::away('[http://103.43.151.135:8000/payment/status/failed');
            echo "Transaction is already Successful";
        } else {
            Redirect::away('[http://103.43.151.135:8000/payment/status/failed');
            echo "Transaction is Invalid";
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled','updated_at' => now()]);
            echo "Transaction is Cancel";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing','updated_at' => now()]);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }



    public function discountCouponCode(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {

            if ($user->userType === "STUDENT") {

                $data = Course::find($request->course_id);
                if ($data) {

                    if ($data->coupon_code == $request->coupon_code) {
                        return response()->json([
                            "coupon-price" => $data->coupon_code_price
                        ]);
                    } else if ($data->coupon_code !== $request->coupon_code) {
                        return response()->json([
                            "message" => "Not matched your coupon code"
                        ], 404);
                    } else {
                        return response()->json([
                            "message" => "There is not available any coupon discount in this course now."
                        ], 404);
                    }
                } else {
                    return response()->json([
                        "message" => "Course not found"
                    ], 404);
                }
            }
        }
    }
}
