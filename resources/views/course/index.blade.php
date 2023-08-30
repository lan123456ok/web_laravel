@extends('layout.master')
@push('css')
    <link
        href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/sc-2.2.0/sb-1.5.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="card">
        @if ($errors->any())
            <div class="card-header">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="card-body">

            <a class="btn btn-success" href="{{ route('course.create') }}">
                Thêm
            </a>
{{--            <caption>--}}
{{--                <form class="float-right form-group form-inline">--}}
{{--                    <label class="mr-2">Search: </label>--}}
{{--                    <input type="search" class="form-control" name="q" value="{{ $search }}">--}}
{{--                </form>--}}
{{--            </caption>--}}
            <div class="form-group">
                <select id="select-name"></select>
            </div>
            <table class="table table-striped" id="table-index">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên lớp</th>
                        <th>Ngày tạo</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>


            </table>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/sc-2.2.0/sb-1.5.0/sl-1.7.0/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function(){
            $("#select-name").select2({
                ajax: {
                     url: "{{ route('course.api.name') }}",
                     dataType: 'json',
                     data: function (params) {
                        return {
                        q: params.term
                        };
                     },
                     processResults: function (data, params) {
                        return {
                            results: $.map(data, function (item) {
                                 return {
                                    text: item.name,
                                    id: item.id
                                 }
                            })
                        };
                     },
                },
                placeholder: 'Nhập tên cần tìm'
            });
            var buttonCommon = {
                exportOptions: {
                    columns: ':visible :not(.not-export)'
                }
            };
            let table = $('#table-index').DataTable({
                dom: 'Blrtip',
                select: true,
                buttons: [
                    $.extend( true, {}, buttonCommon, {
                        extend: 'copyHtml5'
                    }),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'csvHtml5'
                    }),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'excelHtml5'
                    }),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'pdfHtml5'
                    }),
                    $.extend( true, {}, buttonCommon, {
                        extend: 'print'
                    }),
                    'colvis'
                ],
                lengthMenu: [1, 5 , 10 , 20, 30, 40, 50],
                processing: true,
                serverSide: true,
                ajax: '{{ route('course.api') }}',
                columnDefs: [
                    { className: "not-export", "targets": [3]}
                ],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {
                        data: 'edit',
                        targets: 3,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-primary" href="${data}">Sửa</a>`;
                            }
                    },
                    {
                        data: 'destroy',
                        targets: 4,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return `<form action="${data}" method="post">
                                        @csrf
                                        @method('DELETE')
                                         <button type="button" class="btn btn-delete btn-danger">Xóa</button>
                                    </form>`;
                            }
                    },
                ]
            });
            $('#select-name').change(function () {
                table.columns(0).search( this.value ).draw();
            } );
            $(document).on('click','.btn-delete', function(){
                let row = $(this).parents('tr');
                let form = $(this).parents('form');
                $.ajax({
		            url: form.attr('action'),
		            type: 'POST',
		            dataType: 'json',
		            data: form.serialize(),
		            success: function() {
			            console.log("success");
			            table.draw();
		            },
		            error: function() {
			            console.log("error");
		            }
		        });
            });
        });
    </script>
@endpush

