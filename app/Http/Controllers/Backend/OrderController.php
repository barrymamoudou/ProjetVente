<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Orderdetails;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderController extends Controller
{
    //
    public function FinalInvoice(Request $request){

        $order=new Order();
        $order->customer_id = $request->customer_id;
        $order->order_date = $request->order_date;
        $order->order_status = $request->order_status;
        $order->total_products = $request->total_products;
        $order->sub_total =  $request->sub_total;
        $order->vat = $request->vat;
        $order->invoice_no = 'EPOS'.mt_rand(10000000,99999999);
        $order->total =  $request->total;
        $order->payment_status = $request->payment_status;
        $order->pay = $request->pay;
        $order->due = $request->due;
        $order->created_at= Carbon::now(); 
        $order->save();

         // Récupérer l'ID de la commande insérée
        $order_id = $order->id;
         // Récupérer le contenu du panier
        $contents = Cart::content();

         // Ajouter les détails de la commande pour chaque produit dans le Panier  Correction ici : on accède dirèctement à la propriété qty 
        foreach ($contents as $key => $value) {                             
            $order_details = new Orderdetails();                                          
            $order_details->order_id=$order_id;                                     
            $order_details->product_id=$value->id;                               
            $order_details->quantity=$value->qty;                              
            $order_details->unitcost=$value->price;
            $order_details->total=$value->total;                                                
            $order_details->save();
        }


        
        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );
          //Vider le panier
        Cart::destroy();

        return redirect()->route('dashboard')->with($notification);
    } // End Method

    public function PendingOrder(){

        $orders = Order::where('order_status','pending')->get();
        return view('backend.order.pending_order',compact('orders'));

    }// End Method 

    public function OrderDetails($order_id){
        //ici on recupere id de la command et si cette commande est egal a la commande on envoyer au donne sont bon on  continieur
        $order = Order::where('id',$order_id)->first();

        $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();
        return view('backend.order.order_details',compact('order','orderItem'));

    }// End Method   order_details.blade.php


    public function OrderStatusUpdate(Request $request){

        $order_id = $request->id;

        /**
         * verifier les donnes 
         */

         $product=Orderdetails::where('order_id',$order_id)->get();
         
         foreach($product as $item){
            Product::where('id',$item->product_id)->update([
               "product_store"=>DB::raw('product_store-'.$item->quantity)
            ]);
         }

        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

         $notification = array(
            'message' => 'Order Done Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('pending.order')->with($notification);


    }// End Method 

    public function CompleteOrder(){

        $orders = Order::where('order_status','complete')->get();
        return view('backend.order.complete_order',compact('orders'));

    }// End Method 


    public function StockManage(){

        $product = Product::latest()->get();
        return view('backend.stock.all_stock',compact('product'));
    
    }// End Method 

    public function OrderInvoice($order_id){

        $order = Order::where('id',$order_id)->first();

       $orderItem = Orderdetails::with('product')->where('order_id',$order_id)->orderBy('id','DESC')->get();

       $pdf = Pdf::loadView('backend.order.order_invoice', compact('order','orderItem'))->setPaper('a4')->setOption([
               'tempDir' => public_path(),
               'chroot' => public_path(),

       ]);
        return $pdf->download('invoice.pdf');

   }// End Method 
    




 
    
}
