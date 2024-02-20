<?php

namespace App\Utils;

class Image
{
    protected \GdImage $resource;

    public function __construct(\GdImage $resource)
    {
        $this->resource = $resource;
    }

    public static function createFromPNG($path): Image
    {
        return new self(imagecreatefrompng($path));
    }

    public static function createFromJPEG($path): Image
    {
        return new self(imagecreatefromjpeg($path));
    }

    public static function create($width, $height, $transparent = true): Image
    {
        $resource = imagecreatetruecolor($width, $height);

        // Transparent Background
        if ($transparent) {
            imagealphablending($resource, false);
            $transparency = imagecolorallocatealpha($resource, 0, 0, 0, 127);
            imagefill($resource, 0, 0, $transparency);
            imagesavealpha($resource, true);
        }

        return new self($resource);
    }

    /**
     * return int image width x.
     */
    public function getWidth(): bool|int
    {
        return imagesx($this->resource);
    }

    /**
     * return int image width y.
     */
    public function getHeight(): bool|int
    {
        return imagesy($this->resource);
    }

    public function getRGBA($x, $y): array
    {
        $rgb = $this->getColorat($x, $y);

        return imagecolorsforindex($this->resource, $rgb);
    }

    public function getAlpha($x, $y): mixed
    {
        $color = $this->getRGBA($x, $y);

        return $color['alpha'];
    }

    public function getColorat($x, $y): bool|int
    {
        return imagecolorat($this->resource, $x, $y);
    }

    public function setPixel($x, $y, $color): void
    {
        imagesetpixel($this->resource, $x, $y, $color);
    }

    public function createColor($red, $green, $blue, $alpha = 0): bool|int
    {
        return imagecolorallocatealpha($this->resource, $red, $green, $blue, $alpha);
    }

    public function rotate($degrees, $bgdColor = 0): void
    {
        $this->resource = imagerotate($this->resource, $degrees, $bgdColor);
    }

    public function merge(Image $image, $dstX = 0, $dstY = 0, $srcX = 0, $srcY = 0, $width = 20, $height = 20): void
    {
        imagecopy($this->resource, $image->getResource(), $dstX, $dstY, $srcX, $srcY, $width, $height);
    }

    /**
     * @param int $mode
     *                  IMG_FLIP_HORIZONTAL    Flips the image horizontally.
     *                  IMG_FLIP_VERTICAL    Flips the image vertically.
     *                  IMG_FLIP_BOTH        Flips the image both horizontally and vertically.
     */
    public function flip(int $mode = IMG_FLIP_VERTICAL): void
    {
        imageflip($this->resource, $mode);
    }

    public function saveAs($path, $alpha = true): void
    {
        imagesavealpha($this->resource, $alpha);
        imagepng($this->resource, $path);
    }

    public function getResource(): \GdImage
    {
        return $this->resource;
    }

    public function copy(): Image
    {
        $copy = self::create($this->getWidth(), $this->getHeight());
        $copy->merge($this);

        return $copy;
    }
}
