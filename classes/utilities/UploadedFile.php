<?php


class UploadedFile
{
    /**
     * Container of information about The uploaded file
     *
     * @var array
     */
    private $file = [];

    /**
     * The uploaded file name
     *
     * @var string
     */
    private $fileName;

    /**
     * the uploaded temp file path
     *
     * @var string
     */
    private $tempFile;

    /**
     * the actual file name stored in the file system
     *
     * @var array
     */
    private $newFileName;

    /**
     * Constructor
     *
     * @param string $input
     */
    public function __construct($input)
    {
        if (empty($_FILES[$input])) {
            return;
        }

        $file = $_FILES[$input];

        if ($file['error'] != UPLOAD_ERR_OK) {
            return;
        }

        $this->file = $file;

        $this->fileName = $this->file['name'];
        $this->tempFile = $this->file['tmp_name'];
    }

    /**
     * Determine if the file is uploaded
     *
     * @return bool
     */
    public function isExists()
    {
        return !empty($this->file);
    }

    /**
     * Get file Name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get the actual file name stored in the file system
     */
    public function getNewFileName()
    {
        return $this->newFileName;
    }

    /**
     * Move the uploaded file to the given path
     *
     * @param string $path /path/to/filesDir/
     * @param string $newFileName file.jpg
     * @return bool
     */
    public function moveTo($path, $newFileName = null)
    {
        $fileName = $newFileName ?: sha1(mt_rand()) . '_' . $this->fileName;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $uploadedFilePath = $path . $fileName;
        $this->newFileName = $fileName;

        return move_uploaded_file($this->tempFile, $uploadedFilePath);
    }

    /**
     * Remove $fileName from the given path
     *
     * @param  string $path /path/to/filesDir/
     * @param  string $fileName file.jpg
     * @return bool
     */
    public static function removeFrom($path, $fileName)
    {
        $file = $path . $fileName;
        if (is_file($file)) {
            return unlink($file);
        }
        return false;
    }
}
