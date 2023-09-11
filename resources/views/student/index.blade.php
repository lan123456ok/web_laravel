@extends('layout.master')
@push('css')
    <link
        href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.6/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/date-1.5.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/sc-2.2.0/sb-1.5.0/sl-1.7.0/datatables.min.css"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="card">
        <div class="card-body">

            <a class="btn btn-success" href="{{ route('student.create') }}">
                Thêm
            </a>
{{--            <caption>--}}
{{--                <form class="float-right form-group form-inline">--}}
{{--                    <label class="mr-2">Search: </label>--}}
{{--                    <input type="search" class="form-control" name="q" value="{{ $search }}">--}}
{{--                </form>--}}
{{--            </caption>--}}
            <div class="form-group">
                <select id="select-course-name"></select>
            </div>
            <div class="form-group">
                <select id="select-status-name" class="form-control">
                    <option value="all">Tất cả</option>
                    @foreach($arrStudentStatus as $option => $value)
                        <option value="{{ $value }}">
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>
            <table class="table table-striped" id="table-index">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>Tuổi</th>
                        <th>Tình trạng</th>
                        <th>Avatar</th>
                        <th>Lớp</th>
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
            $("#select-course-name").select2({
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
                dom: 'Blrftip',
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
                lengthMenu: [20, 30, 40, 50, 100],
                processing: true,
                serverSide: true,
                ajax: '{{ route('student.api') }}',
                columnDefs: [
                    { className: "not-export", "targets": [3]}
                ],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'birthdate',name: 'birthdate'},
                    {data: 'age', name: 'age'},
                    {data: 'status', name: 'status'},
                    {
                        data: 'avatar',
                        targets: 6,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            if(!data){
                                return '';
                            }
                            return `<img src="{{ public_path() }}/${data}">`
                        }
                    },
                    {data: 'course_name', name: 'course_name'},
                    {
                        data: 'edit',
                        targets: 8,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-primary" href="${data}">Sửa</a>`;
                            }
                    },
                    {
                        data: 'destroy',
                        targets: 9,
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
            $('#select-course-name').change(function () {
                table.column(7).search( $(this).val() ).draw();
            });
            $('#select-status-name').change(function () {
                let value =  $(this).val();
                // if(value === "all") {
                //     table.column(5).search( '' ).draw();
                // }else {
                //     table.column(5).search(value).draw();
                // } front-end xu ly
                table.column(5).search(value).draw();
            });
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

