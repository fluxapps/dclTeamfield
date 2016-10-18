<?php
/**
 * Class ilDclTeamfieldConfigGUI
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

use DclTeamfield\gui\MainGUI;
use DclTeamfield\gui\PluginConfigGUI;
use ILIAS\DI\Container;

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');

/**
 * Class ilDclTeamfieldConfigGUI
 *
 * @package DclTeamfield
 * @ilCtrl_IsCalledBy  ilLiveVotingConfigGUI: ilObjComponentSettingsGUIs
 */
class ilDclTeamfieldConfigGUI extends \ilPluginConfigGUI
{
    /**
     * @var $dic Container
     */
    private $dic;

    /**
     * ilDclTeamfieldConfigGUI constructor.
     */
    public function __construct()
    {
        global $DIC;

        $this->dic = $DIC;
    }

    function performCommand($cmd)
    {
        $this->dic->ctrl()->setParameterByClass("ilobjcomponentsettingsgui", "ctype", $_GET["ctype"]);
        $this->dic->ctrl()->setParameterByClass("ilobjcomponentsettingsgui", "cname", $_GET["cname"]);
        $this->dic->ctrl()->setParameterByClass("ilobjcomponentsettingsgui", "slot_id", $_GET["slot_id"]);
        $this->dic->ctrl()->setParameterByClass("ilobjcomponentsettingsgui", "plugin_id", $_GET["plugin_id"]);
        $this->dic->ctrl()->setParameterByClass("ilobjcomponentsettingsgui", "pname", $_GET["pname"]);

        $tpl = $this->dic
            ->ui()
            ->mainTemplate();

        $tpl->setTitle($this->dic->language()->txt("cmps_plugin") . ": " . $_GET["pname"]);
        $tpl->setDescription("");
        $this->dic->tabs()->clearTargets();

        if ($_GET["plugin_id"]) {
            $this->dic->tabs()->setBackTarget(
                $this->dic->language()->txt("cmps_plugin"),
                $this->dic->ctrl()->getLinkTargetByClass("ilobjcomponentsettingsgui", "showPlugin")
            );
        } else {
            $this->dic->tabs()->setBackTarget(
                $this->dic->language()->txt("cmps_plugins"),
                $this->dic->ctrl()->getLinkTargetByClass("ilobjcomponentsettingsgui", "listPlugins")
            );
        }

        $nextClass = $this->dic->ctrl()->getNextClass();

        if ($nextClass) {
            $a_gui_object = new MainGUI();
            $this->dic->ctrl()->forwardCommand($a_gui_object);
        } else {
            $this->dic->ctrl()->redirectByClass(array(
                MainGUI::class,
                PluginConfigGUI::class
            ));
        }
    }



}