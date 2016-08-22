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

    public function resizeHeight($height)
    {
        if ($this->getHeight() <= $height) {
            return;
        }
        $ratio = ($height / $this->getHeight());
        $newheight = $height;
        $newwidth = $this->getWidth() * $ratio;
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $this->getResource(), 0, 0, 0, 0, $newwidth, $newheight, $this->getWidth(), $this->getHeight());
        $this->resource = $dst;
        $this->width = $newwidth;
        $this->height = $height;
    }

    public function resizeWidth($width)
    {
        if ($this->getWidth() <= $width) {
            return;
        }
        $ratio = ($width / $this->getWidth());
        $newheight = $this->getHeight() * $ratio;
        $newwidth = $width;
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $this->getResource(), 0, 0, 0, 0, $newwidth, $newheight, $this->getWidth(), $this->getHeight());
        $this->resource = $dst;
        $this->width = $width;
        $this->height = $newheight;
    }

    public function cropWidth($newWidth)
    {
        $thumb_width = $newWidth;
        $thumb_height = $this->height;
        $original_aspect = $this->width / $this->height;
        $thumb_aspect = $thumb_width / $thumb_height;
        if ( $original_aspect >= $thumb_aspect )
        {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $this->width / ($this->height/ $thumb_height);
        }
        else
        {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $this->height / ($this->width / $thumb_width);
        }
        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
        imagecopyresampled($thumb,
            $this->resource,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $this->width, $this->height);

        $this->resource = $thumb;
        $this->width = $newWidth;
    }

    public function cropHeight($newHeight)
    {
        $thumb_width = $this->width;
        $thumb_height = $newHeight;
        $original_aspect = $this->width / $this->height;
        $thumb_aspect = $thumb_width / $thumb_height;
        if ( $original_aspect >= $thumb_aspect )
        {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $thumb_height;
            $new_width = $this->width / ($this->height/ $thumb_height);
        }
        else
        {
            // If the thumbnail is wider than the image
            $new_width = $thumb_width;
            $new_height = $this->height / ($this->width / $thumb_width);
        }
        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
        imagecopyresampled($thumb,
            $this->resource,
            0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
            0 - ($new_height - $thumb_height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $this->width, $this->height);

        $this->resource = $thumb;
        $this->height = $newHeight;
    }
}