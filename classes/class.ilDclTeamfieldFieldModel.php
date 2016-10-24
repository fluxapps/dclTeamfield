<?php
/**
 * Class ilDclTeamfieldFieldModel
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

use DclTeamfield\business\RoleMapper;

require_once 'Customizing/global/plugins/Modules/DataCollection/FieldTypeHook/dclTeamfield/vendor/autoload.php';
require_once("Modules/DataCollection/classes/Fields/Reference/class.ilDclReferenceFieldModel.php");
require_once("./Modules/DataCollection/classes/Helpers/class.ilDclRecordQueryObject.php");

final class ilDclTeamfieldFieldModel extends \ilDclReferenceFieldModel
{

    /**
     * ilDclTeamfieldFieldModel constructor.
     *
     * @param int $a_id
     */
    public function __construct($a_id = 0)
    {
        parent::__construct($a_id);
        $this->setStorageLocationOverride(1); //we need to store the team name
    }

    /**
     * @inheritDoc
     */
    public function getRecordQueryFilterObject($filter_value = "", ilDclBaseFieldModel $sort_field = null)
    {
        global $DIC;
        $ilDB = $DIC['ilDB'];

        $teamName = "";

        //safe cast to int
        $filter = (is_numeric($filter_value)) ? (int)$filter_value : "";

        if(ilDclTeamfieldFieldRepresentation::FILTER_DISPLAY_TEAM === $filter)
        {
            $teamName = RoleMapper::mapToTeamName($this);
        }

        $join_str =
            " INNER JOIN il_dcl_record_field AS filter_record_field_{$this->getId()} ON (filter_record_field_{$this->getId()}.record_id = record.id AND filter_record_field_{$this->getId()}.field_id = "
            . $ilDB->quote($this->getId(), 'integer') . ") ";

        $join_str .=
            " INNER JOIN il_dcl_stloc{$this->getStorageLocation()}_value AS filter_stloc_{$this->getId()} ON (filter_stloc_{$this->getId()}.record_field_id = filter_record_field_{$this->getId()}.id AND filter_stloc_{$this->getId()}.value = "
            . $ilDB->quote($teamName, 'string') . ") ";

        $sql_obj = new ilDclRecordQueryObject();
        $sql_obj->setJoinStatement($join_str);

        return $sql_obj;
    }

    /**
     * @inheritDoc
     */
    public function allowFilterInListView()
    {
        return true;
    }


}