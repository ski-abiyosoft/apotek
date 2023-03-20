<?php

namespace Interfaces;

interface FileInterface
{
    public function new(string $path): void;
    public function open(string $path): void;
    public function write(string $contents): void;
    public function delete(string $path): void;
    public function close(): void;
}