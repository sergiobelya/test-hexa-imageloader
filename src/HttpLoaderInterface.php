<?php

namespace sergiobelya\TestHexaImageloader;

/**
 *
 * @author Serg
 */
interface HttpLoaderInterface
{
    /**
     * @param string $url
     * @return string loaded data
     */
    public function load($url);
}
