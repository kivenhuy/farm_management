@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6" id="basic-chart">

            </div>
            <div class="col-md-6" id="location-chart">
                    
            </div>
        </div>
    </div>
@endsection 

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('basic-chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Farmer by staff staticstic'
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
                    text: 'Farmer by location staticstic'
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
                drilldown: {
                    series: [

                    ],
                }
            });
        });

    </script>
@endpush
