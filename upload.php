<?php
require_once  "Image.php";
require_once "functions.php";

if (isset($_GET['log'])) {
    die(nl2br(file_get_contents('out/memes.log')));
} elseif (
    ! isset($_FILES['image1'])
    || empty($_FILES['image1']['tmp_name'])
    || ! isset($_FILES['image2'])
    || empty($_FILES['image2']['tmp_name'])) {
    header('Location: index.html');
    exit(0);
}

$image1 = new Image($_FILES['image1']['tmp_name']);
$image2 = new Image($_FILES['image2']['tmp_name']);

$tiene = new Image('tiene.png');
$quiere = new Image('quiere.png');

$newImage = imagecreatetruecolor(600,600);

if ($image1->getType() == $image2->getType()) {
    $image1->ensureMaxAxis();
    $image2->ensureMaxAxis();

    addLabel($image1, $tiene);
    addLabel($image2, $quiere);


    $w1 = $image1->getWidth();
    $w2 = $image2->getWidth();
    $h1 = $image1->getHeight();
    $h2 = $image2->getHeight();

    if (!$image1->isLandscape()) {
        imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
        imagecopyresampled($newImage, $image2->getResource(), $w1, 0, 0, 0, $w2, $h2, $w2, $h2);
    } else {
        imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
        imagecopyresampled($newImage, $image2->getResource(), 0, $h1, 0, 0, $w2, $h2, $w2, $h2);
    }
} else {
    if (abs($image1->getWidth() - $image2->getWidth()) < abs($image1->getHeight() - $image2->getHeight())) {
        if ($image1->getWidth() < $image2->getWidth()) {
            $image2->resizeWidth($image1->getWidth());
        } else {
            $image1->resizeWidth($image2->getWidth());
        }

        if ($image1->getHeight() < $image2->getHeight()) {
            $image2->cropHeight($image1->getHeight());
        } else {
            $image1->cropHeight($image2->getHeight());
        }

        $image1->ensureMaxAxis();
        $image2->ensureMaxAxis();

        addLabel($image1, $tiene);
        addLabel($image2, $quiere);



        $w1 = $image1->getWidth();
        $w2 = $image2->getWidth();
        $h1 = $image1->getHeight();
        $h2 = $image2->getHeight();

        if (!$image1->isLandscape()) {
            imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
            imagecopyresampled($newImage, $image2->getResource(), $w1, 0, 0, 0, $w2, $h2, $w2, $h2);
        } else {
            imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
            imagecopyresampled($newImage, $image2->getResource(), 0, $h1, 0, 0, $w2, $h2, $w2, $h2);
        }
    } else {
        $image1->ensureSize();
        $image2->ensureSize();

        if ($image1->getHeight() < $image2->getHeight()) {
            $image2->cropHeight($image1->getHeight());
        } else {
            $image1->cropHeight($image2->getHeight());
        }
        if ($image1->getWidth() < $image2->getWidth()) {
            $image2->cropWidth($image1->getWidth());
        } else {
            $image1->cropWidth($image2->getWidth());
        }


        $image1->ensureMaxAxis();
        $image2->ensureMaxAxis();

        addLabel($image1, $tiene);
        addLabel($image2, $quiere);



        $w1 = $image1->getWidth();
        $w2 = $image2->getWidth();
        $h1 = $image1->getHeight();
        $h2 = $image2->getHeight();

        if (!$image1->isLandscape()) {
            imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
            imagecopyresampled($newImage, $image2->getResource(), $w1, 0, 0, 0, $w2, $h2, $w2, $h2);
        } else {
            imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
            imagecopyresampled($newImage, $image2->getResource(), 0, $h1, 0, 0, $w2, $h2, $w2, $h2);
        }
    }
}


/**
 * Save wherever you want the output image
 */
$out = 'out/out' . time() . '.jpg';
imagejpeg($newImage, $out);

$cfile = curl_file_create($out,'image/jpeg','file');

$imgdata = array('file' => $cfile);
$target_url = 'https://api.olx.com/v1.0/users/images';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $target_url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true); // enable posting
curl_setopt($curl, CURLOPT_POSTFIELDS, $imgdata); // post images
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
$r = curl_exec($curl);
curl_close($curl);
$response = json_decode($r, true);
$url = 'https://images01.olx-st.com/' . $response['url'];
file_put_contents("out/memes.log", $url . "\n", FILE_APPEND);
unlink($out);
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
    <meta property="og:title" content="Armé mi meme #OtroLoTieneOtroLoQuiere." />
    <meta property="og:description" content="Creá uno: otrolotieneotroloquiere.com" />
    <meta property="og:url" content="<?php echo urlencode($url) ?>" />
    <meta property="og:image" content="<?php echo urlencode($url) ?>" />
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
            <img src="<?php echo $url ?>"  class="img-responsive" />
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
                    <a href="whatsapp://send?text=Arm&eacute; mi meme #OtroLoTieneOtroLoQuiere. Cre&aacute; uno: http://www.otrolotieneotroloquiere.com/  <?php echo urlencode($url) ?>" data-action="share/whatsapp/share" target="_blank" title="Compartir en Whatsapp"><img src="img/iconos/wapp.png" alt="Whatsapp"></a>
                </li>
            </ul>
        </div>
    </div>
    <p><a href="index.html" class="linea">Armá otro</a></p>
</section>
    <footer>
        <p style="float:left; margin:20px;"><a href="https://www.olx.com.ar/" target="_blank">#OtroLoTieneOtroLoQuiere</a></p>
        <p style="" class="btncondiciones"> <a href="condiciones.html" target="_blank">Condiciones de uso</a></p>

    </footer>
<script src="js/vendor/jquery-1.11.2.min.js"></script>
</body>
</html>


