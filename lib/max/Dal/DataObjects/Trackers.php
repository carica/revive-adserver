<?php

/*
+---------------------------------------------------------------------------+
| Openads v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 Openads Limited                                   |
| For contact details, see: http://www.openads.org/                         |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

/**
 * Table Definition for trackers
 */
require_once 'DB_DataObjectCommon.php';

class DataObjects_Trackers extends DB_DataObjectCommon
{
    var $onDeleteCascade = true;
    var $refreshUpdatedFieldIfExists = true;
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    var $__table = 'trackers';                        // table name
    var $trackerid;                       // int(9)  not_null primary_key auto_increment
    var $trackername;                     // string(255)  not_null
    var $description;                     // string(255)  not_null
    var $clientid;                        // int(9)  not_null multiple_key
    var $viewwindow;                      // int(9)  not_null
    var $clickwindow;                     // int(9)  not_null
    var $blockwindow;                     // int(9)  not_null
    var $status;                          // int(1)  not_null unsigned
    var $type;                            // int(1)  not_null unsigned
    var $linkcampaigns;                   // string(1)  not_null enum
    var $variablemethod;                  // string(7)  not_null enum
    var $appendcode;                      // blob(65535)  not_null blob
    var $updated;                         // datetime(19)  not_null binary

    /* ZE2 compatibility trick*/
    function __clone() { return $this;}

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Trackers',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE

    var $defaultValues = array(
        'linkcampaigns' => 'f',
        'variablemethod' => 'default'
    );
    
    function duplicate()
    {
        // Store the current (pre-duplication) tracker ID for use later
        $oldTrackerId = $this->trackerid;

        // Get unique name
        $this->trackername = $this->getUniqueNameForDuplication('trackername');

        $this->trackerid = null;
        $newTrackerid = $this->insert();
        if (!$newTrackerid) {
            return $newTrackerid;
        }

        // Copy any linked campaigns
        $doCampaign_trackers = $this->factory('campaigns_trackers');
        $doCampaign_trackers->trackerid = $oldTrackerId;
        $doCampaign_trackers->find();
        while ($doCampaign_trackers->fetch()) {
            $doCampaign_trackersClone = clone($doCampaign_trackers);
            $doCampaign_trackersClone->campaign_trackerid = null;
            $doCampaign_trackersClone->trackerid = $newTrackerid;
            $doCampaign_trackersClone->insert();
        }

        // Copy any variables
        $doVariables = $this->factory('variables');
        $doVariables->trackerid = $oldTrackerId;
        $doVariables->find();
        while ($doVariables->fetch()) {
            $doVariablesClone = clone($doVariables);
            $doVariablesClone->vriableid = null;
            $doVariablesClone->trackerid = $newTrackerid;
            $doVariablesClone->insert();
        }

        return $newTrackerid;
    }

    function _auditEnabled()
    {
        return true;
    }

    function _getContextId()
    {
        return $this->trackerid;
    }

    function _getContext()
    {
        return 'Tracker';
    }

    /**
     * build a client specific audit array
     *
     * @param integer $actionid
     * @param array $aAuditFields
     */
    function _buildAuditArray($actionid, &$aAuditFields)
    {
//        $context                      = 'Tracker';
//        $aAuditFields['key_field']    = $this->trackerid;
        $aAuditFields['key_desc']     = $this->trackername;
        switch ($actionid)
        {
            case OA_AUDIT_ACTION_INSERT:
                        break;
            case OA_AUDIT_ACTION_UPDATE:
                        break;
            case OA_AUDIT_ACTION_DELETE:
                        break;
        }
    }

    function _formatValue($field)
    {
        switch ($field)
        {
            case 'linkcampaigns':
                return $this->_boolToStr($this->$field);
            default:
                return $this->$field;
        }
    }
}

?>