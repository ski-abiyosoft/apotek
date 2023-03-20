<?php

namespace Interfaces;

interface MigratorInterface
{
    public function migrate(): void;
    public function createFile(string $migration_name, bool $is_creation = true, string $table_name = null): void;
}