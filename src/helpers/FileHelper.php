<?php

namespace Shiguangxiaotou3\Docsify\helpers;

class FileHelper
{

    public static function copyDir($source, $destination,$permissions =0755)
    {
        if (is_dir($source)) {
            if (!file_exists($destination)) {
                mkdir($destination, $permissions, true);
            }

            $files = scandir($source);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    self::copyDir("$source/$file", "$destination/$file");
                }
            }
        } elseif (file_exists($source)) {
            copy($source, $destination);
        }
    }

    public static function clearDir($directory)
    {
        if (!is_dir($directory)) {
            return;
        }
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $directory . '/' . $file;
                if (is_dir($path)) {
                    // 递归清空子目录
                    self::clearDir($path);
                    // 删除子目录
                    rmdir($path);
                } else {
                    // 删除文件
                    unlink($path);
                }
            }
        }
    }

    public static function deleteDir($path)
    {
        if (is_file($path)) {
            return unlink($path);
        } else {
            $dh = opendir($path);
            $success = true;  // 默认假设删除成功

            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $filePath = $path . '/' . $file;

                    // 如果是子目录，递归删除子目录
                    if (is_dir($filePath)) {
                        // 如果子目录删除失败，设置 $success 为 false
                        if (!self::deleteDir($filePath)) {
                            $success = false;
                        }
                    } else {
                        // 如果文件删除失败，设置 $success 为 false
                        if (!unlink($filePath)) {
                            $success = false;
                        }
                    }
                }
            }
            closedir($dh);

            // 如果目录删除成功，尝试删除目录本身
            if ($success && !rmdir($path)) {
                $success = false;
            }

            return $success;
        }
    }
}