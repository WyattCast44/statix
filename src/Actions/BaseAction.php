<?php 

namespace Statix\Actions;

use Illuminate\Console\Command;

abstract class BaseAction
{
    protected $cli;

    public function __construct(Command $command)
    {
        $this->cli = $command;
    }
}