<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sơ đồ sân khấu</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
    text-align: center;
    font-family: Arial, sans-serif;
    background-color: #fffbe5;
}

h1 {
    color: #2d6a4f;
}

.stage-layout {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr 1fr;
    grid-template-rows: auto auto auto auto;
    gap: 10px;
    width: 60%;
    margin: auto;
}

.stage {
    grid-column: 2 / 4;
    background-color: #f4a261;
    padding: 20px;
    font-weight: bold;
    font-size: 24px;
    color: #2d6a4f;
    border-radius: 10px;
}

.basic {
    background-color: #e76f51;
    padding: 10px;
    color: white;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
}

.basic.top {
    grid-row: 1;
    grid-column: span 2;
}

.basic.bottom {
    grid-column: 2 / 4;
    grid-row: 4;
}

.fans-zone {
    background-color: #264653;
    color: white;
    padding: 15px;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
}

.fans-zone.left {
    grid-row: 2;
    grid-column: 1;
}

.fans-zone.right {
    grid-row: 2;
    grid-column: 4;
}

.fans-zone.bottom {
    grid-column: 2 / 4;
    grid-row: 3;
}

.vip {
    background-color: #f4d35e;
    padding: 15px;
    font-weight: bold;
    text-align: center;
    border-radius: 5px;
    grid-row: 3;
}
    </style>
</head>
<body>
    <h1>SƠ ĐỒ SÂN KHẤU</h1>
    <div class="stage-layout">
        <div class="basic top">BASIC</div>
        <div class="basic top">BASIC</div>
        <div class="fans-zone left">FANS ZONE</div>
        <div class="stage">SÂN KHẤU</div>
        <div class="fans-zone right">FANS ZONE</div>
        <div class="vip">VIP</div>
        <div class="vip">VIP</div>
        <div class="fans-zone bottom">
            <!-- 40 Ghế -->
            <script>
                for (let i = 1; i <= 40; i++) {
                    document.write(`<div class='seat'><i class='fa fa-chair'></i></div>`);
                }
            </script>
        </div>
        <div class="basic bottom">BASIC</div>
    </div>
</body>
</html>