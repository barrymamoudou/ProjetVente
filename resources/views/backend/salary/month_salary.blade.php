@extends('admin_dashboard')
@section('admin')

 <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
      <a href="{{ route('add.advance.salary') }}" class="btn btn-primary rounded-pill waves-effect waves-light">Ajouter un salaire anticipé </a>  
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Salaire du mois dernier</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Mois</th>
                                <th>Salaire</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>


        <tbody>
        	@foreach($paidsalary as $key=> $item)
            <tr>
                <td>{{ $key+1 }}</td>
                <td> <img src="{{ asset($item->employee->image) }}" style="width:50px; height: 40px;"> </td>
                <td>{{ $item['employee']['name'] }}</td>
                <td>{{ $item->salary_month }}</td>
                <td>{{ $item['employee']['salary'] }}</td>
                <td><span class="badge bg-success"> entièrement payé </span> </td>
                <td>
<a href="{{ route('edit.advance.salary',$item->id) }}" class="btn btn-blue rounded-pill waves-effect waves-light">History</a> 

                </td>
            </tr>
            @endforeach
        </tbody>
                    </table>

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->




                    </div> <!-- container -->

                </div> <!-- content -->


@endsection 