<?php
/**
 * Class MainGUI
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';

/**
 * Class MainGUI
 *
 * @package DclTeamfield\gui
 * @ilCtrl_IsCalledBy  MainGUI: ilDclTeamfieldConfigGUI
 */
class MainGUI implements \DclTeamfield\gui\CommandExecutionService
{
    /**
     * @var \ILIAS\DI\Container $dic
     */
    private $dic;

    /**
     * MainGUI constructor.
     */
    public function __construct()
    {
        global $DIC;
        $this->dic = $DIC;
    }


    /**
     * @inheritDoc
     */
    public function executeCommand()
    {
        $commandClass = $this->dic->ctrl()->getCmdClass();
        $instance = null;

        switch($commandClass)
        {
            case 'pluginconfiggui':
                $instance = new PluginConfigGUI();
                break;
            default:
                throw new Exception("No valid dclTeamfield gui found");
        }

        $this->dic->ctrl()->forwardCommand($instance);
    }

}