<?php

namespace App\Helpers;

class ColorGenerator
{
    /**
     * Used to set the lower limit of RGB values.
     * The higher this value is the fewer gray tone will be generated 70+ to 100 recommended
     *
     * @var int
     */
    protected static $lowerLimit = 70;

    /**
     * Used to set the higher limit of RGB values.
     * The higher this value is the fewer gray tone will be generated 180+ to 255 recommended
     *
     * @var int
     */
    protected static $upperLimit = 255;

    /**
     * Distance between 2 selected values.
     * Colors like ff0000 and ff0001 are basically the same when it comes to human eye perception
     * increasing this value will result in more different color but will lower the color pool
     *
     * @var int
     */
    protected static $colorGap = 20;

    /**
     * Colors already generated
     *
     * @var array
     */
    protected static $generated = array();

    /**
     * @return string
     */
    public static function generate()
    {
        $failCount = 0;
        do {
        $redVector = rand(0, 1);
        $greenVector = rand(0, 1);
        $blueVector = rand(!($redVector || $greenVector), (int)(($redVector xor $greenVector) || !($redVector || $greenVector)));
        $quantiles = floor((self::$upperLimit - self::$lowerLimit) / self::$colorGap);

        $red = $redVector * (rand(0, $quantiles) * self::$colorGap + self::$lowerLimit);
        $green = $greenVector * (rand(0, $quantiles) * self::$colorGap + self::$lowerLimit);
        $blue = $blueVector * (rand(0, $quantiles) * self::$colorGap + self::$lowerLimit);
        $failCount++;
        } while (isset(self::$generated["$red,$green,$blue"]) && $failCount < 1000);

        return self::rgb($red, $green, $blue);
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return string
     */
    protected static function rgb($red, $green, $blue)
    {
        $red = base_convert($red, 10, 16);
        $red = str_pad($red, 2, '0', STR_PAD_LEFT);

        $green = base_convert($green, 10, 16);
        $green = str_pad($green, 2, '0', STR_PAD_LEFT);

        $blue = base_convert($blue, 10, 16);
        $blue = str_pad($blue, 2, '0', STR_PAD_LEFT);

        return '#' . $red . $green . $blue;
    }
}