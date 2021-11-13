<?php

namespace helper;

class File
{
    public static function upload($name, $requiredFileType = null): string | null
    {
        if (!self::exists($name)) {
            return null;
        }

        $uploadDir = $GLOBALS["config"]["upload_dir"];
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir);
        }

        $fileType = strtolower(pathinfo(self::get($name, "name"), PATHINFO_EXTENSION));
        $fileName = strtolower(pathinfo(self::get($name, "name"), PATHINFO_FILENAME));
        $uniqueName = $fileName . uniqid() . "." . $fileType;

        $targetFile = CONFIG["upload_dir"] . "/" . $uniqueName;

        if ($requiredFileType != null) {
            if($fileType !== $requiredFileType) {
                throw new \RuntimeException("");
            }
        }

        if(move_uploaded_file(self::get($name, "tmp_name"), $targetFile)) {
            return $targetFile;
        }

        return null;
    }

    private static function exists($name)
    {
        return isset($_FILES[$name]) && !empty($_FILES[$name]);
    }

    private static function get($name, $field)
    {
        if (!self::exists($name)) {
            return null;
        }
        return $_FILES[$name][$field];
    }
}