<?php

namespace Services;

use Interfaces\TestInterface;

class TestCase implements TestInterface
{
    private $test_cases;

    public function __construct()
    {
        $this->test_cases = scandir(__DIR__ . "/../../Test");
    }
    
    public function run(): void
    {
        unset($this->test_cases[0]);
        unset($this->test_cases[1]);

        foreach ($this->test_cases as $case) {
            $test = include __DIR__ . "/../../Test/" . $case;

            $test->test();
        }
    }
}