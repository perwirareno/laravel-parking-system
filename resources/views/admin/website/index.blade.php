@extends('admin.template')

@section('content')

<section class="content">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $judul }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="createNew"><i class="fas fa-plus"></i> Tambah Website Utama</button>
                    </div>
                </div>
                <div class="card-body">

                    <table id="tabledata" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Nama</th>
                                <th width="45%">Link</th>
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
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <section class="panel">
                        <div class="panel-body">
                            <form id="productForm" name="productForm" class="form-horizontal">
                                <input type="hidden" id="id">

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Nama </label>
                                    <div class="col-lg-8">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Link </label>
                                    <div class="col-lg-8">
                                        <input type="text" name="link" id="link" class="form-control" placeholder="URL" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-lg-4">Nomor Urut</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="urut" id="urut" min="0" max="100" class="form-control" placeholder="Nomor Urut" autocomplete="off" value="" required="">
                                    </div>
                                </div>

                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn bg-danger btn-icon" data-dismiss="modal">
                                        <i class="far fa-window-close"></i> Tutup
                                    </button>

                                    <button type="button" class="btn bg-primary btn-icon" id="saveBtn" value="create">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#tabledata').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('Website.index') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'action', name: 'action', orderable: false, searchable: false
                },
            ]
        });

        $('body').on('click', '#createNew', function () {
            $('#productForm').trigger("reset");
            $('#saveBtn').val("create-product");
            $('#id').val('');
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function () {

            var fd;
            fd = new FormData();
            fd.append('id', $('#id').val());
            fd.append('name', $('#name').val());
            fd.append('link', $('#link').val());
            fd.append('urut', $('#urut').val());
            fd.append('_token', '{{ csrf_token() }}');

            $.ajax({
                data: fd,
                url: "{{ route('Website.store') }}",
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#productForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                    $('#ajaxModel').modal('hide');
                }
            });
        });

        $('body').on('click', '.editSlide', function () {
            var id = $(this).data('id');
            $.get("{{ route('Website.index') }}" +'/' + id +'/edit', function (data) {
                console.log(data)
                $('#productForm').trigger("reset");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#link').val(data.link);
                $('#urut').val(data.urut);
            })
        });

       
        $('body').on('click', '.hapusSlide', function () {

            var id = $(this).data("id");
            
            if(confirm("Are You sure want to delete !") == true){
                $.ajax({
                    type: "GET",
                    url: "HapusWebsite/"+id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
        
    });

    
</script>

@endsection
