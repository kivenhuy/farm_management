@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container">
      <h2>All Farm Land</h2>
      <div class="card">
        <div class="card-body">
          <div class="row" style="margin-bottom: 20px">
            <div class="col-6">

              <div class="input-group input-group-md">
                <select class="form-control form-control-user" id="season"  name="season" value="" style="">
                  <option value="">Select Season</option>
                  @foreach($season_data as $sub_season_data)
                    <option value="{{$sub_season_data->id}}">{{$sub_season_data->season_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div id="map" style="height: 700px;"></div>
    </div>


    {{-- <div class="window_form_farmer">
      <div class="backgorund"></div>
      <div class="form_image_and_name">
        <div class="image">
          <img class="avatar_farmer" src="{{uploaded_asset($farm_land_data[0]->farmer_photo)}}" alt="">
        </div>
        <div class="name">
          <div>
            <p>{{$farm_land_data[0]->farmer_name}}</p>
          </div>
          <div>
            <p>{{$farm_land_data[0]->farmer_code}}</p>
          </div>
        </div>
      </div>
      <div class="form_information_cultivation">
          <div class="cultivation">
            <div>
              <img class="img_cultivation" src="https://hero.farm-angel.com/public/uploads/all/T0yt5PpBElTPbNHPStLyiJjZN8XCj9L2G1Oa0pEr.png" alt="">
            </div>
            <div class="details_cultivation">
              <div class="text_for_details">
                <label for="">Crop</label>
                <p>
                  {{$farm_land_data[0]->crop_name}}
                </p>
              </div>
              
            </div>
          </div>
          <div class="total_land_holding">
            <div>
              <img class="img_landholding" src="https://hero.farm-angel.com/public/uploads/all/68bJ8FPMziQNnYRZ1Ay51GcmqdU8lrsQYMomt0CU.png" alt="">
            </div>
            <div>
              <div class="text_for_details">
                <label for="" >Total Land Holding</label>
                <p>{{$farm_land_data[0]->actual_area}} km</p>
              </div>
            </div>
          </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Farm Name</label>
          </div>
          <div>
            {{$farm_land_data[0]->farm_name}}
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Organization</label>
          </div>
          <div>
            
          </div>
        </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Village</label>
          </div>
          <div>
            {{$farm_land_data[0]->actual_area}}
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Estiamte Harvest Date</label>
          </div>
          <div>
            {{$farm_land_data[0]->harvest_date}}
          </div>
        </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Season</label>
          </div>
          <div style="display: block;word-wrap: break-word;max-width: 145px;">
            {{$farm_land_data[0]->season_period_from}} to {{$farm_land_data[0]->season_period_to}}
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Yield-(Kgs)</label>
          </div>
          <div>
            {{$farm_land_data[0]->est_yeild}}
          </div>
        </div>
      </div>
      <div>
        <div class="mar-all mb-2" style=" text-align: end;">
          <a href="">
              <button type="submit" name="button" value="publish" class="btn btn-primary waves-effect waves-light">View More</button>
          </a>
      </div>
      </div>
    </div> --}}
@stop
<style>
  label
  {
    color:black;
    font-weight: 900;
    margin-bottom: 6px;
  }
  p
  {
    margin-bottom: unset !important;
  }
  .window_form_farmer
  {
    background-color: azure;
    max-width: 410px;
    width: 100%;
    height: auto;
    margin-top: 6px;
  }
  .backgorund 
  {
    background-image: url('https://hero.farm-angel.com/public/uploads/all/wAHHHSSx7s8Jvd95tLN7KUx299Pn5eAHsZj9zVw5.png');
    max-height: 80px;
    height: 80px;
  }
  .form_image_and_name
  {
    padding: 0 24px;
    position: relative;
    z-index: 1;
    top: -35px;
    display: flex;
    align-items: center;
  }
  .form_image_and_name .name
  {
    margin-left: 16px;
    font-size: 18px;
    word-wrap: break-word;
    display: block;
    max-width: 200px;
    color: black;
  }
  .form_image_and_name .image{
    max-width: 115px;
    max-height: 115px;
    width: 100%;
    height: 100%;
  }
  .avatar_farmer{
    width: 115px;
    height: 115px;
    border-radius: 50%;
  }
  .img_cultivation 
  {
    max-width: 64px;
    max-height: 64px;
    width: 100%;
    height: 100%;
  }
  .img_landholding
  {
    max-width: 64px;
    max-height: 64px;
    width: 100%;
    height: 100%;
  }
  .form_information_cultivation
  {
    display: flex;
  }
  .cultivation
  {
    width: 40%;
    display: flex;
    align-items: center;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
  }
  .total_land_holding
  {
    width: 60%;
    display: flex;
    align-items: center;
    border-bottom: 1px solid black;
  }
  .text_for_details
  {
    margin-left: 8px;
  }
  .form_information_details_farmer
  {
    padding: 10px;
    display: flex;
    border-bottom: 1px solid black;
  }
  .form_information_details_farmer .form_left 
  {
    width: 50%;
  }
  .form_information_details_farmer .form_right 
  {
    width: 50%;
  }
  .mar-all
  {
    margin-top: 8px;
    margin-bottom: 20px;
  }
</style>
@push('scripts')
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script type="text/javascript">
  // import { MarkerClusterer } from "https://cdn.skypack.dev/@googlemaps/markerclusterer@2.3.1";
  $(".link").on("click", function(event) {

  if (event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2) {
    
  }
  // ... load only necessary things for normal clicks
  });

  $(document).ready(function()
  {
    $('#season').on('change', function() {
      var value = this.value;
      $.ajax
      ({
          type: "POST",
          url: "{{route('farm_land.filter_farmland')}}", 
          data:
          {
            season_id:this.value
          },
          success: function(result,value)
          {
            
            initMap(result,value);
          }
      });
    });
  });
  function initMap($data='',$key = 0) 
  {
    const myLatLng = { lat: 10.7719514, lng: 106.726354 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 8,
      center: myLatLng,
      mapTypeId:'hybrid',
    });

    // alert($data + $key);
    if($data=='' && $key == 0)
    {
      // alert('aaa');
      var locations = {{ Js::from($farm_land_data) }};
    }
    else if($data=='' && $key != 0)
    {
      var locations = $data;
    }
    else
    {
      // alert('bbb');
      var locations = $data;
    }
    
  
            var infowindow = new google.maps.InfoWindow(
              {
                disableAutoPan: true,
              }
            );
            var marker, i;
            const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            const markers = locations.map((position, i) => {
            // console.log(position);
              const label = labels[i % labels.length];
              const pinGlyph = new google.maps.LatLng(position['lat'], position['lng']);
              const marker = new google.maps.Marker({
                position: new google.maps.LatLng(position['lat'], position['lng']),
                content: pinGlyph.element,
              });

              var myTrip = new Array();
              if((position['plot_data']).length > 0)
              {
                
                for (j = 0; j < position['plot_data'].length; j++) { 
                  console.log(position['plot_data'][j]['lat']);
                  
                  myTrip.push(new google.maps.LatLng(position['plot_data'][j]['lat'], position['plot_data'][j]['lng']));
                }
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
                  content.innerHTML = `
                  <div class="backgorund"></div>
                    <div class="form_image_and_name">
                      <div class="image">
                        <img class="avatar_farmer" src="${locations[i]['farmer_photo']}" alt="">
                      </div>
                      <div class="name">
                        <div>
                          <p>${locations[i]['farmer_name']}</p>
                        </div>
                        <div>
                          <p>${locations[i]['farmer_code']}</p>
                        </div>
                      </div>
                    </div>
                    <div class="form_information_cultivation">
                        <div class="cultivation">
                          <div>
                            <img class="img_cultivation" src="https://hero.farm-angel.com/public/uploads/all/T0yt5PpBElTPbNHPStLyiJjZN8XCj9L2G1Oa0pEr.png" alt="">
                          </div>
                          <div class="details_cultivation">
                            <div class="text_for_details">
                              <label for="">Crop</label>
                              <p>
                                ${locations[i]['crop_name']}
                              </p>
                            </div>
                            
                          </div>
                        </div>
                        <div class="total_land_holding">
                          <div>
                            <img class="img_landholding" src="https://hero.farm-angel.com/public/uploads/all/68bJ8FPMziQNnYRZ1Ay51GcmqdU8lrsQYMomt0CU.png" alt="">
                          </div>
                          <div>
                            <div class="text_for_details">
                              <label for="" >Total Land Holding</label>
                              <p>${locations[i]['actual_area']} km</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Farm Name</label>
                        </div>
                        <div>
                          ${locations[i]['farm_name']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Organization</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Village</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Estiamte Harvest Date</label>
                        </div>
                        <div>
                          ${locations[i]['harvest_date']}
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Season</label>
                        </div>
                        <div style="display: block;word-wrap: break-word;max-width: 100px;">
                          ${locations[i]['season_period_from']} to ${locations[i]['season_period_to']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Yield-(Kgs)</label>
                        </div>
                        <div>
                          ${locations[i]['est_yeild']}
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="mar-all mb-2" style=" text-align: end;">
                        <a href="farmer/${locations[i]['farmer_id']}">
                            <button type="submit" name="button" value="publish" class="btn btn-primary waves-effect waves-light">View More</button>
                        </a>
                    </div>
                    </div>
              `;

              google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                  infowindow.setContent(content);
                  infowindow.open(map, marker);
                }
              })(marker, i));
              
              return marker;
            });

            // Add a marker clusterer to manage the markers.
            new markerClusterer.MarkerClusterer({ markers, map });
            for (i = 0; i < locations.length; i++) { 
                 
                  // marker = new google.maps.Marker({



                  //   position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                  //   map: map
                  // });
                  var myTrip = new Array();
                  if((locations[i]['plot_data']).length > 0)
                  {
                    
                    for (j = 0; j < locations[i]['plot_data'].length; j++) { 
                      console.log(locations[i]['plot_data'][j]['lat']);
                      
                      myTrip.push(new google.maps.LatLng(locations[i]['plot_data'][j]['lat'], locations[i]['plot_data'][j]['lng']));
                    }
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
                  content.innerHTML = `
                  <div class="backgorund"></div>
                    <div class="form_image_and_name">
                      <div class="image">
                        <img class="avatar_farmer" src="${locations[i]['farmer_photo']}" alt="">
                      </div>
                      <div class="name">
                        <div>
                          <p>${locations[i]['farmer_name']}</p>
                        </div>
                        <div>
                          <p>${locations[i]['farmer_code']}</p>
                        </div>
                      </div>
                    </div>
                    <div class="form_information_cultivation">
                        <div class="cultivation">
                          <div>
                            <img class="img_cultivation" src="https://hero.farm-angel.com/public/uploads/all/T0yt5PpBElTPbNHPStLyiJjZN8XCj9L2G1Oa0pEr.png" alt="">
                          </div>
                          <div class="details_cultivation">
                            <div class="text_for_details">
                              <label for="">Crop</label>
                              <p>
                                ${locations[i]['crop_name']}
                              </p>
                            </div>
                            
                          </div>
                        </div>
                        <div class="total_land_holding">
                          <div>
                            <img class="img_landholding" src="https://hero.farm-angel.com/public/uploads/all/68bJ8FPMziQNnYRZ1Ay51GcmqdU8lrsQYMomt0CU.png" alt="">
                          </div>
                          <div>
                            <div class="text_for_details">
                              <label for="" >Total Land Holding</label>
                              <p>${locations[i]['actual_area']} km</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Farm Name</label>
                        </div>
                        <div>
                          ${locations[i]['farm_name']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Organization</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Village</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Estiamte Harvest Date</label>
                        </div>
                        <div>
                          ${locations[i]['harvest_date']}
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Season</label>
                        </div>
                        <div style="display: block;word-wrap: break-word;max-width: 100px;">
                          ${locations[i]['season_period_from']} to ${locations[i]['season_period_to']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Yield-(Kgs)</label>
                        </div>
                        <div>
                          ${locations[i]['est_yeild']}
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="mar-all mb-2" style=" text-align: end;">
                        <a href="farmer/${locations[i]['farmer_id']}">
                            <button type="submit" name="button" value="publish" class="btn btn-primary waves-effect waves-light">View More</button>
                        </a>
                    </div>
                    </div>
                  `;

                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      infowindow.setContent(content);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
                  
            }
            
  }
  window.initMap = initMap;
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key={{env('GOOGLE_MAP_KEY')}}&callback=initMap&v=weekly">
</script>


@endpush