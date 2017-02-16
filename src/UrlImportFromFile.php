<?php

namespace sergiobelya\TestHexaImageloader;

/**
 * @author Serg
 */
class UrlImportFromFile
{
    public function importToArray($filepath)
    {
        if (!is_file($filepath)) {
            throw new \Exception($filepath . ' is not a file');
        }
        return file($filepath);
    }
}
