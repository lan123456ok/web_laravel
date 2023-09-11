<form action="{{ route('process_register') }}" method="post">
    @csrf
    Tên:
    <input type="text" name="name">
    <br>
    Email:
    <input type="email" name="email">
    <br>
    Password:
    <input type="password" name="password">
    <br>
    <button>
        Đăng ký
    </button>
</form>
