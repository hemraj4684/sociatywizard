$(d).ready(function(){
	var ctx = document.getElementById("chart-area").getContext("2d");
	$.ajax({
		type:'get',
		dataType:'json',
		url:URL+'flatsform/members_summary',
		success:function(res){
			if(res.result){
				tot = 0
				var polarData = []
				t_data = '<table class="bordered"><thead><tr><th>Block</th><th colspan="2">No of Members</th></tr></thead><tbody>'
				$.each(res.result,function(k,v){
					color = colors[Math.floor(Math.random() * colors.length)]
					polarData[k] = {
						value:parseInt(v.total),
						color:color,
						highlight:'rgba(0,0,0,0.7)',
						label:v.wing
					}
					tot = tot + parseInt(v.total)
					t_data += '<tr><td>'+v.wing+'</td><td>'+v.total+'</td><td class="right-align"><a class="btn btn-small indigo accent-3" href="'+URL+'registeredmembers/member_list/'+v.id+'">view</a></td></tr>'
				})
				new Chart(ctx).PolarArea(polarData, {
					responsive:true
				});
				t_data += '<tr><th>Total Members</th><td>'+tot+'</td><td class="right-align"><a class="btn btn-small indigo accent-3" href="'+URL+'registeredmembers/member_list">view</a></td></tr></tbody></table>'
				$('.sum-res').html(t_data)
			}
		}
	})
})