<?php

namespace sergiobelya\TestHexaImageloader;

use sergiobelya\TestHexaImageloader\Exceptions\UrlException;
use sergiobelya\TestHexaImageloader\Exceptions\ImageLoaderException;
use Exception;

/**
 * @author Serg
 */
class ImageLoader
{

    const URL_TO_FILENAME_LAST_NAME = 1;

    protected static $url_to_filename_methods = [
        self::URL_TO_FILENAME_LAST_NAME,
    ];

    protected static $allowed_image_types = [
        IMAGETYPE_JPEG => 'image/jpeg',
        IMAGETYPE_PNG => 'image/png',
        IMAGETYPE_GIF => 'image/gif',
    ];

    protected static $allowed_ext = [
        'jpg',
        'jpeg',
        'png',
        'gif',
    ];

    protected $http_loader;

    protected $img_urls = [];

    protected $img_pathes = [];

    protected $folder;

    protected $url_to_filename = self::URL_TO_FILENAME_LAST_NAME;

    protected $max_double_lastnames = 10;

    public function __construct(HttpLoaderInterface $http_loader, $folder)
    {
        $this->http_loader = $http_loader;
        if (!is_dir($folder)) {
            if (!mkdir($folder, 0775, true)) {
                throw new ImageLoaderException("Folder $folder is not created");
            }
        }
        $this->folder = $folder;
    }

    public function setUrlsArray(array $img_urls)
    {
        foreach ($img_urls as $url) {
            $this->addUrl($url);
        }
    }

    public function addUrl($url)
    {
        $url = trim($url);
        if (!$url) {
            return;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new UrlException($url);
        }
        $arr_url = explode('.', $url);
        $ext = array_pop($arr_url);
        if (!in_array(strtolower($ext), self::$allowed_ext)) {
            throw new UrlException($url);
        }
        $this->img_urls[] = $url;
    }

    public function loadAllImages()
    {
        $this->urls2pathes();
        foreach ($this->img_pathes as $url => $path) {
            $this->loadImage($url, $path);
            unset($this->img_pathes[$url]);
        }
    }

    /**
     * @param int $type some of URL_TO_FILENAME_ constants
     * @throws ImageLoaderException
     */
    public function setMethodUrl2Filename($type)
    {
        if (!in_array($type, self::$url_to_filename_methods)) {
            throw new ImageLoaderException('method is incorrect');
        }
    }

    /**
     * @param int $count
     */
    public function setMaxDoubleLastnames($count)
    {
        $this->max_double_lastnames = $count;
    }

    protected function urls2pathes()
    {
        foreach ($this->img_urls as $url) {
            $this->img_pathes[$url] = $this->url2path($url);
        }
    }

    protected function url2path($url)
    {
        switch ($this->url_to_filename) {
            case self::URL_TO_FILENAME_LAST_NAME :
            default :
                $url_arr = explode('/', $url);
                $last_fragment = array_pop($url_arr);
                $i = 0;
                do {
                    ++$i;
                    $rel_path = $i > 1 ? $last_fragment . '_' . $i : $last_fragment;
                } while (in_array($rel_path, $this->img_pathes) || file_exists($this->folder . $rel_path));
                break;
        }
        return $rel_path;
    }

    protected function loadImage($url, $tmp_path) {
        $content = $this->http_loader->load($url);
        $writed_bytes = file_put_contents($this->folder.$tmp_path, $content);
        if (false === $writed_bytes) {
            throw new Exception;
        }
        return $writed_bytes;
    }

}