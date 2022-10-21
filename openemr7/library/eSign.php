<?php

/**
 *  @package   OpenEMR
 *  @link      http://www.open-emr.org
 *  @author    Sherwin Gaddis <sherwingaddis@gmail.com>
 *  @copyright Copyright (c )2020. Sherwin Gaddis <sherwingaddis@gmail.com>
 *  @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 *
 *
 */

// require_once "../interface/globals.php";
require_once(__DIR__ . "/../interface/globals.php");

require_once("$srcdir/encounter.inc");
require_once("$srcdir/group.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/amc.php");
require_once($GLOBALS['srcdir'] . '/ESign/Api.php');
require_once("$srcdir/../controllers/C_Document.class.php");

use ESign\Api;
use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Csrf\CsrfUtils;
use OpenEMR\Common\Twig\TwigContainer;
use OpenEMR\Core\Header;
use OpenEMR\Events\Encounter\EncounterMenuEvent;
use OpenEMR\Services\EncounterService;
use OpenEMR\Services\UserService;
use Symfony\Component\EventDispatcher\EventDispatcher;


$expand_default = (int)$GLOBALS['expand_form'] ? 'show' : 'hide';
$reviewMode = false;
if (!empty($_REQUEST['review_id'])) {
    $reviewMode = true;
    $encounter = sanitizeNumber($_REQUEST['review_id']);
}

$is_group = ($attendant_type == 'gid') ? true : false;
if ($attendant_type == 'gid') {
    $groupId = $therapy_group;
}
$attendant_id = $attendant_type == 'pid' ? $pid : $therapy_group;
if ($is_group && !AclMain::aclCheckCore("groups", "glog", false, array('view','write'))) {
    echo xlt("access not allowed");
    exit();
}

if ($GLOBALS['kernel']->getEventDispatcher() instanceof EventDispatcher) {
    /**
     * @var EventDispatcher
     */
    $eventDispatcher = $GLOBALS['kernel']->getEventDispatcher();
} else {
    throw new Exception("Could not get EventDispatcher from kernel", 1);
}



?>


<?php
$esignApi = new Api();
// $formConfig = $esignApi->formConfigToJson(); ;
// // console.log(formConfig);
// print_r($formConfig);




?>
<script>
$(function () {
    var formConfig = <?php echo $esignApi->formConfigToJson(); ?>;
    console.log(formConfig);
    $(".esign-button-form").esign(
        formConfig,
        {
            afterFormSuccess : function( response ) {
                if ( response.locked ) {
                    var editButtonId = "form-edit-button-"+response.formDir+"-"+response.formId;
                    $("#"+editButtonId).replaceWith( response.editButtonHtml );
                }

                var logId = "esign-signature-log-"+response.formDir+"-"+response.formId;
                $.post( formConfig.logViewAction, response, function( html ) {
                    $("#"+logId).replaceWith( html );
                });
            }
        }
    );

    var encounterConfig = <?php echo $esignApi->encounterConfigToJson(); ?>;
    $(".esign-button-encounter").esign(
        encounterConfig,
        {
            afterFormSuccess : function( response ) {
                // If the response indicates a locked encounter, replace all
                // form edit buttons with a "disabled" button, and "disable" left
                // nav visit form links
                if ( response.locked ) {
                    // Lock the form edit buttons
                    $(".form-edit-button").replaceWith( response.editButtonHtml );
                    // Disable the new-form capabilities in left nav
                    top.window.parent.left_nav.syncRadios();
                    // Disable the new-form capabilities in top nav of the encounter
                    $(".encounter-form-category-li").remove();
                }

                var logId = "esign-signature-log-encounter-"+response.encounterId;
                $.post( encounterConfig.logViewAction, response, function( html ) {
                    $("#"+logId).replaceWith( html );
                });
            }
        }
    );

    $("#prov_edu_res").click(function() {
        if ( $('#prov_edu_res').prop('checked') ) {
            var mode = "add";
        }
        else {
            var mode = "remove";
        }
        top.restoreSession();
        $.post( "../../../library/ajax/amc_misc_data.php",
            { amc_id: "patient_edu_amc",
              complete: true,
              mode: mode,
              patient_id: <?php echo js_escape($pid); ?>,
              object_category: "form_encounter",
              object_id: <?php echo js_escape($encounter); ?>,
              csrf_token_form: <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>
            }
        );
    });

    $("#provide_sum_pat_flag").click(function() {
        if ( $('#provide_sum_pat_flag').prop('checked') ) {
            var mode = "add";
        }
        else {
            var mode = "remove";
        }
        top.restoreSession();
        $.post( "../../../library/ajax/amc_misc_data.php",
            { amc_id: "provide_sum_pat_amc",
              complete: true,
              mode: mode,
              patient_id: <?php echo js_escape($pid); ?>,
              object_category: "form_encounter",
              object_id: <?php echo js_escape($encounter); ?>,
              csrf_token_form: <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>
            }
        );
    });

    $("#trans_trand_care").click(function() {
        if ( $('#trans_trand_care').prop('checked') ) {
            var mode = "add";
            // Enable the reconciliation checkbox
            $("#med_reconc_perf").removeAttr("disabled");
        $("#soc_provided").removeAttr("disabled");
        }
        else {
            var mode = "remove";
            //Disable the reconciliation checkbox (also uncheck it if applicable)
            $("#med_reconc_perf").attr("disabled", true);
            $("#med_reconc_perf").prop("checked",false);
        $("#soc_provided").attr("disabled",true);
        $("#soc_provided").prop("checked",false);
        }
        top.restoreSession();
        $.post( "../../../library/ajax/amc_misc_data.php",
            { amc_id: "med_reconc_amc",
              complete: false,
              mode: mode,
              patient_id: <?php echo js_escape($pid); ?>,
              object_category: "form_encounter",
              object_id: <?php echo js_escape($encounter); ?>,
              csrf_token_form: <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>
            }
        );
    });

    $("#med_reconc_perf").click(function() {
        if ( $('#med_reconc_perf').prop('checked') ) {
            var mode = "complete";
        }
        else {
            var mode = "uncomplete";
        }
        top.restoreSession();
        $.post( "../../../library/ajax/amc_misc_data.php",
            { amc_id: "med_reconc_amc",
              complete: true,
              mode: mode,
              patient_id: <?php echo js_escape($pid); ?>,
              object_category: "form_encounter",
              object_id: <?php echo js_escape($encounter); ?>,
              csrf_token_form: <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>
            }
        );
    });
    $("#soc_provided").click(function(){
        if($('#soc_provided').prop('checked')){
                var mode = "soc_provided";
        }
        else{
                var mode = "no_soc_provided";
        }
        top.restoreSession();
        $.post( "../../../library/ajax/amc_misc_data.php",
                { amc_id: "med_reconc_amc",
                complete: true,
                mode: mode,
                patient_id: <?php echo js_escape($pid); ?>,
                object_category: "form_encounter",
                object_id: <?php echo js_escape($encounter); ?>,
                csrf_token_form: <?php echo js_escape(CsrfUtils::collectCsrfToken()); ?>
                }
        );
    });

     $(".deleteme").click(function(evt) { deleteme(); evt.stopPropogation(); });

<?php
  // If the user was not just asked about orphaned orders, build javascript for that.
if (!isset($_GET['attachid'])) {
    $ares = sqlStatement(
        "SELECT procedure_order_id, date_ordered " .
        "FROM procedure_order WHERE " .
        "patient_id = ? AND encounter_id = 0 AND activity = 1 " .
        "ORDER BY procedure_order_id",
        array($pid)
    );
    echo "  // Ask about attaching orphaned orders to this encounter.\n";
    echo "  var attachid = '';\n";
    while ($arow = sqlFetchArray($ares)) {
        $orderid   = $arow['procedure_order_id'];
        $orderdate = $arow['date_ordered'];
        echo "  if (confirm(" . xlj('There is a lab order') . " + ' ' + " . js_escape($orderid) . " + ' ' + " .
        xlj('dated') . " + ' ' + " . js_escape($orderdate) .  " + ' ' + " .
        xlj('for this patient not yet assigned to any encounter.') . " + ' ' + " .
        xlj('Assign it to this one?') . ")) attachid += " . js_escape($orderid . ",") . ";\n";
    }
    echo "  if (attachid) location.href = 'forms.php?attachid=' + encodeURIComponent(attachid);\n";
}
?>

    <?php if ($reviewMode) { ?>
        $("body table:first").hide();
        $(".encounter-summary-column").hide();
        $(".btn").hide();
        $(".encounter-summary-column:first").show();
        $(".title:first").text(<?php echo xlj("Review"); ?> + " " + $(".title:first").text() + " ( " + <?php echo js_escape($encounter); ?> + " )");
    <?php } ?>
});

 // Process click on Delete link.
 function deleteme() {
  dlgopen('../deleter.php?encounterid=' + <?php echo js_url($encounter); ?> + '&csrf_token_form=' + <?php echo js_url(CsrfUtils::collectCsrfToken()); ?>, '_blank', 500, 200, '', '', {
      allowResize: false,
      allowDrag: true,
  });
  return false;
 }


// create new follow-up Encounter.
function createFollowUpEncounter() {

    <?php
    $result = sqlQuery("SELECT * FROM form_encounter WHERE pid = ? AND encounter = ?", array(
        $_SESSION['pid'],
        $encounter
    ));
    $encounterId = (!empty($result['parent_encounter_id'])) ? $result['parent_encounter_id'] : $result['id'];
    ?>
    var data = {encounterId: '<?php echo attr($encounterId); ?>', mode: 'follow_up_encounter'};
    top.window.parent.newEncounter(data);
}


 // Called by the deleter.php window on a successful delete.
function imdeleted(EncounterId) {
    top.window.parent.left_nav.removeOptionSelected(EncounterId);
    top.window.parent.left_nav.clearEncounter();
    top.encounterList();
}

// Called to open the data entry form a specified encounter form instance.
function openEncounterForm(formdir, formname, formid) {
  var url = <?php echo js_escape($rootdir); ?> + '/patient_file/encounter/view_form.php?formname=' +
      encodeURIComponent(formdir) + '&id=' + encodeURIComponent(formid);
  if (formdir == 'newpatient' || !parent.twAddFrameTab) {
    top.restoreSession();
    location.href = url;
  }
  else {
    parent.twAddFrameTab('enctabs', formname, url);
  }
  return false;
}

// Called when an encounter form may changed something that requires a refresh here.
function refreshVisitDisplay() {
  location.href = <?php echo js_escape($rootdir); ?> + '/patient_file/encounter/forms.php';
}

</script>



    
   
    

