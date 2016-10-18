<?php
/**
 * Class ilDclTeamfieldPlugin
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once('./Modules/DataCollection/classes/Fields/Plugin/class.ilDclFieldTypePlugin.php');

class ilDclTeamfieldPlugin extends \ilDclFieldTypePlugin
{
    const PLUGIN_NAME = "DclTeamfield";

    /**
     * ilDclTeamfieldPlugin constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if(!$GLOBALS["DIC"]->offsetExists(self::class))
        {
            $GLOBALS["DIC"][self::class] = $this;
        }
    }

    /**
     * Provides a new or already created ilDclTeamfieldPlugin instance.
     *
     * @return ilDclTeamfieldPlugin
     */
    public static function getInstance()
    {
        if(!$GLOBALS["DIC"]->offsetExists(self::class))
        {
            return new self();
        }

        return $GLOBALS["DIC"][self::class];
    }

    function getPluginName()
    {
        return self::PLUGIN_NAME;
    }

}