<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h1>Xin chào khách hàng,{{$user->name}}</h1>
    <p>Chúc mừng bạn đã đăng ký thành công tài khoản tại website của chúng tôi. vui lòng kích hoạt tài khoản để có thể đăng nhập </p>
    <a href="{{url('/activate/'.$token)}}"style="color:blue;padding:20px 40px;font-size:20px">Kích hoạt tài khoản</a>
    <p>Trân trọng!</p>
    <p>Cửa hàng thực phẩm hữu cơ</p>
</body>
</html>