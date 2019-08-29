$(d).ready(function(){
	var ctx = document.getElementById("collecton_chart").getContext("2d");
	var data = {
	    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
	    datasets: [
	        {
	            label: "Total Amount Of Bill Generated",
	            fillColor: "rgba(25,50,125,0.4)",
	            strokeColor: "rgba(25,52,125,0.75)",
	            highlightFill: "rgba(220,220,220,1)",
	            highlightStroke: "rgba(220,220,220,1)",
	            data: ds
	        },{
	            label: "Total Amount Collected",
	            fillColor: "rgba(25,50,125,0.4)",
	            strokeColor: "rgba(25,52,125,0.75)",
	            highlightFill: "rgba(220,220,220,1)",
	            highlightStroke: "rgba(220,220,220,1)",
	            data: co
	        }
	    ]
	};
	new Chart(ctx).Line(data,{responsive:true});
})