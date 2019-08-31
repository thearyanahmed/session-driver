<?php

namespace Prophecy\DDriver\Drivers;

use Prophecy\DDriver\Exceptions\DirectoryNotWriteableException;

class Json implements \SessionHandlerInterface {

    protected $_filePath;

    protected $_dir;

    protected $_defaultFileContent = '{}';

    public function __construct(string $dir = null, string $sessionId = null)
    {
        if(!$dir) {
            $dir = __DIR__ . '/../../data';

            $this->_dir = $dir;
        }

        if(!$sessionId) {
            $sessionId = mt_rand(0,1500);
        }
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
        $filePath = $save_path . '/' . $name . '.json';

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
        // TODO: Implement close() method.

    }

    /**
     * Destroy a session
     * @link https://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($session_id)
    {
//        // TODO: Implement destroy() method.
//        $file = $this->_load();
//
//        if(isset($file[$session_id])) {
//            unset($file[$session_id]);
//            return $this->_put($file);
//        }
//        return false;
    }

    /**
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        foreach (glob("$this->_dir/*.json") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
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
            $this->_put($this->_defaultFileContent);
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