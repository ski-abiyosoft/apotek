<?php

namespace Interfaces;

interface ConnectionInterface
{
    public function make_query(string $query_string): bool;
    public function get(): array;
}