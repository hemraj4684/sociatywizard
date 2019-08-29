var dt;
$(d).ready(function(){
dt = $('.data_list').DataTable(dt_obj)
$('.dataTables_wrapper select').show();
$('#vendor_new').submit(function(e){
	PD(e)
	t=$(this)
	$('.submit-btn').prop('disabled',true)
	$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Saving...')
	page_loader()
	$.ajax({
		type:'post',
		url:URL+'vendersform/add_new',
		data:t.serialize(),
		dataType:'json',
		complete:function(){
			page_loader_exit()
			$('.submit-btn').html('Submit')
			$('.submit-btn').prop('disabled',false)
		},
		success:function(res){
			if(res.success){
				location.reload()
			}
			if(res.name){
				$('.name').text(res.name)
			} else {
				$('.name').text('')
			}
			if(res.no1){
				$('.no1').text(res.no1)
			} else {
				$('.no1').text('')
			}
			if(res.no2){
				$('.no2').text(res.no2)
			} else {
				$('.no2').text('')
			}
			if(res.address){
				$('.address').text(res.address)
			} else {
				$('.address').text('')
			}
			if(res.notes){
				$('.notes').text(res.notes)
			} else {
				$('.notes').text('')
			}
			if(res.category){
				$('.category').text(res.category)
			} else {
				$('.category').text('')
			}
		}
	})
})
$('#vendor_edit').submit(function(e){
	PD(e)
	t=$(this)
	id=t.data('id')
	$('.submit-btn').prop('disabled',true)
	$('.submit-btn').html('<i class="fa fa-spinner fa-pulse"></i> Saving...')
	page_loader()
	$.ajax({
		type:'post',
		url:URL+'vendersform/editing/'+id,
		data:t.serialize(),
		dataType:'json',
		complete:function(){
			page_loader_exit()
			$('.submit-btn').html('Submit')
			$('.submit-btn').prop('disabled',false)
		},
		success:function(res){
			if(res.success){
				location.reload()
			}
			if(res.name){
				$('.name_e').text(res.name)
			} else {
				$('.name_e').text('')
			}
			if(res.no1){
				$('.no1_e').text(res.no1)
			} else {
				$('.no1_e').text('')
			}
			if(res.no2){
				$('.no2_e').text(res.no2)
			} else {
				$('.no2_e').text('')
			}
			if(res.address){
				$('.address_e').text(res.address)
			} else {
				$('.address_e').text('')
			}
			if(res.notes){
				$('.notes_e').text(res.notes)
			} else {
				$('.notes_e').text('')
			}
			if(res.category){
				$('.category_e').text(res.category)
			} else {
				$('.category_e').text('')
			}
		}
	})
})
})
$(d).on('click','.remove_vendor',function(){
t=$(this)
id=t.data('id')
if(confirm('Are you sure, you want to delete this vendor ?')){
	page_loader()
	$.ajax({
		type:'get',
		url:URL+'vendersform/remove_vendor/'+id,
		complete:function(){
			page_loader_exit()
			dt.row(t.parents('tr')).remove().draw(false);
			dt.column(0).nodes().each( function (cell, i) {
		        cell.innerHTML = i+1;
	        });
	        Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Vendor Deleted Successfully!',3000,'green success-area')
		}
	})
}
})
$(d).on('click','.edit-btn',function(){
p=$(this).parents('tr')
$('#name_e').val(p.find('td:eq(1)').text())
$('#no1_e').val(p.find('td:eq(2)').data('no1'))
$('#no2_e').val(p.find('td:eq(2)').data('no2'))
$('#address_e').val(p.find('td:eq(4)').data('addr'))
$('#notes_e').val(p.find('td:eq(5)').data('note'))
$('#category_e').val(p.find('td:eq(3)').text())
$('#edit_modal label').addClass('active')
$('#id').val($(this).data('id'))
})