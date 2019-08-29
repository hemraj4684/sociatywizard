var dt;
function DT_TRIGGER(){
	obj={dom:'lBfrtip',buttons:[{extend:'print',title:$('#hidden_sname').val(),message:$('#hidden_saddr').val(),exportOptions:{columns:':visible'},customize:function(win){$(win.document.body).find('table').css({'box-shadow':'none','border-right':'1px solid #bbbbbb','border-left':'1px solid #bbbbbb'});}},{text:'Excel',action:function(e,dt,node,config){$('.data_list').tableExport({type:'excel',escape:'false'});}},'colvis',],"aaSorting":[],"autoWidth":false}
	obj['aoColumnDefs']=[{"bSortable":false,'aTargets':[0,2]},{"bSearchable":false,'aTargets':[0]}];
	dt = $('.data_list').dataTable(obj).columnFilter({aoColumns:[null,null,null,null,null,null]});
	$('.dataTables_wrapper select').show();
}
$(d).ready(function(){
$('#v_flat').searchflat()
$('.datepicker').pickadate({
selectMonths: true,
selectYears: 10,
max:true,
format: 'dd-mm-yyyy'
});
t_head=$('.data_list thead').html()
DT_TRIGGER()


$('.timepicker').pickatime({autoclose:false,darktheme:true,twelvehour:true});
$('.visitor_search_form').submit(function(e){PD(e);
if($('#ex_df').val().trim().length>0){
t=$(this);$('.visitor-ds-btn').prop('disabled',true)
page_loader()
$.ajax({
	type:'post',
	url:URL+'visitorsform/search_by_date',
	data:t.serialize(),
	dataType:'json',
	success:function(r){
		if(r.to_err){
			Materialize.toast(r.to_err,4000,'red accent-3 bold-500')
		}
		if(r.success && r.data){
			dt.fnDestroy()
			$('.data_list').empty()
			$('.data_list').html(r.data)
			DT_TRIGGER()
		}
	},error:function(){
		Materialize.toast('An error occured!',4000,'red accent-3 bold-500')
		Materialize.toast('Please Try Again!',4000,'red accent-3 bold-500')
	},complete:function(){
		$('.visitor-ds-btn').prop('disabled',false)
		page_loader_exit()
	}
})
}else{Materialize.toast('Please Enter Date Of Visit',4000,'red accent-3 bold-500')}
})
$('#visitors_app_credentials').submit(function(e){PD(e);
t=$(this)
$('.w-submit-btn').prop('disabled',true)
page_loader()
$.ajax({
	type:'post',
	url:URL+'visitorsform/update_credentials',
	data:t.serialize(),
	dataType:'json',
	complete:function(){
		page_loader_exit()
		$('.w-submit-btn').prop('disabled',false)
	},error:function(err){alert('An error occured!')},
	success:function(r){
		if(r.uerr){
			Materialize.toast(r.uerr,4000,'red accent-3 bold-500')
		}
		if(r.perr){
			Materialize.toast(r.perr,4000,'red accent-3 bold-500')
		}
		if(r.success){
			$('#credentials_modal').closeModal();
			Materialize.toast('Changes Saved Successfully!',4000,'green bold-500')
			$('#v_app_password').val('')
			$('#w_logout').attr('checked', false);
		}
	}
})
})
$('.remove-btn').click(function(){
	vlist = $('.v-item:checked')
	if(vlist.length>0){
		if(confirm('Are you sure ?')){
			t=$(this)
			page_loader()
			t.prop('disabled',true)
			dataid = {}
			dataid.id = {}
			dataid.user_token = token
			$.each(vlist,function(k,v){
				dataid.id[k] = $(this).val()
			})
			$.ajax({
				dataType:'json',
				type:'post',
				url:URL+'visitorsform/remove_visitors',
				data:dataid,
				success:function(r){
					if(r.iderr){
						Materialize.toast(r.iderr,4000,'red accent-3 bold-400 z-depth-2')
					}
					if(r.success){
						Materialize.toast('Records Deleted Successfully!',4000,'green bold-400 z-depth-2')
						$.each(vlist,function(k,v){
							$(".data_list").dataTable().fnDeleteRow($(this).parents('tr'));
						})
					}
				},error:function(){
					alert('An error occured!')
				},complete:function(){
					page_loader_exit()
					t.prop('disabled',false)
				}
			})
		}
	} else {
		Materialize.toast('*Please Select A Checkbox*',400000,'red accent-3 bold-400 z-depth-2')
	}
})



$('#new_visitor_form').submit(function(e){PD(e);t=$(this)

	page_loader()
	$.ajax({
		type:'post',
		url:URL+'visitorsform/new_visitor',
		data:new FormData(this),
		processData:false,
		contentType:false,
		dataType:'json',
		success:function(r){
			if(r.nerr){
				Materialize.toast(r.nerr,4000,'red accent-3')
			}
			if(r.derr){
				Materialize.toast(r.derr,4000,'red accent-3')
			}
			if(r.success && r.data){
				dt_nodes = dt.fnGetNodes();
				dt.fnDestroy()
				$('.data_list').empty()
				v_img = URL+'assets/images/user_image.png'
				if(r.data[3]){
					v_img = URL+'assets/visitors/'+r.data[7]+'/'+r.data[3]
				}
				$('.data_list').html('<thead>'+t_head+'</thead><tbody><tr><td><input type="checkbox" class="filled-in v-item" value="'+r.data[5][1]+'" id="v-c-'+r.data[5][1]+'" name="visitor[]"><label for="v-c-'+r.data[5][1]+'" class="valign-top"></label> <img width="100" src="'+v_img+'" class="responsive-img"></td><td>'+r.data[0]+'</td><td>'+r.data[1]+'</td><td>'+r.data[6]+'</td><td>'+r.data[2]+'</td><td>'+r.data[4]+'</td></tr></tbody>')
				$('.data_list tbody').append(dt_nodes)
				DT_TRIGGER()
				t[0].reset()
				Materialize.updateTextFields();
				Materialize.toast('Visitor Added Successfully!',4000,'green')
				$('.new_v_img').attr('src', URL+'assets/images/user_image.png');
			}
		},
		complete:function(){
			page_loader_exit()
		}
	})
})

$('.new_v_img').click(function(){
	$('.v_userfile').click()
})

$('.v_userfile').change(function(){
	reader = new FileReader();
	reader.onload = function (e) {
        $('.new_v_img').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);
})


})