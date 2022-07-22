@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('/') }}vendors/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
        integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
        integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
        crossorigin=""></script>

    <style>
        #map {
            min-height: 500px;
        }

    </style>
@endsection

@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <div class="row flex-between-end">
                <div class="col-auto align-self-center">
                    <h5 class="mb-0">{{ $menu }}</h5>
                </div>
                <div class="col-auto ms-auto">
                    <button id="addModal" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i>
                        Add</button>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <nav style="--falcon-breadcrumb-divider: '»';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $menu }}</li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="card mb-3">
        <div class="bg-holder d-none d-lg-block bg-card"></div>
        <div class="card-body position-relative">
            <div class="row">
                <div class="col-lg-12">
                    <div id="tableExample2">
                        <div class="table-responsive scrollbar pb-4">
                            <table class="table table-bordered table-hover table-striped fs--1 mb-0" id="example">
                                <thead class="bg-200 text-900">
                                    <tr>
                                        <th style="width: 20px" class="text-center">No.</th>
                                        <th>TC</th>
                                        <th>Nama Tower</th>
                                        <th>Tower Owner</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th style="width: 120px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="list">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('modal')
    <div class="modal fade ajaxModal" id="ajaxModal" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false"
        data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ajaxForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-0">
                        <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                            <h4 class="mb-1" id="modal-title"></h4>
                        </div>
                        <div class="px-4 pt-3 pb-0">

                            <div class="row">
                                <div class="col-lg-8 order-2">
                                    <input type="hidden" name="id" id="id">

                                    <div class="mb-1">
                                        <label class="col-form-label" for="tower_name">Tower Name:</label>
                                        <input autofocus class="form-control" id="tower_name" type="text" name="tower_name" />
                                        <span class="text-danger error-text tower_name_err"></span>
                                    </div>

                                    <div class="mb-1">
                                        <label class="col-form-label" for="tower_owner">Tower Owner:</label>
                                        <input autofocus class="form-control" id="tower_owner" type="text" name="tower_owner" />
                                        <span class="text-danger error-text tower_owner_err"></span>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="col-form-label" for="lat">Latitude:</label>
                                            <input class="form-control" type="text" id="lat" type="number" name="lat" /> <span
                                                class="text-danger error-text lat_err"></span>
                                        </div>
                                        <div class="col-lg-6">
                                            <label class="col-form-label" for="long">Longitude:</label>
                                            <input class="form-control" type="text" id="long" type="text" name="long" />
                                            <small class="text-info">Tab untuk melihat maps.</small> 
                                            <span class="text-danger error-text long_err"></span>
                                        </div>

                                        {{-- <div class="col-lg-2">
                                            <label class="col-form-label" for="long" style="color: transparent">Longitude:</label>
                                            <button type="button" class="btn btn-primary form-control" id="btn-result-map" style="font-size:9pt" onclick="showResulMap()"><i class="fas fa-search"></i></button> 
                                        </div> --}}
                                    </div>

                                    <div class="mb-2">
                                        <label class="col-form-label" for="long">Tower Image:</label>
                                        <input class="form-control tower-image" name="tower_image" id="customFile" type="file" onchange="readURL(this);"/>
                                        <small class="text-info">Gambar tower tidak wajib.</small>
                                    </div>
                                        
                                
                                </div>

                                <div class="col-lg-4 order-1 mt-lg-3">
                                    <div class="card p-0 m-0" id="prev-image" style="background-repeat: no-repeat;height: 300px;background-image: url('{{ asset('storage/tower/ds.png') }}');background-size: cover;">

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="map-result" id="map-result"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer mt-4">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" id="saveBtn">Simpan </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade showMap" id="showMap" tabindex="-1" role="dialog" aria-hidden="true" data-keyboard="false"
        data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content position-relative">
                <div class="modal-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/') }}vendors/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}vendors/datatables/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('/') }}vendors/choices/choices.min.js"></script>

    <script>
        $('body').on('keydown', '#long', function(e) {
            if (e.which == 9) {
                e.preventDefault();
                showResulMap();
            }
        });

        function showResulMap() {
            var lat = $('#lat').val();
            var long = $('#long').val();

            $('#map-result').html('');
            $('#map-result').append('<iframe width="100%" height="250" src="https://maps.google.com/maps?q='+lat+','+long+'&hl=es;z=14&amp;output=embed"></iframe>');
        }

        function doubleOnly(event) {
            var angka = (event.which) ? event.which : event.keyCode
            if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
            return false;
            return true;
        }
    </script>

    <script>
        // preview image
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#prev-image').css('background-image','url("'+ e.target.result +'")');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        // 


        let myToast = $('.toast');
        let bsToast = new bootstrap.Toast(myToast);

        if(defaultMapType == 'gmap'){
            function showMap(lat, long) {
                $('#map').html('');
                $('#showMap').modal('show');
                $('#map').append('<iframe width="100%" height="500" src="https://maps.google.com/maps?q='+lat+','+long+'&hl=es;z=14&amp;output=embed"></iframe>');
            }
        } else {
            $('#map').html('');
            const map = L.map('map');
            function showMap(lat, long) {

                $('#showMap').modal('show');
                
                L.tileLayer(
                    'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 22,
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        id: 'mapbox/streets-v11',
                        tileSize: 512,
                        zoomOffset: -1
                    }).addTo(map);

                marker = new L.marker([lat, long])
                    .bindPopup(tower_name)
                    .addTo(map);

                // map.locate({setView: true, maxZoom: 15});   
                map.setView(new L.LatLng(lat, long), 15)
            }
        }

        function showMap() {
                $('#showMap').modal('show');
        }

        $(function() {

            $(".ajaxModal").modal({
                backdrop: 'static',
                keyboard: false
            });

            // datatable
            var table = $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('/coordinates') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'tower_code',
                        name: 'tower_code'
                    },
                    {
                        data: 'tower_name',
                        name: 'tower_name'
                    },
                    {
                        data: 'tower_owner',
                        name: 'tower_owner'
                    },
                    {
                        data: 'lat',
                        name: 'lat'
                    },
                    {
                        data: 'long',
                        name: 'long'
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },

                ],
                "lengthMenu": [
                    [50, 100, -1],
                    [50, 100, "All"]
                ],
                ordering: true,
                order: [
                    // [1, 'desc']
                ],
                "columnDefs": [{
                        "targets": [0, 6],
                        "className": "text-center",
                    },
                    {
                        "orderable": false,
                        "targets": [0, 4, 5, 6]
                    },
                    {
                        "orderable": true,
                        "targets": [1,2,3]
                    }
                ]
            });

            // add modal show
            $('#addModal').on('click', function() {
                $('#map-result').html('');
                $('#modal-title').html("Add Coordinate");
                $('#saveBtn').text("Save");
                $('#ajaxModal').modal('show');
                $('.error-text').text('');
                $('#id').val('');
                $('#prev-image').css('background-image','url("")');
                $('#ajaxForm')[0].reset();
            });

            // // save
            $("#ajaxForm").on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('coordinates.store') }}",
                    type: 'post',
                    data: formData,
                    dataType: 'json',
                    enctype: 'multipart/form-data',
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        myLoader('body', 'Sedang memuat...');
                    },
                    success: function(response) {
                        $('.error-text').text('');
                        try {
                            if (response.status == 'success') {
                                $('#saveBtn').text("Simpan");
                                $('#ajaxModal').modal('hide');
                                $('#ajaxForm')[0].reset();
                                table.draw();

                                $('.toast-title').html('Sukses');
                                $('.toast-body').html(response.message);

                                bsToast.show();
                            } else {
                                $.each(response.message, function(key, value) {
                                    $('.' + key + '_err').text(value);
                                });
                            }
                        } catch (error) {
                            console.log(error);
                        }

                        $('body').waitMe("hide");
                    }
                });
            });

            // ubah
            $('body').on('click', '.edit', function() {
                myLoader('body', 'Sedang memuat...');
                $('.error-text').text('');
                var id = $(this).data('id');

                $.get("{{ route('coordinates.index') }}" + '/' + id + '/edit', function(data) {
                    $('#modal-title').html("Ubah {{ $menu }}");
                    $('#saveBtn').text("Update");
                    $('#id').val(data.id);
                    $('#tower_name').val(data.tower_name);
                    $('#tower_owner').val(data.tower_owner);
                    $('#lat').val(data.lat);
                    $('#long').val(data.long);
                    $('#prev-image').css('background-image','url("storage/'+ data.tower_image +'")');

                    showResulMap();

                    $('body').waitMe("hide");
                    $('#ajaxModal').modal('show');
                })
            });

            // delete
            $('body').on('click', '.delete', function() {
                var id = $(this).data("id");

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data akan dihapus secara permanen!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('coordinates.store') }}" + '/' + id,
                            dataType: 'json',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            beforeSend: function() {
                                myLoader('body', 'Sedang memuat...');
                            },
                            success: function(data) {
                                $('body').waitMe("hide");

                                Swal.fire(
                                    'Terhapus!',
                                    'Data telah dihapus.',
                                    'success'
                                );

                                table.draw();

                                $('.toast-title').html('Terhapus');
                                $('.toast-body').html('Data telah dihapus');

                                bsToast.show();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                })
            });

            // //quick login
            // $('body').on('click', '.quick-login', function() {
            // var id = $(this).data("id");

            // Swal.fire({
            // title: 'Yakin?',
            // text: "Apakah anda yakin ingin login sebagai coordinates?",
            // icon: 'question',
            // showCancelButton: true,
            // confirmButtonColor: '#3085d6',
            // cancelButtonColor: '#d33',
            // confirmButtonText: 'Ya',
            // cancelButtonText: 'Cancel'
            // }).then((result) => {
            // if (result.isConfirmed) {
            // $.ajax({
            // type: "POST",
            // url: "{{ url('auth.quick-login') }}",
            // dataType: 'json',
            // data: {
            // _token: '{{ csrf_token() }}',
            // id: id,
            // },
            // beforeSend: function() {
            // myLoader('body', 'Sedang memuat...');
            // },
            // success: function(data) {
            // $('body').waitMe("hide");
            // if (data.status == 'success') {
            // Swal.fire(
            // 'Berhasil',
            // data.message,
            // 'success'
            // );
            // setTimeout(
            // function() {
            // document.location.href =
            // '{{ url('/dashboard') }}';
            // }, 3000);
            // } else {
            // Swal.fire(
            // 'Gagal',
            // data.message,
            // 'error'
            // );
            // setTimeout(
            // function() {
            // document.location.href =
            // '{{ url('/') }}';
            // }, 3000);
            // }
            // },
            // error: function(data) {
            // console.log('Error:', data);
            // }
            // });
            // }
            // })
            // });
        });
    </script>

    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap"></script>
@endsection
