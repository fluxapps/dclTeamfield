<?php

use DclTeamfield\business\RoleMapper;

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once 'Modules/DataCollection/classes/Fields/Reference/class.ilDclReferenceFieldRepresentation.php';

/**
 * Class ilDclTeamfieldFieldRepresentation
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */
final class ilDclTeamfieldFieldRepresentation extends ilDclReferenceFieldRepresentation
{
    const FILTER_DISPLAY_TEAM = 1;

    private $pl;
    private $dic;

    /**
     * ilDclTeamfieldFieldRepresentation constructor.
     *
     * @param ilDclBaseFieldModel $field
     */
    public function __construct(ilDclBaseFieldModel $field)
    {
        parent::__construct($field);

        global $DIC;
        $this->dic = $DIC;
        $this->pl = $DIC["ilDclTeamfieldPlugin"];
    }


    /**
     * Build the creation-input-field
     *
     * @param ilObjDataCollection $dcl
     * @param string              $mode
     *
     * @return ilPropertyFormGUI
     */
    public function buildFieldCreationInput(ilObjDataCollection $dcl, $mode = 'create') {
        $opt = new ilRadioOption($this->lng->txt('dcl_' . $this->getField()->getDatatype()->getTitle()), $this->getField()->getDatatypeId());
        $opt->setInfo($this->lng->txt('dcl_' . $this->getField()->getDatatype()->getTitle(). '_desc'));

        $options = array();
        // Get Tables
        $tables = $dcl->getTables();
        foreach ($tables as $table) {
            $options[$table->getId()] = $table->getTitle();
        }
        $prop_table_selection = new ilSelectInputGUI($this->pl->txt('field_edit_reference_table'), 'prop_' .ilDclBaseFieldModel::PROP_REFERENCE);
        $prop_table_selection->setOptions($options);

        $opt->addSubItem($prop_table_selection);

        return $opt;
    }

    /**
     * GUI input for the record creation mask.
     * This input field is disabled because the value will be automatically set by ILIAS.
     *
     * @param ilPropertyFormGUI $form
     * @param int               $record_id      Current record id.
     *
     * @return ilTextInputGUI
     */
    public function getInputField(ilPropertyFormGUI $form, $record_id = 0)
    {
        $input = new ilTextInputGUI($this->getField()->getTitle(), 'field_' . $this->getField()->getId());
        $input->setDisabled(true);

        $teamName = RoleMapper::mapToTeamName($this->getField());
        if(is_null($teamName))
            $input->setValue($this->pl->txt("no_team_found"));
        else
            $input->setValue($teamName);

        return $input;
    }

    /**
     * Adds the filter input GUI to the table.
     *
     * @param ilTable2GUI $table
     *
     * @return null
     */
    public function addFilterInputFieldToTable(ilTable2GUI $table)
    {
        $input = $table->addFilterItemByMetaType("filter_" . $this->getField()->getId(), ilTable2GUI::FILTER_SELECT, false, $this->getField()->getId());

        $options = array();
        $options[self::FILTER_DISPLAY_TEAM] = $this->pl->txt("filter_option_team");

        $options = array('' => $this->lng->txt('dcl_any')) + $options;
        $input->setOptions($options);

        $this->setupFilterInputField($input);

        return $this->getFilterInputFieldValue($input);
    }


}