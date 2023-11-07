@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('crop-calendars.create') }}" class="btn btn-info mb-4">Create Crop Calendar</a>
            @include('shared.form-alerts')
        </div>

        <table class="table table-bordered">
            <thead>
              <tr style="background-color: #666cff;">
                <th scope="col" style="color:white;">Name</th>
                <th scope="col" style="color:white;">Country</th>
                <th scope="col" style="color:white;">Province</th>
                <th scope="col" style="color:white;">District</th>
                <th scope="col" style="color:white;">Commune</th>
                <th scope="col" style="color:white;">Status</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                
            </tbody>
          </table>
        

        <div class="position-relative" style="min-height: 30px">
            {{ $cropCalendars->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $cropCalendars->total() }}</span> results found</div>
        </div>
    </div>
@endsection 

@section('style')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {-
        });
    </script>
@endpush