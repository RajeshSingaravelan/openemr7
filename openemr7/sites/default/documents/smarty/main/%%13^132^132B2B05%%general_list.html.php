<?php /* Smarty version 2.6.33, created on 2022-10-19 09:46:21
         compiled from /var/www/html/openemr-7.0.0/templates/documents/general_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'headerTemplate', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 13, false),array('function', 'xlt', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 46, false),array('function', 'xla', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 58, false),array('function', 'xlj', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 143, false),array('function', 'datetimepickerSupport', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 202, false),array('modifier', 'js_url', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 42, false),array('modifier', 'attr', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 69, false),array('modifier', 'text', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 86, false),array('modifier', 'js_escape', '/var/www/html/openemr-7.0.0/templates/documents/general_list.html', 99, false),)), $this); ?>
<html>
<head>

<?php echo smarty_function_headerTemplate(array('assets' => 'datetime-picker|select2'), $this);?>

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['GLOBALS']['assets_static_relative']; ?>
/dropzone/dist/dropzone.css">
<?php echo '
<style>
.select2-selection {
height: 35px!important;
border-radius: 4px 0 0 4px!important;
}
.warn_diagnostic {
    margin: 10 auto 10 auto;
    color: rgb(255, 0, 0);
    font-size: 1.5rem;
}
.fixed-height {
    min-width: 200px;
    padding: 1px;
    max-height: 35%;
    overflow: auto;
}
</style>
'; ?>

<script src="<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
/library/js/DocumentTreeMenu.js"></script>
<script src="<?php echo $this->_tpl_vars['GLOBALS']['assets_static_relative']; ?>
/dropzone/dist/dropzone.js"></script>

<script>
	function callTemplateModule() <?php echo '{'; ?>

		top.restoreSession();
		let tele  = document.getElementById("template_filename");
		let tname = encodeURIComponent(tele.options[tele.selectedIndex].value);
        let callUrl = '<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
/portal/patient/onsitedocuments?pid=' + <?php if (! empty ( $this->_tpl_vars['patient_id'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['patient_id'])) ? $this->_run_mod_handler('js_url', true, $_tmp) : js_url($_tmp)); ?>
<?php endif; ?> + '&catid=' + <?php if (! empty ( $this->_tpl_vars['category_id'] )): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['category_id'])) ? $this->_run_mod_handler('js_url', true, $_tmp) : js_url($_tmp)); ?>
<?php endif; ?> + '&is_module=true&new=' + tname;
		location.assign(callUrl);
	<?php echo '}'; ?>

</script>
<title><?php echo smarty_function_xlt(array('t' => 'Documents'), $this);?>
</title>
</head>
<!-- ViSolve - Call expandAll function on loading of the page if global value 'expand_document' is set -->
<?php if ($this->_tpl_vars['GLOBALS']['expand_document_tree']): ?>
  <body onload="javascript:objTreeMenu_1.expandAll();return false;">
<?php else: ?>
  <body>
<?php endif; ?>
<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-sm-12">
			<div class="title">
				<h2><?php echo smarty_function_xlt(array('t' => 'Documents'), $this);?>
 <a href='interface/patient_file/summary/demographics.php' onclick='top.restoreSession()' title="<?php echo smarty_function_xla(array('t' => 'Go Back'), $this);?>
" ><i id='advanced-tooltip' class='fa fa-undo fa-2x small' aria-hidden='true'></i></a></h2>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-3">
			<div id="documents_list">
				<fieldset>
                    <legend><?php echo smarty_function_xlt(array('t' => 'Documents List'), $this);?>
</legend>
                    <div class="pl-3">
                        <div class="form-inline float-right" id="patientSearch">
                            <select id="selectPatient" class="form-control" type="text" data-placeholder="<?php echo ((is_array($_tmp=$this->_tpl_vars['place_hld'])) ? $this->_run_mod_handler('attr', true, $_tmp) : attr($_tmp)); ?>
">
                                <option></option>
                            </select>
                            <button id='pid' type="button" class='float-right btn btn-primary pBtn'>&times;</button>
                        </div>
                        <a id="list_collapse" href="#" onclick="javascript:objTreeMenu_1.collapseAll();return false;">&nbsp;(<?php echo smarty_function_xlt(array('t' => 'Collapse all'), $this);?>
)</a>
                        <?php echo $this->_tpl_vars['tree_html']; ?>

                    </div>
			    </fieldset>
            </div>
		</div>
		<div class="col-sm-9">
			<div id="documents_actions">
				<fieldset>
					<legend><?php echo smarty_function_xlt(array('t' => 'Document Uploader/Viewer'), $this);?>
</legend>
                    <div style="padding: 0 10px">
						<?php if (! empty ( $this->_tpl_vars['message'] )): ?>
							<div class='text' style="margin-bottom:-10px; margin-top:-8px; padding:10px;"><i><?php echo ((is_array($_tmp=$this->_tpl_vars['message'])) ? $this->_run_mod_handler('text', true, $_tmp) : text($_tmp)); ?>
</i></div><br />
						<?php endif; ?>
						<?php if (! empty ( $this->_tpl_vars['messages'] )): ?>
							<div class='text' style="margin-bottom:-10px; margin-top:-8px; padding:10px;"><i><?php echo ((is_array($_tmp=$this->_tpl_vars['messages'])) ? $this->_run_mod_handler('text', true, $_tmp) : text($_tmp)); ?>
</i></div><br />
						<?php endif; ?>
                        <?php if (! empty ( $this->_tpl_vars['activity'] )): ?><?php echo $this->_tpl_vars['activity']; ?>
<?php endif; ?>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div><!--end of container div-->
<script>
var curpid = <?php echo ((is_array($_tmp=$this->_tpl_vars['cur_pid'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
var newVersion= <?php echo ((is_array($_tmp=$this->_tpl_vars['is_new'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
var demoPid = <?php echo ((is_array($_tmp=$this->_tpl_vars['demo_pid'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
var inUseMsg = <?php echo ((is_array($_tmp=$this->_tpl_vars['used_msg'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
;
<?php echo '
if(curpid == demoPid && !newVersion){
    $("#patientSearch").hide();
}
else{
    $("#pid").text(curpid);
}
$(function () {
    $("#selectPatient").select2({
        ajax: {
            url: "'; ?>
<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
/library/ajax/document_helpers.php<?php echo '",
            dataType: \'json\',
            data: function(params) {
                return {
                  '; ?>

                  csrf_token_form: <?php echo ((is_array($_tmp=$this->_tpl_vars['CSRF_TOKEN_FORM'])) ? $this->_run_mod_handler('js_escape', true, $_tmp) : js_escape($_tmp)); ?>
,
                  <?php echo '
                  term: params.term
                };
            },
            processResults: function(data) {
                return  {
                    results: $.map(data, function(item, index) {
                        return {
                            text: item.label,
                            id: index,
                            value: item.value,
                            label: item.label
                        }
                    })
                };
                return x;
            },
            cache: true
        },
        minimumInputLength: 3
    });

    $(\'#selectPatient\').on(\'select2:select\', function (e) {
        e.preventDefault();
        if (e.params.data.value == \'00\' && ! e.params.data.label.match('; ?>
<?php echo smarty_function_xlj(array('t' => 'Reset'), $this);?>
<?php echo ')){
            alert(inUseMsg);
            return false;
        }
        $(this).val(e.params.data.label);
        location.href  = "'; ?>
<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
<?php echo '/controller.php?document&list&patient_id=" + encodeURIComponent(e.params.data.value) + "&patient_name=" + encodeURIComponent(e.params.data.label);
        $("#pid").text(e.params.data.value);
    });

    $(".pBtn").click(function(event) {
    var $input = $("#selectPatient");
    $input.val(\'\');
    });
});
$("#list_collapse").detach().appendTo("#objTreeMenu_1_node_1 nobr");

// functions to view and pop out documents as needed.
//
$(function () {
    $("img[id^=\'icon_objTreeMenu_\']").tooltip({
        items: $("img[src*=\'file3.png\']"),
        content: '; ?>
<?php echo smarty_function_xlj(array('t' => "Double Click on this icon to pop up document in a new viewer."), $this);?>
<?php echo '
    });

    $("img[id^=\'icon_objTreeMenu_\']").on(\'dblclick\', function (e) {
        let popsrc = $(this).next("a").attr(\'href\') || \'\';
        let diview = $(this).next("a").text();
        let dflg = false;
        if (!popsrc.includes(\'&view&\')) {
            return false;
        } else if (diview.toLowerCase().includes(\'.dcm\') || diview.toLowerCase().includes(\'.zip\')) {
            popsrc = "'; ?>
<?php echo $this->_tpl_vars['GLOBALS']['webroot']; ?>
<?php echo '/library/dicom_frame.php?web_path=" + popsrc;
            dflg = true;
        }
        popsrc = popsrc.replace(\'&view&\', \'&retrieve&\') + \'as_file=false\';
        let poContentModal = function () {
            let wname = \'_\' + Math.random().toString(36).substr(2, 6);
            let opt = "menubar=no,location=no,resizable=yes,scrollbars=yes,status=no";
            window.open(popsrc, wname, opt);
        };

        let btnText = '; ?>
<?php echo smarty_function_xlj(array('t' => 'Full Screen'), $this);?>
<?php echo ';
        let btnClose = '; ?>
<?php echo smarty_function_xlj(array('t' => 'Close'), $this);?>
<?php echo ';
        let size = \'modal-xl\';
        dlgopen(popsrc, \'popdoc\', size, 700, \'\', \'\', {
            buttons: [
                {text: btnText, close: true, style: \'primary btn-sm\', click: poContentModal},
                {text: btnClose, close: true, style: \'secondary btn-sm\'}
            ],
            allowResize: true,
            allowDrag: true,
            dialogId: \'\',
            type: \'iframe\'
        });
        return false;
    });
});

$(function () {'; ?>

    <?php echo smarty_function_datetimepickerSupport(array(), $this);?>

<?php echo '});'; ?>


</script>
</body>
</html>