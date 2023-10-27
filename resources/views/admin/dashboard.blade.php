@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-account mdi-24px" ></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $staffs->count() }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Field Officers</p>
                  </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-account-cowboy-hat mdi-20px"></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $farmerCount }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Farmers</p>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-land-plots mdi-24px" ></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ number_format($totalLandHolding, 2) }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Land Holding(HA)</p>
                  </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-land-fields mdi-20px"></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $totalFarmlands }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Farms</p>
                  </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" id="basic-chart">

            </div>
            <div class="col-md-6" id="location-chart">
                    
            </div>
        </div>
        <div id="area-chart" class="mt-5">

        </div>
        <table id="datatable" style="display:none;">
            <thead>
                <tr>
                    <th></th>
                    <th>Total Land Holding</th>
                    <th>Actual Area</th>
                </tr>
            </thead>
            <tbody>
                @foreach($communeByFarmAreas as $communeByFarmArea) 
                    <tr>
                        <td>{{ $communeByFarmArea['name'] }}</td>
                        <td>{{ $communeByFarmArea['total_land_holding'] }}</td>
                        <td>{{ $communeByFarmArea['actual_area'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection 

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('basic-chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Farmers by Field Officer'
                },
                xAxis: {
                    categories: ['Farmer']
                },
                yAxis: {
                    title: {
                        text: 'Total Farmer'
                    }
                },
                series: {!! json_encode($staffsFormat) !!},
            });
        });
        
        $(document).ready(function() {
            var locationChart = Highcharts.chart('location-chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Farmers by Commune'
                },
                xAxis: {
                    categories: ['Farmer']
                },
                yAxis: {
                    title: {
                        text: 'Total Farmer'
                    }
                },
                series:  {!! json_encode($comunessData) !!},
            });

            Highcharts.chart('area-chart', {
                data: {
                    table: 'datatable'
                },
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Farm area by commune'
                },
                type: 'category',
                tooltip: {
                    valueSuffix: ' HA'
                },
            });
        });

    </script>
@endpush
