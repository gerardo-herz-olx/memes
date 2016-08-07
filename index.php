<?php
class Image {
    const LANDSCAPE = 1;
    const PORTRAIT = 0;
    protected $resource;
    protected $width;
    protected $height;
    protected $type;

    public function __construct($path)
    {
        $info = getimagesize($path);
        $this->width = $info[0];
        $this->height = $info[1];

        if ($info['mime'] == 'image/png') {
            $this->resource = imagecreatefrompng($path);
        } else {
            $this->resource = imagecreatefromjpeg($path);
        }
        if ($this->isLandscape()) {
            $this->type = self::LANDSCAPE;
        } else {
            $this->type = self::PORTRAIT;
        }
    }

    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    public function isLandscape()
    {
        return $this->width > $this->height;
    }

    public function getType()
    {
        return $this->type;
    }

    public function ensureMaxAxis()
    {
        if ($this->isLandscape()) {
            if ($this->getWidth() <= 600) {
                return;
            }
            $ratio = (600 / $this->getWidth());
            $newwidth = 600;
            $newheight = min($this->getHeight() * $ratio, 300);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $this->getResource(), 0, 0, 0, 0, $newwidth, $newheight, $this->getWidth(), $this->getHeight());
            $this->resource = $dst;
            $this->width = 600;
            $this->height = $newheight;
        } else {
            if ($this->getHeight() <= 600) {
                return;
            }
            $ratio = (600 / $this->getHeight());
            $newheight = 600;
            $newwidth = min($this->getWidth() * $ratio, 300);
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $this->getResource(), 0, 0, 0, 0, $newwidth, $newheight, $this->getWidth(), $this->getHeight());
            $this->resource = $dst;
            $this->width = $newwidth;
            $this->height = 600;
        }

    }
}

function addLabel(Image $image, Image $label)
{
    $xOffset = ($image->getWidth() - $label->getWidth()) / 2;
    //$yOffset = $image->getHeight() - $label->getHeight() - 10;
    $yOffset = ($image->getHeight() - $label->getHeight()) / 2;
    imagecopymerge($image->getResource(), $label->getResource(), $xOffset, $yOffset, 0, 0, $label->getWidth(), $label->getHeight(), 100);
}

if (isset($_FILES['image1']) && ! empty($_FILES['image1']['tmp_name']) && isset($_FILES['image2']) && ! empty($_FILES['image2']['tmp_name'])) {
    $image1 = new Image($_FILES['image1']['tmp_name']);
    $image2 = new Image($_FILES['image2']['tmp_name']);

    if ($image1->getType() != $image2->getType()) {
        throw new InvalidArgumentException("Image types mismatch.");
    }

    $image1->ensureMaxAxis();
    $image2->ensureMaxAxis();

    $w1 = $image1->getWidth();
    $w2 = $image2->getWidth();
    $h1 = $image1->getHeight();
    $h2 = $image2->getHeight();
    $tiene = new Image('tiene.png');
    $quiere = new Image('quiere.png');

    $newImage = imagecreatetruecolor(600,600);

    addLabel($image1, $tiene);
    addLabel($image2, $quiere);

    if (!$image1->isLandscape()) {
        imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
        imagecopyresampled($newImage, $image2->getResource(), $w1, 0, 0, 0, $w2, $h2, $w2, $h2);
    } else {
        imagecopyresampled($newImage, $image1->getResource(), 0, 0, 0, 0, $w1, $h1, $w1, $h1);
        imagecopyresampled($newImage, $image2->getResource(), 0, $h1, 0, 0, $w2, $h2, $w2, $h2);
    }


    /**
     * Save wherever you want the output image
     */
    $out = 'out/out' . time() . '.jpg';
    imagejpeg($newImage, $out);
    ?>
    <img src="<?php echo $out ?>" />
    <?php
} else {
    ?>
    <form method="post"  enctype="multipart/form-data">
        <input type="file" name="image1" id="image1" accept="image/*"  />
        <input type="file" name="image2" id="image2" accept="image/*"  />
        <input type="submit"/>
    </form>
    <?php
}

