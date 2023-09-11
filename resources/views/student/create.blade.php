@extends('layout.master')
@section('content')
<form action="{{ route('student.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group w-25">
        Tên:
        <input type="text" class="form-control" name="name" value="{{old('name')}}">
        <br>
        Giới tính:
        <br>
        <input type="radio" name="gender" value="0" checked>Nam
        <input type="radio" name="gender" value="1">Nữ
        <br>
        Ngày sinh:
        <input type="date" class="form-control" name="birthdate">
        <br>
        Trạng thái:
        <br>
        @foreach($arrStudentStatus as $option => $value)
            <input type="radio" name="status" value="{{ $value }}"
                   @if($loop->first) checked @endif>
            {{ $option }}
        @endforeach
        <br>
        Avatar:
        <input type="file" name="avatar">
        <br>
        Lớp:
        <select name="course_id">
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @if($loop->first) checked @endif>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
    </div>

    <button>Thêm</button>
</form>
@endsection
