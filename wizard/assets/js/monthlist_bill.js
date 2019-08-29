var table = {};
dt_obj["aoColumnDefs"]={"bSortable":false,'aTargets':[0,2,4,5,6,7]},{"bSearchable":false,'aTargets':[0,7]}
$(d).ready(function(){table=$('.data_list').dataTable(dt_obj).columnFilter({aoColumns:[null,null,null,null,{type:"select","bRegex":true,values:[{value:'^Paid',label:'Paid'},{value:'^Not Paid',label:'Not Paid'}]},null,null,null]});
$('.dataTables_wrapper select').show();
$('.remove_bill').click(function(){
    t = $(this)
    file = $('.file_selected:checked')
    if(file.length===0){
        Materialize.toast('Please Select A Bill To Delete',1000,'red accent-3 bold-500')
    } else {
        if(confirm('Are you sure ?')){
            page_loader()
            t.prop('disabled',true)
            dataid = {}
            dataid.user_token = token
            dataid.id = {}
            $.each(file,function(k,v){
                dataid.id[k] = $(this).val()
            })
            $.ajax({
                type:'post',
                url:URL+'invoice/delete_bill',
                data:dataid,
                dataType:'json',
                complete:function(){
                    t.prop('disabled',false)
                    page_loader_exit()
                },
                success:function(res){
                    if(res.success){
                        Materialize.toast('<i class="fa fa-thumbs-o-up"></i> &nbsp; Bill(s) Deleted Successfully',2000,'green success-area')
                        $.each(file,function(k,v){
                            $(".data_list").dataTable().fnDeleteRow($(this).parents('tr'));
                        })
                    }
                    if(res.err){
                        Materialize.toast('Please Select A Bill To Delete',2000,'red accent-3 bold-500')
                    }
                }
            })
        }
    }
})

})