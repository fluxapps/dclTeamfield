<?php
/**
 * Class RoleMapper
 * The role mapper class maps user roles to team names.
 *
 * @author  Nicolas Schäfli <ns@studer-raimann.ch>
 */

namespace DclTeamfield\business;

require_once 'Modules/DataCollection/classes/Fields/Base/class.ilDclBaseFieldModel.php';
require_once 'Modules/DataCollection/classes/Helpers/class.ilDclCache.php';

class RoleMapper
{
    const TEAM_COLUMN_NAME = 'Team';
    const GROUP_COLUMN_NAME = 'Group';

    /**
     * Maps a field id to a reference table which is used to map the user role to a team name.
     *
     * @param \ilDclBaseFieldModel $field     Field which should be used to fetch the reference table.
     *
     * @return string | null
     */
    public static final function mapToTeamName(\ilDclBaseFieldModel $field)
    {
        $referenceTable = self::getLinkedTable($field);
        $teamField = self::getTeamField($referenceTable);
        $groupField = self::getGroupField($referenceTable);

        $roles = self::getUserAssignedGroups();

        $records = $referenceTable->getRecords();
        foreach ($records as $record)
        {
            $team = $record->getRecordField($teamField->getId());
            $group = $record->getRecordField($groupField->getId());

            if(in_array($group->getValue(), $roles))
            {
                return $team->getValue();
            }

        }

        return null;
    }

    /**
     * Fetches all record fields from the Team column which are located in the linked table.
     *
     * @param \ilDclBaseFieldModel $field   The field located in the current table.
     *
     * @return \ilDclBaseRecordFieldModel[]
     */
    public static final function getAllTeamNameFieldRecords(\ilDclBaseFieldModel $field)
    {
        $referenceTable = self::getLinkedTable($field);
        $teamField = self::getTeamField($referenceTable);

        $records = $referenceTable->getRecords();
        $teamRecords = [];

        foreach ($records as $record)
        {
            $team = $record->getRecordField($teamField->getId());
            $teamRecords[] = $team;
        }

        return $teamRecords;
    }

    /**
     * Fetches all role id's by the active user.
     *
     * @return int[]
     */
    private static function getUserAssignedGroups()
    {
        global $DIC;
        $userId = $DIC->user()->getId();
        $roles = $DIC->rbac()->review()->assignedRoles($userId);

        return $roles;
    }

    /**
     * Searches the Team field in the reference table.
     *
     * @param \ilDclTable $table    Reference Table.
     *
     * @return \ilDclBaseFieldModel The first field named field in the given table.
     */
    private static function getTeamField($table)
    {
        global $DIC;

        $teamField = $table->getFieldByTitle(self::TEAM_COLUMN_NAME);
        if(is_null($teamField))
        {
            $pl = \ilDclTeamfieldPlugin::getInstance();

            /**
             * @var $error \ilErrorHandling
             */
            $error = $DIC["ilErr"];
            $error->throwError($pl->txt("no_team_field_found_in_table"), $error->WARNING);
        }

        return $teamField;
    }

    /**
     * Searches the Group field in the reference table.
     *
     * @param \ilDclTable $table
     *
     * @return \ilDclBaseFieldModel
     */
    private static function getGroupField($table)
    {
        global $DIC;

        $groupField = $table->getFieldByTitle(self::GROUP_COLUMN_NAME);
        if(is_null($groupField))
        {
            $pl = \ilDclTeamfieldPlugin::getInstance();

            /**
             * @var $error \ilErrorHandling
             */
            $error = $DIC["ilErr"];
            $error->throwError($pl->txt("no_group_field_found_in_table"), $error->WARNING);
        }

        return $groupField;
    }

    /**
     * Fetches the table by the saved reference.
     * If no table id was found an il error is raised.
     *
     * @param \ilDclBaseFieldModel $field   The field which should be used to fetch the reference table.
     *
     * @return \ilDclTable
     */
    private static function getLinkedTable(\ilDclBaseFieldModel $field)
    {
        global $DIC;
        $table_id = $field->getProperty(\ilDclBaseFieldModel::PROP_REFERENCE);

        //check if the reference table is set.
        if(is_null($table_id))
        {
	        $pl = \ilDclTeamfieldPlugin::getInstance();

            /**
             * @var $error \ilErrorHandling
             */
            $error = $DIC["ilErr"];
            $error->throwError($pl->txt("no_magic_team_table_set"), $error->WARNING);
        }

        $table = \ilDclCache::getTableCache($table_id);
        return $table;
    }
}