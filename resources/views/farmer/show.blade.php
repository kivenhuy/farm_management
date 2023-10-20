@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6"  style="color:black;font-size:24px">Farmer Details</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                {{-- <form action="{{route('country.store')}}" method="POST" id="country_from">
                    @csrf --}}
                    {{-- Country Name --}}
                    <label for="inputPassword3" class="col-sm-6 col-form-label" style="color:black;font-size:18px">Personal Information</label>
                    <div class="card box-info" >
                        <div class="col-8">
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Farmer Name:</label>{{$farmer_data->full_name}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Mobile Number:</label>{{$farmer_data->phone_number}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Identity Proof:</label>{{$farmer_data->identity_proof}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Proof No:</label>{{$farmer_data->proof_no}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Gender:</label>{{$farmer_data->gender}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">DOB:</label>{{$farmer_data->dob}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Farmer Code:</label>{{$farmer_data->farmer_code}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Enrollment Date:</label>{{$farmer_data->enrollment_place}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Enrollment Place:</label>{{$farmer_data->enrollment_date}}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-sm-12">
                                <img src="{{$farmer_data->farmer_photo}}" alt="">
                            </div>
                        </div>
                    </div>
                    

                    <label for="inputPassword3" class="col-sm-6 col-form-label" style="color:black;font-size:18px">Address Information</label>
                    <div class="card box-info" >
                        <div class="col-8">
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Country Name:</label>{{$farmer_data->country}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Province Name:</label>{{$farmer_data->province}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">District Name:</label>{{$farmer_data->district}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Commune Name:</label>{{$farmer_data->commune}}</div>
                            </div>
                            <div class="col-sm-10">
                                <div class="col-md-10 fvalue"><label for="inputPassword3" class="col-sm-4 col-form-label">Village:</label>{{$farmer_data->village}}</div>
                            </div>
                        </div>
                    </div>
                    
              </div>
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <style>
        .box-info
        {
            padding: 0 16px;
            display: flex;
            flex-direction: row;
        }
      </style>
@stop
@push('scripts')
<script type="text/javascript">
    $(document).ready(function()
    {   
        
    });
    function myFunction() {
            alert('aaa')
        }
</script>
@endpush