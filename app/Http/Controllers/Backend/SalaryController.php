<?php

namespace App\Http\Controllers\backend;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\PaySalary;
use Illuminate\Http\Request;
use App\Models\AdvanceSalary;
use App\Http\Controllers\Controller;

class SalaryController extends Controller
{
    public function AddAdvanceSalary(){
        $employee=Employee::latest()->get();
        return view('backend.salary.add_advance_salary',compact('employee'));
    }
        //Ajouter un Paiement de salaire
    // public function AdvanceSalaryStore(Request $request){

    //     // Ce code vérifie si une avance a déjà été accordée pour un employé donné dans un mois donné.
    //     //  S'il n'y a pas d'avance, il l'ajoute et retourne un message de succès. 
    //     //  Sinon, il retourne un message d'avertissement.

    //         $validateData = $request->validate([
    //             'month' => 'required',
    //             'year' => 'required',
    //             // 'advance_salary' => 'required|max:255', 
    //         ]);
    //         $month = $request->month;
    //         $employee_id = $request->employee_id;
    //         $year = $request->year;

    //         $AnneActuel = now()->year;
    //         $MoisActuel = now()->month;
    //         if ($year ) {
    //             # code...
    //         }

    //         $advanced = AdvanceSalary::where('month', $month)->where('year', $year)->where('employee_id', $employee_id)->first();

    //         if(!$advanced){
    //             AdvanceSalary::create([
    //                 'employee_id' => $request->employee_id,
    //                 'month' => $request->month,
    //                 'year' => $request->year,
    //                 'advance_salary' => $request->advance_salary,
    //                 'created_at' => now(), 
    //             ]);

    //             $message = 'Avance sur salaire payée avec succès';
    //             $alertType = 'success';

    //             // return redirect()->back()->with([
    //             //     'message' => 'Avance sur salaire payée avec succès',
    //             //     'alert-type' => 'success',
    //             // ]);
    //         }else {
    //             $message = 'Avance déjà payée';
    //             $alertType = 'warning';
    //         }

    //         return redirect()->back()->with([
    //             'message' => $message,
    //             'alert-type' => $alertType,
    //         ]);



    //     // if ($advanced === NULL) {

    //     //     AdvanceSalary::insert([
    //     //         'employee_id' => $request->employee_id,
    //     //         'month' => $request->month,
    //     //         'year' => $request->year,
    //     //         'advance_salary' => $request->advance_salary,
    //     //         'created_at' => Carbon::now(), 
    //     //     ]);

    //     //  $notification = array(
    //     //     'message' => 'Advance Salary Paid Successfully',
    //     //     'alert-type' => 'success'
    //     // );

    //     // return redirect()->back()->with($notification); 


    //     // } else{

    //     //      $notification = array(
    //     //     'message' => 'Advance Already Paid',
    //     //     'alert-type' => 'warning'
    //     // );

    //     // return redirect()->back()->with($notification); 

    //     // }

    // }// End Method 

    public function AdvanceSalaryStore(Request $request)
    {
        // Validation des données entrantes
        $validateData = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Assurer que l'employé existe
            'month' => 'required',
            'year' => 'required|integer|min:2022|max:2026', // Par exemple, limiter les années
            'advance_salary' => 'required|numeric|min:0', // Assurer que le salaire est un nombre positif
        ]);
    
        $month = $request->month;
        $employee_id = $request->employee_id;
        $year = $request->year;
    
        // Récupérer l'année et le mois actuels
        $currentYear = now()->year;
        $currentMonth = now()->month;
    
        // Convertir le mois en numéro
        $monthNumber = date('m', strtotime($month)); // "January" => 1, "February" => 2, etc.
    
        // Vérification si le mois et l'année sont dans le passé
        if ($year < $currentYear || ($year == $currentYear && $monthNumber < $currentMonth)) {
            return redirect()->back()->with([
                'message' => 'Vous ne pouvez pas demander une avance pour un mois passé.',
                'alert-type' => 'warning',
            ]);
        }
    
        // Vérifier si une avance existe déjà pour cet employé
        $advanced = AdvanceSalary::where('month', $month)->where('year', $year)->where('employee_id', $employee_id)->first();
    
        if (!$advanced) {
            AdvanceSalary::create([
                'employee_id' => $request->employee_id,
                'month' => $request->month,
                'year' => $request->year,
                'advance_salary' => $request->advance_salary,
                'created_at' => now(),
            ]);
    
            return redirect()->back()->with([
                'message' => 'Avance sur salaire payée avec succès',
                'alert-type' => 'success',
            ]);
        } else {
            return redirect()->back()->with([
                'message' => 'Avance déjà payée pour ce mois.',
                'alert-type' => 'warning',
            ]);
        }
    }
    public function AllAdvanceSalary(){
        $salary = AdvanceSalary::latest()->get();
        return view('backend.salary.all_advance_salary',compact('salary'));
    }
        //Modification Paiement de salaire
    public function EditAdvanceSalary($id){
        $employee = Employee::latest()->get();
        $salary = AdvanceSalary::findOrFail($id);
        return view('backend.salary.edit_advance_salary',compact('salary','employee'));
    }

    public function AdvanceSalaryUpdate(Request $request) {
        
        $salaryId = $request->id;
    
        AdvanceSalary::findOrFail($salaryId)->update([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'year' => $request->year,
            'advance_salary' => $request->advance_salary,
            'created_at' => now(),  // Utilise now() pour la date actuelle
        ]);
    
        return redirect()->route('all.advance.salary')->with([
            'message' => 'Avance de salaire mise à jour avec succès',
            'alert-type' => 'success',
        ]);
    }

    //Paiement de salaire
    public function PaySalary(){
        // $employee = Employee::latest()->get();
        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth = Carbon::now()->endOfMonth();

        // $employee = Employee::whereHas('advance', function($query) use ($startOfMonth, $endOfMonth) {
        //     $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
        // })->latest()->get();
        $employee = Employee::whereHas('advance', function($query) {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        })->latest()->get();
        return view('backend.salary.pay_salary',compact('employee'));
    }

    public function PayNowSalary($id){

        $paysalary = Employee::findOrFail($id);
        return view('backend.salary.paid_salary',compact('paysalary'));

    }// End Method 

    public function EmployeSalaryStore(Request $request){
        $employee_id = $request->id;

        PaySalary::insert([

            'employee_id' => $employee_id,
            'salary_month' => $request->month,
            'paid_amount' => $request->paid_amount,
            'advance_salary' => $request->advance_salary,
            'due_salary' => $request->due_salary,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Employee Salary Paid Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pay.salary')->with($notification); 
    }

    public function MonthSalary(){
        $paidsalary = PaySalary::latest()->get();
        return view('backend.salary.month_salary',compact('paidsalary'));  
    }

}
