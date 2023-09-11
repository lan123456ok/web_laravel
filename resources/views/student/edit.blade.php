@extends('layout.master')
@section('content')
<form action="{{ route('course.update', $each) }}" method="post">
    @csrf
    @method('PUT')
    Tên lớp
    <input type="text" name="name" value="{{ $each->name }}">
    @if($errors->has('name'))
        <span class="danger">
            {{ $errors->first('name') }}
        </span>
    @endif
    <br>
    <button>Sửa</button>
</form>

@endsection
