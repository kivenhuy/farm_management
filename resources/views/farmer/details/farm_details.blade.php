@php $farmLands = $farmerDetail->farm_lands->count() ? $farmerDetail->farm_lands : []; 
// dd($farmLands->farm_land_lat_lng);
@endphp

<table class="table table-bordered js-farm-table">
    <thead>
      <tr style="background-color: #2E7F25 !important;">
        <th class="text-white">Farm Name</th>
        <th class="text-white">Land Holding</th>
        <th class="text-white">Action</th>
      </tr>
    </thead>
    <tbody>
        @foreach($farmLands as $farmLand)
            <tr>
                <td>{{ ucwords($farmLand->farm_name) }}</td>
                <td>{{ number_format($farmLand->total_land_holding, 2) . " Ha" }}</td>
                <td>
                    <a href="javascript:void(0)" class="js-view-farm" data-farm-id="{{ $farmLand->id }}">View Farm</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@foreach($farmLands as $farmLand)
    <div class="farm-detail d-none" data-farmland-id={{ $farmLand->id }}>
        <div class="text-end mb-3">
            <a class="back" href="javascript:void(0)"><span class="mdi mdi-arrow-left"></span> Back</a>
        </div>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status" data-bs-toggle="collapse" data-bs-target="#card-body-farm-information-{{ $farmLand->id}}">Farm Information</h5>
            <div class="" id="card-body-farm-information-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Name</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->farm_name) }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Total Land Holding</label>
                            <span class="col-md-6">{{ number_format($farmLand?->total_land_holding, 2) . ' Ha' }}</span>
                        </div>
                    </div>
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Land Ploting</label>
                            <span class="col-md-6">{{ number_format($farmLand?->farm_land_ploting, 2) . ' Ha' }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Actual Area</label>
                            <span class="col-md-6">{{ number_format($farmLand?->actual_area, 2) . ' Ha' }}</span>
                        </div>
                    </div>          
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Land Ownership</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->land_ownership) }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Approach Road</label>
                            <span class="col-md-6">{{ $farmLand?->approach_road }}</span>
                        </div>
                    </div>    
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Land Topology</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->land_topology) }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Land Gradient</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->land_gradient) }}</span>
                        </div>
                    </div>   
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Land Document</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->land_document) }}</span>
                        </div>
                    </div>                
                </div>
            </div>
        </div>
    </div>

    <div class="farm-detail d-none" data-farmland-id={{ $farmLand->id }}>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-plot-area-{{ $farmLand->id}}">Plot Area</h5>
            <div class="collapse" id="card-body-plot-area-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    <div id="map" style="height: 700px;">
                        @php
                            $farmLand->plot_data = $farmLand->farm_land_lat_lng;
                            // dd($plot_data);
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="farm-detail d-none" data-farmland-id={{ $farmLand->id }}>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-farm-photo-{{ $farmLand->id}}">Farm Photo</h5>
            <div class="collapse" id="card-body-farm-photo-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    @php $photoUrls = !empty($farmLand->farm_photo_url) ? $farmLand->farm_photo_url : []; @endphp
                    @foreach($photoUrls as $photoUrl)
                        <img src="{{ $photoUrl }}" class="d-block mb-3" style="max-width: 100%">
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endforeach

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function()
        {   
            $('.js-view-farm').click(function () {
                var dataFarmId = $(this).attr('data-farm-id');
                $(`[data-farmland-id=${dataFarmId}]`).removeClass('d-none');
                $('.js-farm-table').hide();
            });

            $('.back').click(function() {
                $('.farm-detail').addClass('d-none');
                $('.js-farm-table').show();
            });
        });

        function initMap() {
            const myLatLng = { lat: 10.7719514, lng: 106.726354 };
            const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 8,
            center: myLatLng,
            mapTypeId: 'satellite',
            });

            var locations = {{ Js::from($farmLand ?? $farmLands) }};
            console.log('locations', locations);
  
            var infowindow = new google.maps.InfoWindow();
  
            var marker, i;
              
                  marker = new google.maps.Marker({
                    
                    position: new google.maps.LatLng(locations['lat'], locations['lng']),
                    map: map
                  });
                    
                  // const flightPlanCoordinates = locations[i]['plot_data'];

                  // const flightPath = new google.maps.Polyline({
                  //   path: flightPlanCoordinates,
                  //   geodesic: true,
                  //   strokeColor: "#FF0000",
                  //   strokeOpacity: 1.0,
                  //   strokeWeight: 2,
                  // });
                  var myTrip = new Array();
                  if((locations['plot_data']).length > 0)
                  {
                    for (j = 0; j < locations['plot_data'].length; j++) { 
                      console.log(locations['plot_data'][j]['lat']);
                      myTrip.push(new google.maps.LatLng(locations['plot_data'][j]['lat'], locations['plot_data'][j]['lng']));
                    }
                    myTrip.push(new google.maps.LatLng(locations['plot_data'][0]['lat'], locations['plot_data'][0]['lng']));
                  } 
        
                  const flightPath = new google.maps.Polyline({
                    path: myTrip,
                    geodesic: true,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    });
                  flightPath.setMap(map);
                  
                  const content = document.createElement("div");
                  content.classList.add("window_form_farmer");

                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      infowindow.setContent(content);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
        }
        window.initMap = initMap;

    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key={{env('GOOGLE_MAP_KEY')}}&callback=initMap&v=weekly"></script>


@endpush