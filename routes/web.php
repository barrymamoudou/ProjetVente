<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\PosController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\backend\SalaryController;
use App\Http\Controllers\Backend\ExpenseController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\SupplierController;
use App\Http\Controllers\backend\AttendenceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

route::get('admin/logout',  [AdminController::class, 'AdminDestroy'])->name('admin.logout');
route::get('/logout',  [AdminController::class, 'AdminLogoutPage'])->name('admin.logout.page');
route::middleware(['auth'])->group(function (){

    route::get('admin/profile',  [AdminController::class, 'AdminProfile'])->name('admin.profile');
    route::post('admin/profile/store',  [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/change/password', [AdminController::class, 'ChangePassword'])->name('change.password');
    Route::post('/update/password', [AdminController::class, 'UpdatePassword'])->name('update.password');

    // ALL employees

    route::controller(EmployeeController::class)->group(function (){
        route::get('/all/employee', 'AllEmployee')->name('all.employee');
        Route::get('/add/employee','AddEmployee')->name('add.employee');
        Route::post('/store/employee','StoreEmployee')->name('employee.store');
        Route::get('/edit/employee/{id}','EditEmployee')->name('edit.employee');
        Route::post('/update/employee','UpdateEmployee')->name('employee.update');
        Route::get('/delete/employee/{id}','DeleteEmployee')->name('delete.employee');
     });

     // ALL customer
     Route::controller(CustomerController::class)->group(function(){
        route::get('/all/customer', 'AllCustomer')->name('all.customer');
        Route::get('/add/customer','AddCustomer')->name('add.customer');
        Route::post('/store/customer','StoreCustomer')->name('customer.store');
        Route::get('/edit/customer/{id}','EditCustomer')->name('edit.customer');
        Route::post('/update/customer','UpdateCustomer')->name('customer.update');
        Route::get('/delete/customer/{id}','DeleteCustomer')->name('delete.customer');
     
    });

     /// Supplier All Route 
        Route::controller(SupplierController::class)->group(function(){

        Route::get('/all/supplier','AllSupplier')->name('all.supplier');
        Route::get('/add/supplier','AddSupplier')->name('add.supplier');
        Route::post('/store/supplier','StoreSupplier')->name('supplier.store');
        Route::get('/edit/supplier/{id}','EditSupplier')->name('edit.supplier');
        Route::post('/update/supplier','UpdateSupplier')->name('supplier.update');
        Route::get('/delete/supplier/{id}','DeleteSupplier')->name('delete.supplier');
        Route::get('/details/supplier/{id}','DetailsSupplier')->name('details.supplier'); 
    });

    /// add advance avance salary All Route 
    Route::controller(SalaryController::class)->group(function(){

        Route::get('/add/advance/salary','AddAdvanceSalary')->name('add.advance.salary');
        Route::post('/advance/salary/store', 'AdvanceSalaryStore')->name('advance.salary.store');

        Route::get('/all/advance/salary','AllAdvanceSalary')->name('all.advance.salary');
        Route::get('/edit/advance/salary/{id}','EditAdvanceSalary')->name('edit.advance.salary');
        Route::post('/advance/salary/update','AdvanceSalaryUpdate')->name('advance.salary.update');
    
    });

    
/// Pay Salary All Route 
    Route::controller(SalaryController::class)->group(function(){

        Route::get('/pay/salary','PaySalary')->name('pay.salary');
        Route::get('/pay/now/salary/{id}','PayNowSalary')->name('pay.now.salary');
        Route::post('/employe/salary/store','EmployeSalaryStore')->name('employe.salary.store');
        Route::get('/month/salary','MonthSalary')->name('month.salary');
    
    });

    ///Attendence All Route 
    Route::controller(AttendenceController::class)->group(function(){

        Route::get('/employee/attend/list','EmployeeAttendenceList')->name('employee.attend.list'); 
         
        Route::get('/add/employee/attend','AddEmployeeAttendence')->name('add.employee.attend');

        Route::post('/employee/attend/store','EmployeeAttendenceStore')->name('employee.attend.store'); 
        Route::get('/edit/employee/attend/{date}','EditEmployeeAttendence')->name('employee.attend.edit'); 
        Route::get('/view/employee/attend/{date}','ViewEmployeeAttendence')->name('employee.attend.view'); 
    
    });
    
    ///Category All Route 
    Route::controller(CategoryController::class)->group(function(){

        Route::get('/all/category','AllCategory')->name('all.category');
        Route::post('/store/category','StoreCategory')->name('category.store'); 

        Route::get('/edit/category/{id}','EditCategory')->name('edit.category');
        Route::post('/update/category','UpdateCategory')->name('category.update'); 
        Route::get('/delete/category/{id}','DeleteCategory')->name('delete.category');
    
    });

    ///Product All Route 
    Route::controller(ProductController::class)->group(function(){

    Route::get('/all/product','AllProduct')->name('all.product');
    
    Route::get('/add/product','AddProduct')->name('add.product');
    
    Route::post('/store/product','StoreProduct')->name('product.store');

    Route::get('/edit/product/{id}','EditProduct')->name('edit.product');

    Route::post('/update/product','UpdateProduct')->name('product.update');
    
    Route::get('/delete/product/{id}','DeleteProduct')->name('delete.product');

    Route::get('/barcode/product/{id}','BarcodeProduct')->name('barcode.product');

    Route::get('/import/product','ImportProduct')->name('import.product');


    Route::get('/export','Export')->name('export');
    
    });

    Route::controller(ExpenseController::class)->group(function(){

        Route::get('/add/expense','AddExpense')->name('add.expense');
        Route::post('/store/expense','StoreExpense')->name('expense.store');
        Route::get('/today/expense','TodayExpense')->name('today.expense');
        Route::get('/edit/expense/{id}','EditExpense')->name('edit.expense');
        Route::post('/update/expense','UpdateExpense')->name('expense.update');


        Route::get('/month/expense','MonthExpense')->name('month.expense');
        Route::get('/year/expense','YearExpense')->name('year.expense');
        
    });

    Route::controller(PosController::class)->group(function(){

        Route::get('/pos','Pos')->name('pos');
        Route::post('/add-cart','AddCart');
        Route::get('/allitem','AllItem');

        Route::post('/cart-update/{rowId}','CartUpdate');
        Route::get('/cart-remove/{rowId}','CartRemove');

        Route::post('/create-invoice','CreateInvoice');
       
    });

    ///Order All Route 
    Route::controller(OrderController::class)->group(function(){

        Route::post('/final-invoice','FinalInvoice');
        
        Route::get('/pending/order','PendingOrder')->name('pending.order');

        Route::get('/order/details/{order_id}','OrderDetails')->name('order.details');

        Route::post('/order/status/update','OrderStatusUpdate')->name('order.status.update');

        Route::get('/complete/order','CompleteOrder')->name('complete.order');
        
        Route::get('/stock','StockManage')->name('stock.manage');

        Route::get('/order/invoice-download/{order_id}','OrderInvoice');
        
   });

});

