<?php

/**
 * Open and Writes text files.
 *
 * Usage:
 *
 * To write in a new file:
 *
 * <pre>
 *  Yii::import('ext.FileManager.fileManager');
 *  $fm = new fileManager();
 *  $fm->write($location, $text);
 * </pre>
 *
 *
 * To Open a file:
 *
 * <pre>
 *  Yii::import('ext.FileManager.fileManager');
 *  $fm = new fileManager();
 *  $file = $fm->open($location);
 * </pre>
 *
 *
 *
 *

 */
class fileManager
{
    protected $files = [];

    /**
     * Write file.
     * @param string $location
     * @param string $text
     * @return boolean
     */
    public function write($location, $text)
    {
        try {
            $file = $this->open($location, 'w+');
            if (!is_resource($file)) {
                return false;
            }
            $result = fwrite($file, $text);
            $this->close($file);
            return $result !== false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Open the file.
     * @param string $location
     * @param string $mode
     * @return resource|false
     */
    public function open($location, $mode = 'r')
    {
        try {
            $file = fopen($location, $mode);
            if ($file === false) {
                return false;
            }
            return $file;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Close a file.
     * @param resource $file
     * @return boolean
     */
    public function close(mixed $file)
    {
        try {
            fclose($file);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Close all files.
     * @return boolean
     */
    public function closeAll()
    {
        try {
            $allClosed = true;

            foreach ($this->files as $file) {
                $close = $this->close($file);
                $allClosed = $allClosed && $close;
            }

            return $allClosed;
        } catch (Exception $e) {
            return false;
        }
    }
}
