<?php

use DclTeamfield\business\RoleMapper;

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once 'Modules/DataCollection/classes/Fields/Reference/class.ilDclReferenceRecordFieldModel.php';

/**
 * Class ilDclTeamfieldRecordFieldModel
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */
class ilDclTeamfieldRecordFieldModel extends ilDclReferenceRecordFieldModel
{
    /**
     * @inheritDoc
     */
    public function setValue($value, $omit_parsing = false)
    {
        $teamName = RoleMapper::mapToTeamName($this->getField());
        parent::setValue($teamName, true);
    }

    /**
     * @inheritDoc
     */
    public function setValueFromForm($form)
    {
        //value will be set by setValue it self.
        $this->setValue(null);
    }

    /**
     * @inheritDoc
     */
    public function getExportValue()
    {
        return $this->getValue();
    }

    /**
     * @param $excel
     * @param $row
     * @param $col
     *
     * @return array|int|int[]
     */
    public function getValueFromExcel($excel, $row, $col)
    {
        $value = $value = $excel->getCell($row, $col);
        return $value;
    }


}