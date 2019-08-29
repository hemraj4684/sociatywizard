<div class="row"><div class="col s12">
<h3 class="center report-title bold-500 font-20"><?=$report_title?><br><small>Total <?=($what==2) ? 'Expense' : 'Income' ?> : <i class="fa fa-rupee"></i> <span class="total_html"></span>/-</small></h3>
<p class="text-darken-1 grey-text font-12 right-align report-desc">Report Collected from www.societywizard.com on <?=date('dS F Y, h:ia')?></p>
<div class="table-holder">
<?php
$this->load->view('reports/expense_table');
?>
</div></div></div>