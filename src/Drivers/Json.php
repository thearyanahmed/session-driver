<?php

namespace Prophecy\DDriver\Drivers;

use Prophecy\DDriver\Exceptions\DirectoryNotWriteableException;

class Json implements \SessionHandlerInterface {

    protected $_filePath;

    protected $_dir;

    protected $_defaultFileContent;

    private $extension = 'json';

    public function __construct(string $dir = null, string $sessionId = null)
    {
        if(!$dir) {
            $dir = __DIR__ . '/../../data';

            $this->_dir = $dir;
        }

        if(!$sessionId) {
            $sessionId = mt_rand(0,1500);
        }
        $this->_defaultFileContent = json_encode([]);
        $this->open($dir,$sessionId);
    }

    /**
     * @param string $save_path
     * @param string $name
     * @return bool
     * @throws DirectoryNotWriteableException
     */
    public function open($save_path, $name)
    {
        $filePath = $save_path . '/' . $name . '.' . $this->extension;

        $this->_filePath = $filePath;

        $this->_createIfNotExists();

        return true;
    }


    /**
     * Close the session
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close()
    {
        return true;
    }

    /**
     * @param string $file
     * @return bool
     */
    public function destroy($file)
    {
        $file = "$this->_dir/$file.$this->extension" ;
        if (file_exists($file)) {
            unlink($file);
        }

        return true;
    }

    /**
     * @param int $lifespan
     * @return bool
     */
    public function gc($lifespan)
    {
        foreach (glob("$this->_dir/*.$this->extension") as $file) {
            if (filemtime($file) + $lifespan < time() && file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * @param string $session_id
     * @return string|null
     */
    public function read($session_id)
    {
        //load session
        $file = $this->_load();
        //return value | null
        return $file[$session_id] ?? null;
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        //load session
        $file = $this->_load();
        //set value
//        var_dump(gettype($file));
        $file[$session_id] = $session_data;
        //save
        return $this->_put($file);
    }

    /**
     * @return mixed
     */
    private function _load()
    {
        $file = file_get_contents($this->_filePath);
        //decode
       return json_decode($file,true);
    }
    /**
     * @throws DirectoryNotWriteableException
     */
    private function _createIfNotExists()
    {
        if(!file_exists($this->_filePath)) {
            if( ! is_writable(dirname($this->_filePath))) {
                throw new DirectoryNotWriteableException("Session directory ( '{$this->_filePath}' ) is not writable.");
            }
            $this->_put($this->_defaultFileContent,false);
        }
    }

    /**
     * @param $content
     * @param bool $encodeToJson
     * @return bool
     */
    private function _put($content, $encodeToJson = true)
    {
        if($encodeToJson) {
            $content = json_encode($content);
        }
        return file_put_contents($this->_filePath,$content) ? true : false;
    }
}