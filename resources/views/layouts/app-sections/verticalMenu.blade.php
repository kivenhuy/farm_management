

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">Upstream</span>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
    <li class="aiz-side-nav-item">
        <a class="aiz-side-nav-link" href="{{ route('season-masters.index') }}">
            <i class="las la-home aiz-side-nav-icon"></i>
            <span class="aiz-side-nav-text">Season Master</span>
        </a>
    </li>
    <li class="aiz-side-nav-item">
      <a class="aiz-side-nav-link" href="{{ route('crop-informations.index') }}">
          <i class="las la-home aiz-side-nav-icon"></i>
          <span class="aiz-side-nav-text">Crop Master</span>
      </a>
  </li>
    <li class="aiz-side-nav-item">
      <a href="#" class="aiz-side-nav-link">
          <i class="las la-shopping-cart aiz-side-nav-icon"></i>
          <span class="aiz-side-nav-text">Location Master</span>
          <span class="aiz-side-nav-arrow"></span>
      </a>

      <ul class="aiz-side-nav-list level-2" style="visibility: unset">
          <li class="aiz-side-nav-item">
              <a href="{{ route('country.index') }}"
                  class="aiz-side-nav-link ">
                  <span class="aiz-side-nav-text">Country</span>
              </a>
          </li>

          <li class="aiz-side-nav-item">
              <a href="{{ route('province.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">Province</span>
              </a>
          </li>
          <li class="aiz-side-nav-item">
              <a href="{{ route('district.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">District</span>
              </a>
          </li>
          <li class="aiz-side-nav-item">
              <a href="{{ route('commune.index') }}"
                class="aiz-side-nav-link ">
                <span class="aiz-side-nav-text">Commune</span>
              </a>
          </li>
      </ul>
    </li>
  </ul>
</aside>


<style>
  .aiz-side-nav-arrow::after {
    content: "\f105";
    font-family: "Line Awesome Free";
    font-weight: 900;
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.aiz-side-nav-arrow {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: end;
    -ms-flex-pack: end;
    justify-content: flex-end;
    font-size: 80%;
}

</style>