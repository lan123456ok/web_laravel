@extends('layout.master')
@section('content')
<form action="{{ route('course.store') }}" method="post">
    @csrf
    Tên lớp
    <input type="text" name="name" value="{{old('name')}}">
    @if($errors->has('name'))
        <span class="danger">
            {{ $errors->first('name') }}
        </span>
    @endif
    <br>
    <button>Thêm</button>
</form>
@endsection
