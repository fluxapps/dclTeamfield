<?php

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once 'Modules/DataCollection/classes/Fields/Reference/class.ilDclReferenceRecordRepresentation.php';

/**
 * Class ilDclTeamfieldRecordRepresentation
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */
final class ilDclTeamfieldRecordRepresentation extends ilDclReferenceRecordRepresentation
{
    /**
     * @inheritDoc
     */
    public function getHTML($link = true)
    {
        $value = $this->getRecordField()->getValue();

        if(is_null($value))
            return "";

        return $value;
    }

}