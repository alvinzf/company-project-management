@extends('layouts.app')
@section('content')
<style type="text/css">
    .container {
        margin-top: 150px;
    }

    table.dataTable thead th {
        border-bottom: none;
    }

    .page-item.active .page-link {
        background-color: blue !important;
        border: none;
    }

    .page-link {
        color: blue !important;
    }

    table.dataTable.no-footer {
        border-bottom: 0 !important;
    }

    h4 {
        margin-bottom: 30px;
    }

    #example_filter input {
        border-radius: 100px;
    }

    .exit {
        background: #dc3545;
        border-top-left-radius: 5% 5%;
        border-bottom-left-radius: 5% 5%;
        border-top-right-radius: 5% 5%;
        border-bottom-right-radius: 5% 5%;
    }

    .bubble {
        margin-left: -15px;

    }

    .page-item.active .page-link {
        background-color: lightgrey !important;
        border: 1px #292f4c;
    }

    .page-link {
        color: #292f4c !important;
    }
</style>


<h3>Task Saya</h3>
<div class="col-md-12 text-right mb-5">
    <a class="btn blue text-white" href="javascript:void(0)" id="createNewProduct"> Buat Task Baru</a>


</div>
<div class="col-md-12">
    <table class="table table-hover data-table">
        <thead class="table-white">
            <tr>
                <th scope="col " style="width: 1%" class="text-center">No</th>
                <th scope="col" style="width: 20%">Judul</th>
                <th scope="col" class="text-center" style="display:none">Deskripsi</th>
                <th scope="col " class="text-center  justify-content-center">Progress</th>
                <th scope="col" style="width: 11%" class="text-center">Status</th>
                <th scope="col" style="width: 9%" class="text-center">Target</th>
                <th scope="col" style="width: 9%" class="text-center">Action</t>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="productForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Judul</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Judul" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Deskripsi</label>
                        <div class="col-sm-12">
                            <textarea id="content" name="content" required="" placeholder="Deskripsi" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="range" name="progress" class="form-range" style="width: 80%" id="progress" value="0" min="0" max="100" step="10" oninput="this.nextElementSibling.value = this.value ">
                        <output id="progressVal" class="btn blue" style="color: #fff !important; text-decoration: none;">0</output>
                        <output class="btn bubble blue" style="color: #fff !important; text-decoration: none;">%</output>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-12">
                            <div class="btn-group dropright">
                                <select class="form-control" id="status" name="status">
                                    <option value="Direncanakan">Direncanakan</option>
                                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                    <option value="Ditunda">Ditunda</option>
                                    <option value="Terkendala">Terkendala</option>
                                    <option value="Dibatalkan">Dibatalkan</option>



                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12 control-label">Target Selesai</label>
                        <div class="col-sm-12">
                            <div class="input-group date" data-target-input="nearest">
                                <input name="target_selesai" id="target_selesai" type="date">

                            </div>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value=" {{ Auth::user()->uid }}">
                        <input type="hidden" id="name" name="name" value=" {{ Auth::user()->name }}">
                        <input type="hidden" id="jabatan" name="jabatan" value=" {{ Auth::user()->jabatan }}">
                        <input type="hidden" id="supervisi" name="supervisi" value=" {{ Auth::user()->supervisi }}">
                        <input type="hidden" id="bagian" name="bagian" value=" {{ Auth::user()->bagian }}">
                        <input type="hidden" id="bidang" name="bidang" value=" {{ Auth::user()->bidang }}">
                        <input type="hidden" id="tanggal_selesai" name="tanggal_selesai" value="">
                        <br>

                        <div class="col text-center">
                            <button type="submit" class="btn blue center" id="saveBtn" value="create">Simpan</button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            paging: true,
            info: true,
            autoWidth: false,
            responsive: true,
            // "paging": true,
            // "lengthChange": false,
            // "searching": false,
            // "ordering": true,
            // "info": true,
            // "autoWidth": false,
            // "responsive": true,
            columnDefs: [{
                    "targets": [0, 2, 3, 4, 5, 6],
                    "className": "text-center",

                },

            ],
            language: {
                paginate: {
                    next: '<span class="fas fa-arrow-right"></span>', // or '→'
                    previous: '<span class="fas fa-arrow-left"></span>' // or '←' 
                }
            },
            oLanguage: {
                "oPaginate": {
                    next: '&#8594;', // or '→'
                    previous: '&#8592;' // or '←' 
                },
                "sLengthMenu": "Tampilkan _MENU_ Item",
                "sEmptyTable": "Tidak ada data",
                "sInfoEmpty": "Tidak ditemukan",
                "sLoadingRecords": "Sedang memproses - loading...",
                "sInfo": "Menampilkan _START_ - _END_ dari _TOTAL_ Item",
                "sSearch": "Cari:",
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('my.task') }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'title',
                    name: 'title',
                    orderable: false,
                },
                {
                    data: 'content',
                    name: 'content',
                    orderable: false,
                    visible: false,
                },
                {
                    data: 'progress',
                    name: 'progress'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'target_selesai',
                    name: 'target_selesai'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $('#createNewProduct').click(function() {
            document.getElementById('title').readOnly = false;
            document.getElementById('content').readOnly = false;
            document.getElementById('progress').disabled = false;
            document.getElementById('status').disabled = false;
            document.getElementById('target_selesai').readOnly = false;
            document.getElementById('saveBtn').style.visibility = 'visible';
            $('#saveBtn').val("create-product");
            $('#id').val('');
            $('#productForm').trigger("reset");
            $('#modelHeading').html("Buat Task Baru");
            $('#ajaxModel').modal('show');
        });

        $('body').on('click', '.editProduct', function() {
            document.getElementById('title').readOnly = false;
            document.getElementById('content').readOnly = false;
            document.getElementById('progress').disabled = false;
            document.getElementById('status').disabled = false;
            document.getElementById('target_selesai').readOnly = false;
            document.getElementById('saveBtn').style.visibility = 'visible';
            var id = $(this).data('id');
            $.get("{{ route('home.index') }}" + '/' + id + '/edit', function(data) {
                $('#modelHeading').html("Edit Task");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#content').val(data.content);
                $('#progress').val(data.progress);
                $('#status').val(data.status);
                $('#target_selesai').val(data.target_selesai);
            })
        });

        $('body').on('click', '.detailProduct', function() {
            document.getElementById('title').readOnly = true;
            document.getElementById('content').readOnly = true;
            document.getElementById('progress').disabled = true;
            document.getElementById('status').disabled = true;
            document.getElementById('target_selesai').readOnly = false;
            document.getElementById('saveBtn').style.visibility = 'hidden';
            var id = $(this).data('id');
            $.get("{{ route('home.index') }}" + '/' + id + '/edit', function(data) {
                $('#modelHeading').html("Edit Task");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#id').val(data.id);
                $('#title').val(data.title);
                $('#content').val(data.content);
                $('#progress').val(data.progress);
                $('#progressVal').val(data.progress);
                $('#status').val(data.status);
                $('#target_selesai').val(data.target_selesai);

            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $(this).html('Mengirim..');

            $.ajax({
                data: $('#productForm').serialize(),
                url: "{{ route('home.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#productForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Simpan');
                }
            });
        });

        $('body').on('click', '.deleteProduct', function() {
            var id = $(this).data("id");
            var result = confirm("Yakin menghapus?");
            if (result) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('home.store') }}" + '/' + id,
                    success: function(data) {
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            } else {
                return false;
            }
        });

    });
</script>


@endsection