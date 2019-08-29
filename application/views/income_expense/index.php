<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m6">
<h5 class="breadcrumbs-title"><i class="fa fa-money"></i> Income & Expense</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Income & Expense</li>
</ol>
</div>
<div class="col s12 m6">
<a href="<?=base_url('incomeexpense/add_expense')?>" class="btn btn-small purple"><i class="fa fa-plus"></i> Add Expense</a>
<a href="<?=base_url('incomeexpense/add_expense?type=income')?>" class="btn btn-small indigo accent-3"><i class="fa fa-plus"></i> Add Income</a>
</div>
</div>
</div>
<div class="row mt10 sum-up height-600">
<div class="col s12 m6 l6 res">
<div class="card animated bounceInUp"><div class="card-content pad0"><p class="card-title center mb0">Below is the graph of Income v/s Expense</p><canvas id="ie_canvas" width="100%"></canvas></div></div>
</div>
<div class="col s12 m6 l3">
<div class="card animated bounceInDown">
<div class="card-content">
<h3>Total Income</h3>
<p><i class="fa fa-rupee"></i> <?=number_format($income)?>/- <a class="indigo accent-3 btn btn-small" href="<?=base_url('incomeexpense/expense_list?type=income')?>"><i class="mdi-action-view-list left"></i> view</a></p>
</div>
</div>
</div>
<div class="col s12 m6 l3">
<div class="card animated bounceInDown">
<div class="card-content">
<h3>Total Expense</h3>
<p><i class="fa fa-rupee"></i> <?=number_format($expense)?>/- <a class="red accent-3 btn btn-small" href="<?=base_url('incomeexpense/expense_list')?>"><i class="mdi-action-view-list left"></i> view</a></p>
</div>
</div>
</div>
<div class="col s12 m6 l3">
<div class="card animated bounceInDown">
<div class="card-content">
<h3>Total Balance</h3>
<p><i class="fa fa-rupee"></i> <?=number_format($balance)?>/-</p>
</div>
</div>
</div>
<div class="col s12 m6">
	<div class="card animated bounceInUp">
		<div class="card-content">
			<h3>Expense Category</h3>
			<button data-type="2" class="btn btn-small trigger-cat-model"><i class="fa fa-plus"></i> Add Category</button>
			<ol class="expense_cat_list">
				<?php
					foreach ($e_cat as $key => $value) {
						echo '<li class="mb15 e_list_'.$value->id.'"><span>'.h($value->name).'</span> <button data-id="'.$value->id.'" class="iecat-rem btn-flat"><i class="fa m0 fa-close"></i></button> <button data-id="'.$value->id.'" data-name="'.h($value->name).'" class="ie-cat-edit-trigger btn-flat"><i class="fa m0 fa-edit"></i></button></li>';
					}
					unset($value);
				?>
			</ol>
		</div>
	</div>
</div>
<div class="col s12 m6">
	<div class="card animated bounceInUp">
		<div class="card-content">
			<h3>Income Category</h3>
			<button data-type="1" class="btn btn-small trigger-cat-model"><i class="fa fa-plus"></i> Add Category</button>
			<ol class="income_cat_list">
				<?php
					foreach ($i_cat as $key => $value) {
						echo '<li class="mb15 e_list_'.$value->id.'"><span>'.h($value->name).'</span> <button data-id="'.$value->id.'" class="iecat-rem btn-flat"><i class="fa m0 fa-close"></i></button> <button data-id="'.$value->id.'" data-name="'.h($value->name).'" class="ie-cat-edit-trigger btn-flat"><i class="fa m0 fa-edit"></i></button></li>';
					}
					unset($value);
				?>
			</ol>
		</div>
	</div>
</div>
</div>
<div class="modal" id="add-ecat">
<?=form_open('','id="expense-category"')?>
<div class="modal-content">
<div class="row">
<h5 class="col s12">Add Category</h5>
<div class="col s12 input-field"><input autocomplete="off" type="text" id="e-name" name="name" maxlength="25"><label for="e-name">Category Name</label></div>
<div class="col s12 input-field"><button class="indigo add-iec accent-3 right btn" type="submit">Add Category</button></div>
</div>
</div>
<input type="hidden" name="type" class="cattype" value="0">
<?=form_close()?>
</div>
<div class="modal" id="edit-ecat">
<?=form_open('','id="expense-category-edit"')?>
<div class="modal-content">
<div class="row">
<h5 class="col s12">Edit Expense Category</h5>
<div class="col s12 input-field"><input autocomplete="off" type="text" id="ee-name" name="name" maxlength="25"><label for="ee-name">Category Name</label></div>
<div class="col s12 input-field"><button class="indigo edit-iec accent-3 right btn" type="submit">Save Category</button></div>
</div>
</div>
<input type="hidden" name="id" class="ecid">
<?=form_close()?>
</div>