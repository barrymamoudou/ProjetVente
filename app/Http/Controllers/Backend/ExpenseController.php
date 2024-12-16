<?php

namespace App\Http\Controllers\Backend;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function AddExpense(){

        return view('backend.expenses.add_expense');

    } // End Method 

    public function StoreExpense(Request $request){

        Expense::insert([

            'details' => $request->details,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'date' => $request->date,
            'created_at' => Carbon::now(), 
        ]);


            $notification = array(
            'message' => 'Expense Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    } // End Method 

    public function TodayExpense(){
        $date = date("d-m-Y");
        $today = Expense::where('date',$date)->get();
        return view('backend.expenses.today_expense',compact('today'));
    }

    public function EditExpense1($id){

        $expense = Expense::findOrFail($id);
        return view('backend.expenses.edit_expense',compact('expense'));
       

    }// End Method resources/views/backend/expenses/edit_expense.blande.phpresources/views/backend/expenses/edit_expense.blande.php

    public function EditExpense($id){

        $expense = Expense::findOrFail($id);
        return view('backend.expenses.edit_expense',compact('expense'));
        // return view('backend.expense.edit_expense',compact('expense'));

    }// End Method  resources/views/backend/expense/year_expense.blade.php

    public function UpdateExpense(Request $request){

        $expense_id = $request->id;

        Expense::findOrFail($expense_id)->update([

            'details' => $request->details,
            'amount' => $request->amount,
            'month' => $request->month,
            'year' => $request->year,
            'date' => $request->date,
            'created_at' => Carbon::now(), 
        ]);


            $notification = array(
            'message' => 'Expense Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('today.expense')->with($notification); 

    }// End Method

    //Récupérer les dépenses d'un mois spécifique (ici le mois courant).
    
    public function MonthExpense(){

        $month = date("F");
        $monthexpense = Expense::where('month',$month)->get();
        return view('backend.expenses.month_expense',compact('monthexpense'));

    }// End Method


     //Récupérer les dépenses d'une annee spécifique (ici le mois courant).
    public function YearExpense(){

       $year = date("Y");
       $yearexpense = Expense::where('year',$year)->get();
       return view('backend.expenses.year_expense',compact('yearexpense'));

   }// End Method
}
