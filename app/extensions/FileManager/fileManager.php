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
            $mode = 'w+';
            $file = $this->open($location, $mode);
            $write = fwrite($file, $text);
            if ($write == false) {
                return false;
            }
            $this->close($file);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Open the file.
     * @param string $location
     * @param string $mode
     * @return boolean|file
     */
    public function open($location, $mode = 'r')
    {
        try {
            $file = fopen($location, $mode);
            if ($file == false) {
                return false;
            }
            return $file;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Close a file.
     * @param file $file
     * @return boolean
     */
    public function close($file)
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
