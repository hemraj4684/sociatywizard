<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12 m12">
<h5 class="breadcrumbs-title"><?=$this->p_var?></h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active"><?=$this->p_var?></li>
</ol>
</div>
</div>
</div>
<div class="row mt10">
<div class="col s12 m6">
<div class="card"><div class="card-content">
<span class="card-title"><?=$this->p_var?></span>
<div class="sum-res"></div>
<canvas id="chart-area" width="100%"></canvas>
</div>
</div>
</div>
<div class="col s12 m3">
<a class="block card-panel blue white-text" href="<?=base_url('registeredmembers/association_members')?>"><?=$assoc->total?> Association Members</a>
</div>
<div class="col s12 m3">
<a class="purple white-text block card-panel" href="<?=base_url('registeredmembers/multiple_flats')?>"><?=$multi_flat->total?> Members with multiple flats</a>
</div>
<div class="col s12 m3">
<a class="red accent-3 white-text block card-panel" href="<?=base_url('registeredmembers/unassigned_flats')?>"><?=$unassigned->total?> Members not assigned flats</a>
</div>
<div class="col s12 m3">
<a class="green white-text block card-panel" href="<?=base_url('registeredmembers/requests')?>"><?=$newreq->total?> New Member Request</a>
</div>
<div class="col s12 m3">
<a class="grey darken-3 white-text block card-panel" href="<?=base_url('registeredmembers/add_member')?>"><i class="fa fa-plus"></i> Add A New Member</a>
</div>
</div>