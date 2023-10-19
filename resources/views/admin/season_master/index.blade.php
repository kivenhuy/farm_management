@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <form action="{{ route('season-masters.index')}}" class="mb-5">
            @csrf
            <div class="form-group row align-items-center">
                <div class="col">
                    <div>
                        <label for="js-season-code">Season</label>
                        <select name="season_code" id="js-season-code" class="form-control">
                            <option value="">Select Season</option>
                            @foreach (\App\Models\Season::get()->pluck('name', 'code') as $code => $name)
                                <option value="{{  $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <label for="js-from-period">From period</label>
                    <input id="js-from-period" name="from_period" type="text" class="form-control datatimepicker-enable" value="" autocomplete="off">
                </div>
                <div class="col">
                    <label for="js-to-period">To period</label>
                    <input id="js-to-period" name="to_period" type="text" class="form-control datatimepicker-enable" value="" autocomplete="off">
                </div>
                <div class="col">
                    <label for="js-to-period">Status</label>
                    <div class="d-flex">
                        <div class="form-check mt-2" style="margin-right: 1rem;">
                            <input class="form-check-input" type="radio" name="status" id="status-active" value="active">
                            <label class="form-check-label" for="status-active">Active</label>
                        </div>
                        <div class="form-check  mt-2">
                            <input class="form-check-input" type="radio" name="status" id="status-inactive" value="inactive">
                            <label class="form-check-label" for="status-inactive">Inactive</label>
                        </div>
                    </div>
                </div>
                <div style="width: 200px;">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        @if($seasonMasters->count())
        <table class="table">
            <thead>
              <tr>
                <th scope="col">Season</th>
                <th scope="col">From period</th>
                <th scope="col">To period</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($seasonMasters as $seasonMaster)  
                <tr>
                    <td>{{ $seasonMaster->season->name}}</td>
                    <td>{{ $seasonMaster->from_period}}</td>
                    <td>{{ $seasonMaster->to_period}}</td>
                    <td>{{ $seasonMaster->status == 'active' ? 'Active' : 'Inactive'}} </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
    </div>
@endsection 

@section('style')

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
        });
    </script>
@endpush