<?php
/**
 * Interface CommandExecutionService
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

namespace DclTeamfield\gui;


interface CommandExecutionService
{
    /**
     * Part of the ILIAS control flow.
     * Needed in every GUI class.
     *
     * @param string $cmd   IlIAS command for the command class.
     *
     * @return void
     */
    public function executeCommand();
}