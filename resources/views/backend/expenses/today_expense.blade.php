@extends('admin_dashboard')
@section('admin')


<div  class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
      <div class="row">
        <div class="col-12"> 
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <a href="" class="btn btn-primary rounded-pill waves-effect waves-light">Depense</a>
                    </ol>
                    <h4 class="page-title">D'Aujourd'hui Depense</h4>
                </div>
            </div>
        </div>
      </div>
       <!-- end row --> 

       @php
        $date=date("d-m-Y");
        $expense = App\Models\Expense::where('date',$date)->sum('amount');
       @endphp

      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title"> Today Expense </h4>   
                    <h4 style="color:white; font-size: 30px;" align="center"> Total :  ${{ $expense }}</h4>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Details</th>
                                <th>Montant</th>
                                <th>Mois</th>
                                <th>Année</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($today as $key=> $item )
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->details }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->month }}</td>
                                <td>{{ $item->year }}</td>
                                <td>
                                <a href="{{ route('edit.expense',$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light"><i class="fa fa-pencil-alt"></i></a> 
                                </td>
                            </tr> 
                            @endforeach
                            
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
      </div>




      <!-- end page title --> 
    </div>
</div>




@endsection 