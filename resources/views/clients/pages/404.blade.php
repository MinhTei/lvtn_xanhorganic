<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Không tìm thấy trang | Xanh Organic</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'VietNamPro', sans-serif;
        background-color: #ffffff;
        height: 100vh;
        width: 100vw;
        overflow: hidden; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .error-container {
        text-align: center;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }

    .icon-box {
        font-size: 90;
        margin-bottom: 10px;
    }

    h1 {
        font-size: 100px;
        color: #90c33c;
        line-height: 1;
        margin-bottom: 10px;
        font-weight: 800;
    }

    h2 {
        color: #2d5016;
        font-size: 24px;
        margin-bottom: 15px;
    }

    p {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-block;
        padding: 14px 30px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #90c33c;
        color: white;
    }

    .btn-primary:hover {
        background-color: #7ab02c;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(144,195,60,0.4);
    }

    .btn-outline {
        background-color: white;
        color: #2d5016;
        border: 2px solid #2d5016;
    }

    .btn-outline:hover {
        background-color: #2d5016;
        color: white;
    }

    .leaf {
        position: absolute;
        opacity: 0.08;
        font-size: 200px;
        z-index: -1;
        color: #90c33c;
    }

    .leaf-1 { top: -50px; left: -50px; }
    .leaf-2 { bottom: -50px; right: -50px; }

    @media (max-width: 480px) {
        h1 { font-size: 70px; }
        .error-container { padding: 40px 25px; }
        .buttons { flex-direction: column; }
    }
</style>
</head>
<body>

    <div class="error-container" style="width: 100%;">
        <h1>404</h1>
        <h2>Ối! Không tìm thấy trang này</h2>
        <p>
            Có vẻ như sản phẩm hoặc trang bạn đang tìm không tồn tại,
            đã bị xóa hoặc đường dẫn không chính xác. 
            Hãy quay lại trang chủ để tiếp tục mua sắm nhé!
        </p>
        <div class="buttons">
            <a href="{{ route('home') }}" class="btn btn-primary"> Về Trang Chủ</a>
            <a href="#" class="btn btn-outline"> Xem Sản Phẩm</a>
        </div>
    </div>

</body>
</html>