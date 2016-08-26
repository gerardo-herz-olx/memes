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

$newImage = imagecreatetruecolor(600, 600);

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

$cfile = curl_file_create($out, 'image/jpeg', 'file');

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

header('Location: meme.php?img='.$response['url']);
