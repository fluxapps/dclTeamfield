<?php

use DclTeamfield\business\RoleMapper;

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once 'Modules/DataCollection/classes/Fields/Reference/class.ilDclReferenceRecordFieldModel.php';

/**
 * Class ilDclTeamfieldRecordFieldModel
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */
final class ilDclTeamfieldRecordFieldModel extends ilDclReferenceRecordFieldModel
{
    /**
     * The excel import mode tells the setValue method to use the $value parameter
     * instead of the own team discovery mechanics.
     *
     * @var $excelImportMode bool
     */
    private $excelImportMode;

    /**
     * @inheritDoc
     */
    public function setValue($value, $omit_parsing = false)
    {
    	if ($this->isExcelImportMode()) {
    		$teamName = $value;
	    } else if ($this->getValue()) { // this is to avoid setting teamfield on editing records, but only on creation
	    	$teamName = $this->getValue();
	    } else {
	    	$teamName = RoleMapper::mapToTeamName($this->getField());
	    }
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
     * Read data from excel cell.
     *
     * @param \ilExcel  $excel  Excel sheet.
     * @param int       $row    Excel row.
     * @param int       $col    Excel column.
     *
     * @return string
     */
    public function getValueFromExcel($excel, $row, $col)
    {
        //enable excel import mode
        $this->excelImportMode = true;

        $value = $value = $excel->getCell($row, $col);
        return $value;
    }

    /**
     * Returns true if the excel import mode is enabled otherwise false.
     *
     * @return boolean
     */
    public function isExcelImportMode()
    {
        return $this->excelImportMode;
    }
}