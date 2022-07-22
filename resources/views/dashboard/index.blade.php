@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
        crossorigin=""></script>
    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap"></script>

    <style>
        #map {
            min-height: 420px;
        }

    </style>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card h-md-100">
                <div class="card-body">
                    <div class="row h-100 justify-content-between g-0">
                        <div class="col-5 col-sm-6 col-xxl pe-2">
                            <h6 class="mt-1">Market Share</h6>
                            <div class="fs--2 mt-3">
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-primary"></span><span
                                            class="fw-semi-bold">Samsung</span></div>
                                    <div class="d-xxl-none">33%</div>
                                </div>
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-info"></span><span
                                            class="fw-semi-bold">Huawei</span></div>
                                    <div class="d-xxl-none">29%</div>
                                </div>
                                <div class="d-flex flex-between-center mb-1">
                                    <div class="d-flex align-items-center"><span class="dot bg-300"></span><span
                                            class="fw-semi-bold">Apple</span></div>
                                    <div class="d-xxl-none">20%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto position-relative">
                            <div class="echart-market-share"></div>
                            <div class="position-absolute top-50 start-50 translate-middle text-dark fs-2">
                                26M</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-md-100">
                <div class="card-header d-flex flex-between-center pb-0">
                    <h6 class="mb-0">Weather</h6>
                    <div class="dropdown font-sans-serif btn-reveal-trigger"><button
                            class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal"
                            type="button" id="dropdown-weather-update" data-bs-toggle="dropdown" data-boundary="viewport"
                            aria-haspopup="true" aria-expanded="false"><span
                                class="fas fa-ellipsis-h fs--2"></span></button>
                        <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-weather-update">
                            <a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="row g-0 h-100 align-items-center">
                        <div class="col">
                            <div class="d-flex align-items-center"><img class="me-3"
                                    src="{{ asset('/') }}assets/img/icons/weather-icon.png" alt="" height="60" />
                                <div>
                                    <h6 class="mb-2">New York City</h6>
                                    <div class="fs--2 fw-semi-bold">
                                        <div class="text-warning">Sunny</div>Precipitation: 50%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-center ps-2">
                            <div class="fs-4 fw-normal font-sans-serif text-primary mb-1 lh-1">31&deg;
                            </div>
                            <div class="fs--1 text-800">32&deg; / 25&deg;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="row g-0">
        <div class="col-sm-12">
            <div class="card h-100">
                <div class="card-header bg-light py-2">
                    <div class="row flex-between-center">
                        <div class="col-auto">
                            <h6 class="mb-0">Markers</h6>
                        </div>
                        <div class="col-auto d-flex"><a class="btn btn-link btn-sm me-2" href="#!">View
                                Details</a>
                            <div class="dropdown font-sans-serif btn-reveal-trigger"><button
                                    class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal"
                                    type="button" id="dropdown-top-products" data-bs-toggle="dropdown"
                                    data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span
                                        class="fas fa-ellipsis-h fs--2"></span></button>
                                <div class="dropdown-menu dropdown-menu-end border py-2"
                                    aria-labelledby="dropdown-top-products"><a class="dropdown-item" href="#!">View</a><a
                                        class="dropdown-item" href="#!">Export</a>
                                    <div class="dropdown-divider"></div><a class="dropdown-item text-danger"
                                        href="#!">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body h-100">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script>
        if(defaultMapType == 'leaflet'){
            leafletMap();
        } else {
            gMap();
        }

        function gMap(){
            const myLatLng = {
                lat: -2.6154385,
                lng: 121.1170547
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: myLatLng,
            });

            var infowindow = new google.maps.InfoWindow();

            var marker, i;

            $.ajax({
                type: 'get',
                url: '{{ url('list-tower') }}',
                dataType: 'json',
                beforeSend: function() {
                    // $loader.show();
                },
                success: function(response) {
                    var locations = response.data;
                    $.each(response.data, function(index, value) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(value.lat, value.long),
                            map: map
                        });

                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {
                                var content = '<div class="row ps-3" style="width:100%">\
                                        <div class="col-sm-7 py-2">\
                                            <li>'+ value.tower_code +'</li>\
                                            <li>'+ value.tower_name +'</li>\
                                            <li>'+ value.tower_owner +'</li>\
                                            <li>Latitude : '+ value.lat +'</li>\
                                            <li>Longitude : '+ value.long +'</li>\
                                        </div>\
                                        <div class="col-sm-5">\
                                            <img width="100%" src="{{ asset("storage") }}' + '/' + value.tower_image + '">\
                                        <div>\
                                    </div>';

                                infowindow.setContent(content);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    });
                }
            });
        }

        function leafletMap(){
            var map = L.map('map').setView([-2.6154385, 121.1170547], 10);

            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                    id: 'mapbox/streets-v11',
                    tileSize: 512,
                    zoomOffset: -1
                }).addTo(map);

            $.ajax({
                type: 'get',
                url: '{{ url('list-tower') }}',
                dataType: 'json',
                beforeSend: function() {
                    // $loader.show();
                },
                success: function(response) {
                    var locations = response.data;

                    $.each(locations, function(index, value) {
                        var content = '<div class="row" style="width:220px">\
                                            <div class="col-sm-12">\
                                                <img width="100%" src="{{ asset("storage") }}' + '/' + value.tower_image + '">\
                                            <div>\
                                            <div class="col-sm-12 py-2">\
                                                <li>'+ value.tower_code +'</li>\
                                                <li>'+ value.tower_name +'</li>\
                                                <li>'+ value.tower_owner +'</li>\
                                                <li>Latitude : '+ value.lat +'</li>\
                                                <li>Longitude : '+ value.long +'</li>\
                                            </div>\
                                        </div>';

                        marker = new L.marker([value.lat, value.long])
                            .bindPopup(content)
                            .addTo(map);
                    });
                }
            })
        }
    </script>
@endsection

