@php $farmLands = $farmerDetail->farm_lands->count() ? $farmerDetail->farm_lands : []; @endphp

<table class="table table-bordered js-farm-table">
    <thead>
      <tr class="bg-danger">
        <th class="text-white">Farm Name</th>
        <th class="text-white">Land Holding</th>
        <th class="text-white">Action</th>
      </tr>
    </thead>
    <tbody>
        @foreach($farmLands as $farmLand)
            <tr>
                <td>{{ ucwords($farmLand->farm_name) }}</td>
                <td>{{ number_format($farmLand->total_land_holding/100, 2) . " Ha" }}</td>
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
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-farm-information-{{ $farmLand->id}}">Farm Information</h5>
            <div class="collapse" id="card-body-farm-information-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Name</label>
                            <span class="col-md-6">{{ ucwords($farmLand?->farm_name) }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Total Land Holding</label>
                            <span class="col-md-6">{{ number_format($farmLand?->total_land_holding/100, 2) . ' Ha' }}</span>
                        </div>
                    </div>
                    <div class="form-group row border-bottom">
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Farm Land Ploting</label>
                            <span class="col-md-6">{{ number_format($farmLand?->farm_land_ploting/100, 2) . ' Ha' }}</span>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <label class="col-md-6 col-form-label fw-medium text-heading" for="">Actual Area</label>
                            <span class="col-md-6">{{ number_format($farmLand?->actual_area/100, 2) . ' Ha' }}</span>
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
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-farm-photo-{{ $farmLand->id}}">Farm Photo</h5>
            <div class="collapse" id="card-body-farm-photo-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    @php $photoUrls = !empty($farmLand->farm_photo_url) ? $farmLand->farm_photo_url : []; @endphp
                    @foreach($photoUrls as $photoUrl)
                        <img src="{{ $photoUrl }}" class="d-block mb-3">
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="farm-detail d-none" data-farmland-id={{ $farmLand->id }}>
        <div class="card">
            <h5 class="card-header fw-bold card-header-status collapsed" data-bs-toggle="collapse" data-bs-target="#card-body-plot-area-{{ $farmLand->id}}">Plot Area</h5>
            <div class="collapse" id="card-body-plot-area-{{ $farmLand->id}}">
                <div class="card-body border-bottom">
                    Implement later
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
    </script>
@endpush