@extends('admin.template')

@section('content')

<section class="content">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $judul }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="createNew"><i class="fas fa-plus"></i> Tambah Pengguna</button>
                    </div>
                </div>
                <div class="card-body">

                    <table id="tabledata" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama</th>
                                <th width="10%">Email</th>
                                <th width="10%">Role</th>
                                <th class="text-center" width="10%">Aksi</th>
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
                    <h4 class="modal-title" id="modelHeading">Master Pengguna</h4>
                </div>
                <div class="modal-body">
                    <section class="panel">
                        <div class="panel-body">
                            <form id="productForm" name="productForm" class="form-horizontal">
                                <input type="hidden" id="id">

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Nama</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Email</label>
                                    <div class="col-lg-8">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Role</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" id="roleuser" required>
                                            <option value="">Pilih Role</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Petugas Parkir</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Password</label>
                                    <div class="col-lg-8">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Confirm Password</label>
                                    <div class="col-lg-8">
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" autocomplete="off" value="" required="">
                                        <span id='message'></span>
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

    $('#password, #confirm_password').on('keyup', function() {
        if ($('#password').val() == $('#confirm_password').val()) {
            $('#message').html('Matching').css('color', 'green');
        } else
            $('#message').html('Not Matching').css('color', 'red');
    });

    $('#role').on('change', function() {
        if (this.value != '1') {
            $("#point").show();
        } else {
            $("#point").hide();
        }
    });


    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#tabledata').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Pengguna.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roleuser',
                    name: 'roleuser'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('body').on('click', '#createNew', function() {
            $('#productForm').trigger("reset");
            $('#saveBtn').val("create-product");
            $('#id').val('');
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function() {

            if ($('#name').val() == "") {
                Swal.fire(
                    'Nama Kosong!',
                    'Nama wajib diisi',
                    'error'
                )
                return false;
            }

            if ($('#email').val() == "") {
                Swal.fire(
                    'Email Kosong!',
                    'Email wajib diisi',
                    'error'
                )
                return false;
            }

            if ($('#roleuser').val() == "") {
                Swal.fire(
                    'Role Kosong!',
                    'Silahkan pilih role',
                    'error'
                )
                return false;
            }

            if ($('#password').val() == "") {
                Swal.fire(
                    'Password Kosong!',
                    'Password wajib diisi',
                    'error'
                )
                return false;
            }

            if ($('#password').val() != $('#confirm_password').val()) {
                Swal.fire(
                    'Password Tidak Sama!',
                    'Pastikan password yang dimasukkan sama',
                    'error'
                )
                return false;
            }

            var fd;
            fd = new FormData();
            fd.append('id', $('#id').val());
            fd.append('name', $('#name').val());
            fd.append('email', $('#email').val());
            fd.append('roleuser', $('#roleuser').val());
            fd.append('password', $('#password').val());
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                data: fd,
                url: "{{ route('Pengguna.store') }}",
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

        $('body').on('click', '.editUser', function() {
            var id = $(this).data('id');
            $.get("{{ route('Pengguna.index') }}" + '/' + id + '/edit', function(data) {
                // console.log(data)
                $('#productForm').trigger("reset");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#roleuser').val(data.roleuser);
            })
        });


        $('body').on('click', '.hapusUser', function() {

            var id = $(this).data("id");

            Swal.fire({
                title: 'Hapus data pengguna ini?',
                text: "Data pengguna akan terhapus selamanya",
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
                        url: "HapusPengguna/" + id,
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