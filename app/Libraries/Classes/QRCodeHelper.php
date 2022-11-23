<?php
/**
   * QRCodeHelper class
   * 
   * @author     Erdenebayar <erdenebayar@smartdata.mn>
   * @since 	 2021-05-19
   */

use Milon\Barcode\DNS2D as DNS2D;

class QRCodeHelper extends DNS2D
{
    public function getBarcodePNG($code, $type, $w = 3, $h = 3, $color = array(0, 0, 0)) {
        if (!$this->store_path) {
            $this->setStorPath(app('config')->get("barcode.store_path"));
        }
        //set barcode code and type
        $this->setBarcode($code, $type);
        // calculate image size
        $width = ($this->barcode_array['num_cols'] * $w);
        $height = ($this->barcode_array['num_rows'] * $h);
        if (function_exists('imagecreate')) {
            // GD library
            $imagick = false;
            $png = imagecreate($width, $height);
            $bgcol = imagecolorallocate($png, 255, 255, 255);
            imagecolortransparent($png, 1);
            $fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
        } elseif (extension_loaded('imagick')) {
            $imagick = true;
            $bgcol = new \imagickpixel('rgb(255,255,255');
            $fgcol = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
            $png = new \Imagick();
            $png->newImage($width, $height, 'none', 'png');
            $bar = new \imagickdraw();
            $bar->setfillcolor($fgcol);
        } else {
            return false;
        }
        // print barcode elements
        $y = 0;
        // for each row
        for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
            $x = 0;
            // for each column
            for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
                if ($this->barcode_array['bcode'][$r][$c] == 1) {
                    // draw a single barcode cell
                    if ($imagick) {
                        $bar->rectangle($x, $y, ($x + $w), ($y + $h));
                    } else {
                        imagefilledrectangle($png, $x, $y, ($x + $w), ($y + $h), $fgcol);
                    }
                }
                $x += $w;
            }
            $y += $h;
        }
        ob_start();
        // get image out put

        if ($imagick) {
            $png->drawimage($bar);
            echo $png;
        } else {
            imagepng($png);
            imagedestroy($png);
        }
        $image = ob_get_clean();
        $image = base64_encode($image);
        //$image = 'data:image/png;base64,' . base64_encode($image);
        return $image;
    }

}