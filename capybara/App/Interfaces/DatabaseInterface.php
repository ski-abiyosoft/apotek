<?php

namespace Interfaces;

interface DatabaseInterface
{
    public function exec(string $query_string): bool;
    public function get(): array;
}