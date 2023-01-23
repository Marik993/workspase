<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHASEplus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

    <link rel="stylesheet" href="./style.css">
</head>
<body>
<header class="header py-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-2">
                <img src="./assets/Component%202.png" alt="" class="img-fluid">
            </div>
            <div class="col-md-8 d-flex flex-wrap justify-content-between">
                <a href="./index.html#about" class="menu-item">О проекте</a>
                <a href="./index.html#money" class="menu-item">Заработок</a>
                <a href="./index.html#dohodnost" class="menu-item">Доходность</a>
                <a href="./index.html#calculator" class="menu-item">Калькулятор</a>
                <a href="./index.html#apartunities" class="menu-item">Преимущества</a>
            </div>
        </div>
    </div>
</header>
<section class="py-5">
    <div class="container py-5">
        <h1 class="h1">
          Спасибо! В скором времени наши менеджеры с вами свяжуться
        </h1>
    </div>
</section>
<footer class="footer py-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md-5">
                <a class="privacy" href="./privacy.html">
                    Пользовательское соглашение
                    /
                    Политика конфиденциальности
                </a>
            </div>
            <div class="col-md-2">
                <p class="last_test">
                    CHSPLS © 2022
                </p>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
<?php 
$name = stripslashes(htmlspecialchars($_POST['name']));
$secondname = stripslashes(htmlspecialchars($_POST['second_name']));
$phone = stripslashes(htmlspecialchars($_POST['phone']));
$code = stripslashes(htmlspecialchars($_POST['code']));
$email = stripslashes(htmlspecialchars($_POST['email']));
$country = stripslashes(htmlspecialchars($_POST['country']));
$subid = stripslashes(htmlspecialchars($_POST['subid']));

    $obj = ["leads" => [[
        "email" => $email,
        "phone" => "+" . preg_replace('/[^0-9]/', '', $code . $phone),
        "firstname" => $name,
        "lastname" => $secondname,
        "fullname" => ($name . " " . $secondname),
        "country" => $country,
        "utm_campaign" => "chase",
        "comment" => "Subid: " . $subid
    ]]];

    $obj = json_encode($obj, JSON_UNESCAPED_UNICODE);

$headers = array(
    'Content-Type: application/json',
    'Authorization: eyJhbGciOiJIUzI1NiJ9.eyJhZmZpbGlhdGVfaWQiOjcsImNyZWF0ZWQiOjE2NzAwMjIyMjAsImV4cGlyYXRpb24iOjAsImJyYW5kIjoiZmluc2VjayIsInJpZ2h0cyI6WyJhZmZpbGlhdGUiXX0.4crbgs6up8E62J1i4zoj3iuTeGHRRSVDrFweZ_8sdpI',
);

$ch = curl_init('https://api.upsollit.com/affiliates/leads');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $obj);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// Если лиды не доходят до пп, раскоментируй 2 сточки ниже, это прокси
curl_setopt($ch, CURLOPT_PROXY, "162.19.161.152:10627");
curl_setopt($ch, CURLOPT_PROXYUSERPWD, "5589:e24798");
curl_setopt($ch, CURLOPT_FAILONERROR, true);
$output = curl_exec($ch);
$html = json_decode($output, 1);
if (curl_errno($ch) || $html === NULL) {
  file_put_contents("./errors.txt", curl_error($ch) . '\n', FILE_APPEND);
}
file_put_contents("./leads.txt", $obj . "\n", FILE_APPEND);

file_put_contents("./leads.txt", "'${$email}','${'+' . preg_replace('/[^0-9]/', '', $code . $phone)}','${$name}','${$secondname}','${($name . " " . $secondname)}','${$country}','Tesla_x','${$subid}'" . "\n", FILE_APPEND);
curl_close($ch);

if ($html['data']['valid'][0]['login_url']) {
  echo '<meta http-equiv="refresh" content="2; URL=' . $html['data']['valid'][0]['login_url'] . '" />';
}
exit;
?>