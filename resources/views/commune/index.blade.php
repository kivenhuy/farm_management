@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">All Commune</h5>
                    </div>
                    <div class="col">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <a href="{{route('commune.create')}}">
                                <button type="submit" name="button" value="publish"
                                    class="btn btn-primary">Create</button>
                            </a>
                        </div>
                    </div>
                </div>
              <div class="card-body" >
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>RFQ Code</th>
                      <th>Raised Date</th>
                      <th>Seller Name</th>
                      <th>Product Name</th>
                      <th>Quantity</th>
                      <th>Price</th>
                      <th>Status</th>
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
@stop

@section('script')


{{-- <script type="text/javascript">
    $(document).ready(function()
    {   
        var rfq_table = $("#example1").DataTable
        ({
                lengthChange: true,
                responsive: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('country.dtajax')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    columns: [
                          {data: 'code_rfq', name: 'code_rfq', render: function(data){
                              return (data=="")?"":data;
                          }},
                          {data: 'created_at', name: 'created_at',render: function (data, type, row) {
                                    return moment(data).format('MM/DD/YYYY');
                            }},
                          {data: 'seller_name', name: 'seller_name'},
                          {data: 'product_name', name: 'product_name'},
                          {data: 'quantity', name: 'quantity', render: function(data){
                              return (data=="")?"":data;
                          }},
                          {data: 'price', name: 'price'},
                          {data: 'status', name: 'status', render: function(data){
                            
                          if(data == 0)
                          {
                              return "<span class='badge badge-inline badge-secondary'>Pending Approval</span>";
                          }
                          else if(data == 1)
                          {
                              return "<span class='badge badge-inline badge-warning'>Pending Price Update</span>";
                          }
                          else if(data == 2)
                          {
                              return "<span class='badge badge-inline badge-info' >Waiting For Customer</span>";
                          }
                          else if(data == 3)
                          {
                              return "<span class='badge badge-inline badge-success' style='background-color:#28a745 !important'>Added To Cart</span>";
                          }
                        }},
                        {
                                data: 'action', 
                                name: 'action', 
                                orderable: true, 
                                searchable: true
                        },
                    ],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6');
    });

    
</script> --}}
@endsection