<?php
if ($_GET['img'] == '') {
    header('Location: index.html');
    exit(0);
}

$img = 'https://images01.olx-st.com/'.$_GET['img'];
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>OLX | OtroLoMEME! #OtroLoTieneOtroLoQuiere</title>
    <meta name="description" content="OLX">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <meta property="og:title" content="Armé mi meme %23OtroLoTieneOtroLoQuiere." />
    <meta property="og:description" content="Creá uno: otrolotieneotroloquiere.com" />
    <meta property="og:url" content="<?php echo urlencode($url) ?>" />
    <meta property="og:image" content="<?php echo urlencode($img); ?>" />
    <link rel="shortcut icon" href="https://static03.olx-st.com/mobile-webapp/images/common/favicon.ico" type="image/x-icon">

</head>
<body class="detalle">
<header>
    <div class="container">
        <div class="logo"><a href="https://www.olx.com.ar/" target="_blank"><img src="img/logo.png" alt="OLX" title="OLX"></a></div>
        <div class="meme"><a href="http://www.otrolotieneotroloquiere.com" target="_blank"><img src="img/otrolomeme.png" alt="OtroLoMEME" title="OtroLoMEME"></a></div>
        <div class="hash"><a href="https://twitter.com/hashtag/OtroLoTieneOtroLoQuiere?src=hash" target="_blank">#OtroLoTieneOtroLoQuiere</a></div>
    </div>
</header>
<section class="container">
    <h1>OtroLoHace. OtroLoComparte</h1>
    <p>Acá tenés tu meme, ¡compartilo!</p>
    <div class="resultado">
        <figure>
            <img src="<?php echo $img; ?>"  class="img-responsive" />
        </figure>
        <div class="div-social">
            <ul>
                <li>
                    <img src="img/iconos/compartir.png" alt="Compartir">
                </li>
                <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url) ?>" target="_blank" title="Compartir en Facebook"><img src="img/iconos/fb.png" alt="Facebook"></a>
                </li>
                <li>
                    <a href="http://twitter.com/share?text=Armé mi meme %23OtroLoTieneOtroLoQuiere.&url=<?php echo urlencode($url) ?>" target="_blank" title="Compartir en Twitter"><img src="img/iconos/tw.png" alt="Twitter"></a>
                </li>
                <li>
                    <a href="https://plus.google.com/share?url=<?php echo urlencode($url) ?>" target="_blank" title="Compartir en Google +"><img src="img/iconos/g.png" alt="Google +"></a>
                </li>
                <li>
                    <a href="whatsapp://send?text=Arm&eacute; mi meme %23OtroLoTieneOtroLoQuiere. <?php echo urlencode($url) ?> Cre&aacute; uno: http://olx.sm/otroloMEME" data-action="share/whatsapp/share" target="_blank" title="Compartir en Whatsapp"><img src="img/iconos/wapp.png" alt="Whatsapp"></a>
                </li>
            </ul>
        </div>
    </div>
    <p><a href="index.html" class="linea">Armá otro</a></p>
</section>
        <footer>

            <ul class="nav">
                <li><a href="https://www.facebook.com/olxargentina"><img src="img/facebook.png"></a></li>
                <li><a href="https://twitter.com/olxArgentina"><img src="img/twiter.png"></a></li>
                <li><a href="https://www.youtube.com/user/OLXar"><img src="img/youtube.png"></a></li>
                <li><a href="https://www.instagram.com/olxArgentina/"><img src="img/instagrand.png"></a></li>
            </ul>

        <p  class="btncondiciones"> <a href="condiciones.html" target="_blank">Condiciones de uso</a></p>

    </footer>

<script src="js/vendor/jquery-1.11.2.min.js"></script>
</body>
</html>


