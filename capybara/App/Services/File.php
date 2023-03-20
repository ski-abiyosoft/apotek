<?php

namespace Services;

use Interfaces\FileInterface;

class File implements FileInterface
{
    private $stream;
    private $path;

    public function __construct(string $path = null)
    {
        if ($path) {
            $this->new($path);
        }
    }

    /**
     * Creating new file based on given path
     * 
     * @param string $path
     */
    public function new(string $path): void
    {
        $this->stream = fopen($path, 'w');
        $this->path = $path;
    }

    /**
     * Open a file
     * 
     * @param string $path
     */
    public function open(string $path): void
    {
        if ($this->stream) {
            echo "You have opened a file, please close it before open a new file.";
        }else {
            $this->stream = fopen($path, 'r+');
            $this->path = $path;
        }
    }

    /**
     * Write into file
     * 
     * @param string $content
     */
    public function write(string $content): void
    {
        fwrite($this->stream, $content);
    }

    /**
     * Closing file
     */
    public function close(): void
    {
        fclose($this->stream);
    }

    /**
     * Deleting file
     */
    public function delete(string $path): void
    {
        fclose($this->stream);
        unlink($this->path);
    }
}