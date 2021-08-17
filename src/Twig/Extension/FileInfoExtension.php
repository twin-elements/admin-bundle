<?php

namespace TwinElements\AdminBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class FileInfoExtension extends AbstractExtension
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('file_name', [$this, 'getFileName']),
            new TwigFilter('file_ext', [$this, 'getFileExt']),
            new TwigFilter('file_size', [$this, 'getFileSize'])
        ];
    }

    public function getFileName($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    public function getFileExt($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function getFileSize($path)
    {
        if(!file_exists($this->kernel->getProjectDir() . '/public' . $path)){
            return 'File not found!';
        }
        return self::bytesToSize(filesize($this->kernel->getProjectDir() . '/public' . $path));
    }

    private function bytesToSize($bytes)
    {
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;

        if($bytes < $kilobyte){
            $number = $bytes;
            $unit = 'b';

        } elseif ($bytes < $megabyte){
            $number = $bytes / $kilobyte;
            $unit = 'KB';
        } elseif ($bytes < $gigabyte) {
            $number = $bytes / $megabyte;
            $unit = 'MB';
        } elseif ($bytes < $terabyte) {
            $number = $bytes / $gigabyte;
            $unit = 'GB';
        } else {
            $number = $bytes / $terabyte;
            $unit = 'TB';
        }
        return new Markup(number_format($number, 2, '.', ' ') . '&nbsp;' . $unit, 'utf-8');
    }

}
