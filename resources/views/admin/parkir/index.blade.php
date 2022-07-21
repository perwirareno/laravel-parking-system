@extends('admin.template')

@section('content')

<section class="content">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $judul }}</h3>
                    <div class="card-tools">
                        @if(Auth::user()->roleuser !== 1)
                        <button type="button" class="btn btn-success btn-sm" id="createNew"><i class="fas fa-plus"></i> Tambah Data Kendaraan</button>
                        @else
                        <input type="text" id="datefilter" name="datefilter" placeholder="Filter Tanggal..." value="" />
                        &nbsp; &nbsp;
                        <a href="/CetakLaporan/start_date=&end_date=" class="btn btn-primary btn-sm" id="printreport" target="_blank"><i class="fas fa-print"></i> Cetak Data Parkir</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">

                    <table id="tabledata" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Nomor Polisi</th>
                                <th width="10%">Kode Unik</th>
                                <th width="15%">Jam Masuk</th>
                                <th width="15%">Jam Keluar</th>
                                <th width="10%">Biaya</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>

        </div>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-violet">
                    <h4 class="modal-title" id="modelHeading">Master Parkir</h4>
                </div>
                <div class="modal-body">
                    <section class="panel">
                        <div class="panel-body">
                            <form id="productForm" name="productForm" class="form-horizontal">
                                <input type="hidden" id="id">

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Nomor Polisi</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="no_polisi" id="no_polisi" class="form-control" placeholder="Nomor Polisi" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Status</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="inorout" required>
                                            <option value="">Pilih Status</option>
                                            <option value="0">Masuk</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-danger btn-icon" data-dismiss="modal">
                                                <i class="far fa-window-close"></i> Tutup
                                            </button>

                                            <button type="button" class="btn bg-success btn-icon" id="saveBtn" value="create">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

</section>

<script src="{{ asset('template\plugins\sweetalert2\sweetalert2.all.min.js') }}"></script>
<script>
    $("#point").hide();

    $('#role').on('change', function() {
        if (this.value != '1') {
            $("#point").show();
        } else {
            $("#point").hide();
        }
    });

    $('.date').datepicker({
        format: 'mm-dd-yyyy'
    });

    $(function() {

        // Initiate datatable
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#tabledata').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Parkir.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'no_polisi',
                    name: 'no_polisi'
                },
                {
                    data: 'kode_unik',
                    name: 'kode_unik'
                },
                {
                    data: 'jam_masuk',
                    name: 'jam_masuk'
                },
                {
                    data: 'jam_keluar',
                    name: 'jam_keluar'
                },
                {
                    data: 'biaya',
                    name: 'biaya'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Daterange filter
        $('input[name="datefilter"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));

            // Print laporan based on filter
            $('body').on('click', '#printreport', function() {

                var start_date = picker.startDate.format('DD-MM-YYYY');
                var end_date = picker.endDate.format('DD-MM-YYYY');

                var url = "{{URL::to('/CetakLaporan')}}" + "/start_date=" + start_date + "&end_date=" + end_date;
                $("#printreport").attr("href", url)
            });
        });

        $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');

            // Print laporan based on filter
            $('body').on('click', '#printreport', function() {

                var url = "{{URL::to('/CetakLaporan')}}" + "/start_date=&end_date=";
                $("#printreport").attr("href", url)
            });
        });

        // Insert data
        $('body').on('click', '#createNew', function() {
            $('#productForm').trigger("reset");
            $('#saveBtn').val("create-product");
            $('#id').val('');
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function() {

            if ($('#no_polisi').val() == "") {
                Swal.fire(
                    'Nomor Polisi Kosong!',
                    'Nomor Polisi wajib diisi',
                    'error'
                )
                return false;
            }

            if ($('#inorout').val() == "") {
                Swal.fire(
                    'Status Kosong!',
                    'Status masuk wajib diisi',
                    'error'
                )
                return false;
            }

            var fd;
            fd = new FormData();
            fd.append('id', $('#id').val());
            fd.append('no_polisi', $('#no_polisi').val());
            fd.append('inorout', $('#inorout').val());
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                data: fd,
                url: "{{ route('Parkir.store') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#productForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    Swal.fire(
                        'Data Tersimpan!',
                        '',
                        'success'
                    )
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#ajaxModel').modal('hide');
                }
            });
        });

        // Update data
        $('body').on('click', '.editParkir', function() {
            var id = $(this).data("id");

            Swal.fire({
                title: 'Update Status Kendaraan?',
                text: "Update status akan menganggap kendaraan ini sudah keluar dari parkiran",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745',
                reverseButtons: true
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        type: "GET",
                        url: "UpdateParkir/" + id,
                        success: function(data) {
                            table.draw();
                            Swal.fire(
                                'Kendaraan keluar dari parkiran!',
                                '',
                                'success'
                            )
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });

        // Delete data
        $('body').on('click', '.hapusParkir', function() {

            var id = $(this).data("id");

            Swal.fire({
                title: 'Hapus data kendaraan ini?',
                text: "Data kendaraan akan terhapus selamanya",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
                reverseButtons: true
            }).then((result) => {
                if (result.value == true) {
                    $.ajax({
                        type: "GET",
                        url: "HapusParkir/" + id,
                        success: function(data) {
                            table.draw();
                            Swal.fire(
                                'Data Terhapus!',
                                '',
                                'success'
                            )
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });
    });
</script>

@endsection