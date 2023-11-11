@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container-fluid">

      {{-- <div class="card mb-3">
        <div class="card-header row gutters-5">
          <div class="col">
              <h5 class="mb-md-0 h6">Import Farmer</h5>
          </div>
        </div>
        <div class="card-body">
          @include('shared.form-alerts')
          <form method="post" action="{{ route('farmer.import_csv') }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group row">
                <div class="col-2">
                  Import farmer
                </div>
                <div class="col-5">
                  <input type="file" name="csvFile" class="form-control">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-3 offset-2">
                  <button type="submit" class="btn btn-primary">Import</button>
                </div>
              </div>
          </form>
        </div>
      </div> --}}

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row" style="margin-bottom: 20px">
                <div class="col-12">

                  <div class="input-group input-group-md">
                    <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" id="name" placeholder="Search Information Farmer" name="name" value="" style="">  
                  </div>
                </div>
                {{-- <div class="col-6">

                  <div class="input-group input-group-md">
                    <input type="number" class="form-control form-control-user @error('phone') is-invalid @enderror" id="phone" placeholder="Phone" name="phone" value="" style="">  
                  </div>
                </div> --}}
              </div>
              <div class="row align-items-center d-flex">
                <div class="container">
                  <div class="col text-center">
                      <button type="button" name="filter" id="filter" class="btn btn-info" style="width:20%">Search</button>
                      <button type="button" name="reset" id="reset" class="btn btn-light" style="width:20%">Reset</button>
                  </div>
                  
                </div>
              </div>
            </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="card">
                  <div class="card-header row gutters-5">
                      <div class="col">
                          <h5 class="mb-md-0 h6">All Farmer</h5>
                      </div>
                      {{-- <div class="col">
                          <div class="mar-all mb-2" style=" text-align: end;">
                              <a href="{{route('country.create')}}">
                                  <button type="submit" name="button" value="publish"
                                      class="btn btn-primary">Create</button>
                              </a>
                          </div>
                      </div> --}}
                  </div>
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                            <th>Farmer Code</th>
                            <th>Farmer Name</th>
                            <th>Phone Number</th>
                            <th>Gender</th>
                            <th>Field Officer</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                          </tr>
                      </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

        
        <!-- /.row -->
      </div>
      <script>
        @if(Session::has('success'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
        toastr.success("{{ session('success') }}");
        @endif
        @if(Session::has('add'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
        toastr.success("{{ session('add') }}");
        @endif
        @if(Session::has('delete'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
            toastr.success("{{ session('delete') }}");
        @endif
      </script>
        <style>
            
        </style>
@stop

@push('scripts')
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}" ></script>

<script type="text/javascript">
$(document).ready(function()
{
    fill_datatable();

    function fill_datatable(data = '')
    {
      console.log(data)
    var rfq_table = $("#example1").DataTable
    ({
      
      lengthChange: true,
      responsive: true,
      processing: true,
      searching: false,
      bSort:false,
      serverSide: true,
       ajax: {
            url:"{{route('farmer.dtajax')}}",
            pages: 20,
            data:
            {
              search:data,
            }
          },
      // error: function (xhr) {
      //     if (xhr.status == 401) {
      //     window.location.href = "{!! route('login') !!}";
      //     }
      // },
      columns: [
            {data: 'farmer_code', name: 'farmer_code',render: function (data) {
              return (data=="")?"":data;
            }},
            {data: 'full_name', name: 'full_name', render: function(data){
                return (data=="")?"":data;
            }},
            {data: 'phone_number', name: 'phone_number',render: function (data) {
              return (data=="")?"":data;
            }},
            {data: 'gender', name: 'gender',render: function (data) {
              return (data=="")?"":data;
            }},
            {data: 'staff_name', name: 'staff_name'},
            {data: 'action', name: 'action'},
        ],
        drawCallback:function(setting){
          
          $('[data-toggle="tooltip"]').tooltip();
          var abc = $(this).find('.dataTables_empty').length;
          console.log("aaaaaa" + abc);
          if ($(this).find('.dataTables_empty').length == 1) {
                // $('th').hide();
                // $('#example1_info').hide();
                $('#example1_paginate').hide();
          }
        },
        fnDrawCallback: function () {
          var abc = $(this).find('.dataTables_empty').length;
          console.log("aaaaaa" + abc);
        }
    });
  }

    $('#filter').click(function(){
      var data = $('#name').val();
      if( data == '')
      {
        toastr["error"]("Please input data to search!")
        toastr.options = {
          "closeButton": false,
          "debug": true,
          "newestOnTop": false,
          "progressBar": false,
          "positionClass": "toast-top-right",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
      }
      else
      {
        $('#example1').DataTable().destroy();
        fill_datatable(data);
      }
    });

  $('#reset').click(function(){
      var data = $('#name').val('');
      $('#example1').DataTable().destroy();
      fill_datatable(data);
  });
  });
    
</script>
@endpush