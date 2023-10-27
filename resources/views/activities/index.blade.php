@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container mt-5">
      <h2>Laravel Google Maps Multiple Markers Example - ItSolutionStuff.com</h2>
      <div id="map"></div>
    </div>
@stop

@push('scripts')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}" ></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('Google_api_key') }}&callback=initMap" ></script>
<script type="text/javascript">
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
                    ajax: "{{route('staff.dtajax')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    columns: [
                          {data: 'first_name', name: 'staff_name', render: function(data,type,row){
                              return row.first_name + " " + row.last_name
                          }},
                          {data: 'phone_number', name: 'phone_number',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'email', name: 'email',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'gender', name: 'gender',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'status', name: 'status',render: function (data) {
                            return (data=="active")?"Active":"Block";
                          }},
                      ]
        });
    });

    
</script>

<script type="text/javascript">
  function initMap() {
    const myLatlng = { lat: -25.363, lng: 131.044 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 4,
      center: myLatlng,
    });
    // Create the initial InfoWindow.
    let infoWindow = new google.maps.InfoWindow({
      content: "Click the map to get Lat/Lng!",
      position: myLatlng,
    });

    infoWindow.open(map);
    // Configure the click listener.
    map.addListener("click", (mapsMouseEvent) => {
      // Close the current InfoWindow.
      infoWindow.close();
      // Create a new InfoWindow.
      infoWindow = new google.maps.InfoWindow({
        position: mapsMouseEvent.latLng,
      });
      infoWindow.setContent(
        JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
      );
      infoWindow.open(map);
    });
  }

  window.initMap = initMap;
</script>
@endpush