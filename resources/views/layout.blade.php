<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
    <link rel="shortcut icon" href="storage/img/Favicon.svg" type="image/x-icon">
    <title>@yield('title')</title>
</head>
<body>
    <div class="Content__header">
        <img class="Content__header__logo" onclick="document.location='/'" src="storage/img/Logo.svg" height="35px">
        <div class="Content__header__title" onclick="document.location='/'">TWITTOR</div>
        <img class="Content__header__profile" onclick="document.location='/signin'" src="storage/img/Profile.svg"  height="20px">
    </div>
    <div class="Content__center">
        @yield('main_content')
    </div>
</body>
</html>
