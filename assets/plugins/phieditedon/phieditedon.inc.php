<?php
/*==============================================================================
  Title: phiEditedon
  Category: Plugin
  Author: Phize
  Author URI: http://phize.net
  License: GNU General Public License(http://www.gnu.org/licenses/gpl.html)
  Version: 1.1.0
  Last Update: 2009-08-11
==============================================================================*/





function modx_plugin_phiEditedon_getTemplateVar($modx, $tvName, $id, $published, $deleted) {
    $result= array();

    $docRow = $modx->getDocument($id, '*', $published, $deleted);

    if (!$docRow) {
        return false;
    }

    if ($docgrp = $modx->getUserDocGroups()) {
        $docgrp = implode(",", $docgrp);
    }

    $sql = "SELECT tv.id, IF (tvc.value != '', tvc.value, tv.default_text) as value ";
    $sql .= "FROM " . $modx->getFullTableName('site_tmplvars')." tv ";
    $sql .= "INNER JOIN " . $modx->getFullTableName('site_tmplvar_templates')." tvtpl ON tvtpl.tmplvarid = tv.id ";
    $sql .= "LEFT JOIN " . $modx->getFullTableName('site_tmplvar_contentvalues')." tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = '" . $id . "' ";
    $sql .= "WHERE tv.name IN ('" . $tvName . "') AND tvtpl.templateid = " . $docRow['template'];

    $rs = $modx->dbQuery($sql);

    for ($i = 0; $i < @ $modx->recordCount($rs); $i ++) {
        array_push($result, @ $modx->fetchRow($rs));
    }

    return ($result != false) ? $result[0] : false;
}
?>
