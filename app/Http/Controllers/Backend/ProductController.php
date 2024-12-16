<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class ProductController extends Controller
{
    public function AllProduct(){
      $allData=Product::latest()->get();
      
      return view('backend.products.all',compact('allData'));
    }

    public function AddProduct(){
       $category=Category::all();
       $supplier=Supplier::all();
        return view('backend.products.add',compact('category','supplier'));
    }

    public function StoreProduct(Request $request){
        $code = IdGenerator::generate([
            'table' => 'products', // Nom de la table dans laquelle l'ID sera utilisé
            'field' => 'product_code',    // Nom de la colonne dans laquelle l'ID sera inséré
            'length' => 4,     // Longueur de l'ID
            'prefix' => 'PC-', // Préfixe pour l'ID
        ]);
        
        $image = $request->file('product_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/product/'.$name_gen);
        $save_url = 'upload/product/'.$name_gen;
        Product::insert([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'product_code' => $code,
            'product_garage' => $request->product_garage,
            'product_store' => $request->product_store,
            'buying_date' => $request->buying_date,
            'expire_date' => $request->expire_date,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'product_image' => $save_url,
            'created_at' => Carbon::now(), 
        ]);
         $notification = array(
            'message' => 'Product Enregistre avec success ',
            'alert-type' => 'success'
        );
        return redirect()->route('all.product')->with($notification); 
    } // End Method    

    public function EditProduct($id){
        $product = Product::findOrFail($id);
        $category = Category::latest()->get();
        $supplier = Supplier::latest()->get();
        return view('backend.products.edit_product',compact('product','category','supplier'));
    }

    public function UpdateProduct(Request $request){
        
        $product_id=$request->id;
        
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/product/' . $name_gen);
            $save_url = 'upload/product/' . $name_gen;
        } else {
            $save_url = null; // Aucune modification de l'image si elle n'est pas fournie
        }

        $data = [
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'product_code' => $request->product_code,
            'product_garage' => $request->product_garage,
            'product_store' => $request->product_store,
            'buying_date' => $request->buying_date,
            'expire_date' => $request->expire_date,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price,
            'created_at' => now(),
        ];

        if ($save_url) {
            $data['product_image'] = $save_url;
        }
       
            DB::table('products')->where('id', $product_id)->update($data);

            
            $notification = [
                'message' => 'Product Updated Successfully',
                'alert-type' => 'success',
            ];

        return redirect()->route('all.product')->with($notification);
    }

    public function DeleteProduct($id){
        $product_ima=Product::find($id)->first();
        $ima=$product_ima->product_image;
        unlink($ima);
        Product::find($id)->delete();
        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 
    }

    //faire le barCode des product

    public function BarcodeProduct($id){
        $product = Product::findOrFail($id);
        return view('backend.products.barcode_product',compact('product'));
    }// End Method

    public function ImportProduct(){
        return view('backend.products.import_product');
    }

    public function Export(){
        
    }
    
     
    
    
}
