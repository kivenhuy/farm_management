@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container-fluid">

      <form action="{{ route('farmer.index')}}" class="mb-5">
        {{-- @csrf --}}
        <div class="form-group row align-items-center">
            <div class="col">
                <div>
                    <label for="js-farmer-code">Farmer Code</label>
                    <input type="text" name="farmer_code" id="js-farmer-code" class="form-control" value="{{ $farmerCode }}">
                </div>
            </div>
            <div class="col">
                <label for="js-farmer-name">Farmer Name</label>
                <input id="js-farmer-name" name="farmer_name" type="text" class="form-control" value="{{ $farmerName }}" autocomplete="off" placeholder="From Period">
            </div>
            <div class="col">
                <label for="js-start-date">Start Date</label>
                <input id="js-start-date" name="start_date" type="text" class="form-control datatimepicker-enable" value="{{ $startDate }}" autocomplete="off" placeholder="Start Date">
            </div>
            <div class="col">
              <label for="js-end-date">End Date</label>
              <input id="js-end-date" name="end_date" type="text" class="form-control datatimepicker-enable" value="{{ $endDate }}" autocomplete="off" placeholder="End Date">
          </div>
          <div style="width: 260px;" class="mt-3">
              <button type="submit" class="btn btn-primary" style="margin-right: 1rem;">Search</button>
              <button type="button" class="btn btn-secondary js-reset">Reset</button>
          </div>
        </div>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr style="background-color: #666cff;">
          <th scope="col" style="color:white;">Farmer Code</th>
          <th scope="col" style="color:white;">Farmer Name</th>
          <th scope="col" style="color:white;">Phone Number</th>
          <th scope="col" style="color:white;">Gender</th>
          <th scope="col" style="color:white;">Enrollment Date</th>
          <th scope="col" style="color:white;">Field Officer</th>
          <th scope="col" style="color:white;">Action</th>
        </tr>
      </thead>
      <tbody>
          @if($farmerDetails->count())
              @foreach ($farmerDetails as $farmerDetail)  
                  <tr>
                      <td>{{ $farmerDetail->farmer_code}}</td>
                      <td>{{ $farmerDetail->full_name}}</td>
                      <td>{{ $farmerDetail->phone_number}}</td>
                      <td>{{ $farmerDetail->gender}}</td>
                      <td>{{ $farmerDetail->enrollment_date}}</td>
                      <td>{{ $farmerDetail->staff->name}}</td>
                      <td style="width: 200px;">
                          <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('farmer.show', ['id' => $farmerDetail]) }}" title="Edit">
                              <i class="fa fa-eye"></i>
                          </a>
                      </td>
                  </tr>
              @endforeach
          @endif
      </tbody>
    </table>
  

  <div class="position-relative mt-5" style="min-height: 30px">
      {{ $farmerDetails->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $farmerDetails->total() }}</span> results found</div>
  </div>
@endsection


@push('scripts')
    <script src="{{ asset('custom/js/jquery.datetimepicker.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.datatimepicker-enable').datetimepicker({
                format: 'Y-m-d',
        		    datepicker: true,
                timepicker: false,
            });

            $('.js-reset').click(function(){
                $('input[type="text"]').val('');
                $('select').val('');
                document.querySelector('input[name="status"]:checked').checked = false;
            });
        });
    </script>
@endpush
