<?php
/**
 * Class PluginConfig
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

namespace DclTeamfield\entity;

require_once './Services/ActiveRecord/class.ActiveRecord.php';

class PluginConfig extends \ActiveRecord
{
    /**
     * returns the table name.
     *
     * @return string
     */
    static function returnDbTableName() {
        return 'ar_message';
    }
}