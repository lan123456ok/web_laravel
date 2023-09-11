<form action="{{ route('process_login') }}" method="post">
    @csrf
    Email:
    <input type="email" name="email">
    <br>
    Password:
    <input type="password" name="password">
    <br>
    <button>Đăng nhập</button>
    <a href="{{ route('register') }}">
        Đăng ký
    </a>
</form>
