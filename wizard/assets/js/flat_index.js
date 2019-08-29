$(d).ready(function(){
	wing = $('input[name="wing[]"]')
	wings = '';
	wing.each(function(k,v){
		wings += $(this).val()+','
	})
	$.ajax({
		type:'get',
		dataType:'json',
		url:URL+'flatsform/flats_summary',
		success:function(res){
			t_data = '<table class="bordered"><tbody>'
			if(res.result){
				var pieData = []
				inc = 0
				t_flat = 0
				$.each(res.result,function(k,v){
					t_flat = t_flat + v.total
					t_data += '<tr><th>'+k+'</th><td>'+v.total+'</td><td><a class="btn btn-flat right indigo accent-3 white-text btn-small" href="'+URL+'flats/by_type/'+v.id+'/">view</a></td></tr>'
					++inc
				})
				t_data += '<tr><th>Total Flats</th><td>'+t_flat+'</td><td><a class="btn btn-flat right indigo accent-3 white-text btn-small" href="'+URL+'flats/all_flats">view</a></td></tr></tbody></table>'
				$('.sum-res').html(t_data)
			}
		}
	})
	$.ajax({
		type:'get',
		url:URL+'flatsform/blocks_summary/'+wings,
		dataType:'json',
		success:function(res){
			t_data1 = '';
			l_id = 0
			t_flat1 = 0
			if(res.data){
				incm = 0
				var pieDatas;
				$.each(res.data,function(kk,vv){
					t_data1 = '<table class="bordered"><tbody>'
					inc = 0
					pieDatas = []
					$.each(vv,function(k,v){
						color = colors[Math.floor(Math.random() * colors.length)]
						pieDatas[inc] = {
							value:parseInt(v.total),
							color:color,
							highlight:'rgba(0,0,0,0.7)',
							label:k
						}
						++inc
						t_flat1 = t_flat1 + v.total
						t_data1 += '<tr><th>'+k+'</th><td>'+v.total+'</td><td><a class="btn btn-flat right indigo accent-3 white-text btn-small" href="'+URL+'flats/by_type/'+v.id+'/'+v.wing+'">view</a></td></tr>'
						l_id = v.wing
					})
					ctx2 = d.getElementById('summary-'+incm).getContext("2d");
					new Chart(ctx2).Pie(pieDatas, {
						responsive:true
					});
					t_data1 += '<tr><th>Total Flats</th><td>'+t_flat1+'</td><td><a class="btn btn-flat right indigo accent-3 white-text btn-small" href="'+URL+'flats/list_by_block/'+l_id+'">view</a></td></tr></tbody></table>'
					t_data1 += '</tbody></table>';
					$('.sum-res-'+incm).html(t_data1)
					++incm
					t_flat1 = 0
				})
			}
		}
	})
})