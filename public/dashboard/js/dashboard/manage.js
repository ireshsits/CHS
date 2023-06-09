table = [];
var filters = {
	month : '',
	raise_by: '',
	type : 'ALL'
};

var graph = {
	fontSize : 12,
	fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
	fontColor: '#000'
	
}

$(function() {
	initSelectors();
	initType();
	initSelectorsNoClear({'search': false});
	initCharts();
	eventHandler();
});
function initCharts() {
	initMonthlyCharts();
	 initAnnualCharts();
}

function eventHandler() {
//	$('#month').on('click', function(e) {
//		e.preventDefault();
//		initAnalysis();
//	});

	var selects = [ '#month' ,'#raise_by', '#select-type'];
	$.each(selects, function(index, element) {
		$(element).on('select2:select',function(e) {
			if (element == '#month') {
				filters.month = $(e.currentTarget).find("option:selected").val();
				initMonthlyCharts();
				destroyTable('dashboard/month/resol');
			}
			if (element == '#raise_by' || element == '#select-type') {
				if(element == '#raise_by')
					filters.raise_by = $(e.currentTarget).find("option:selected").val();
				if(element == '#select-type')
					filters.type = $(e.currentTarget).find("option:selected").val();
					
				initMonthlyCharts();
				destroyTable('dashboard/month/resol');
				initAnnualCharts();
				destroyTable('dashboard/annual/resol');
			}
		});
		$(element).on('select2:unselect', function(e) {
			if (element == '#month') {
				filters.month = '';
				initMonthlyCharts();
				destroyTable('dashboard/month/resol');
			}
			if (element == '#raise_by' || element == '#select-type') {
				if(element == '#raise_by')
					filters.raise_by = '';
				if(element == '#select-type')
					filters.type = '';

				initMonthlyCharts();
				destroyTable('dashboard/month/resol');
				initAnnualCharts();
				destroyTable('dashboard/annual/resol');
			}
		});
		// if ($.inArray(element,
		// ['#analysis-year','#analysis-status','#area_id_fk','#category_id_fk'])
		// !== -1) {
		// $(element).trigger({
		// type: 'select2:select'
		// });
		// }
	});
}

function emptyMonthlyChartView() {
	$("#chart3_legend").empty();
	if (typeof window.myMonthlyCountChartView !== 'undefined')
		window.myMonthlyCountChartView.destroy();	
	if (typeof window.myMonthlyAreaWiseChartView !== 'undefined')
		window.myMonthlyAreaWiseChartView.destroy();
	if (typeof window.myMonthlyStatusChartView !== 'undefined')
		window.myMonthlyStatusChartView.destroy();
}
function emptyAnnualChartView() {
	$("#chart4_legend").empty();
	if (typeof window.myAnnualCountChartView !== 'undefined')
		window.myAnnualCountChartView.destroy();	
	if (typeof window.myAnnualAreaWiseChartView !== 'undefined')
		window.myAnnualAreaWiseChartView.destroy();
	if (typeof window.myAnnualStatusChartView !== 'undefined')
		window.myAnnualStatusChartView.destroy();
}
function initMonthlyCharts() {
	$.ajax({
		url : custom_url + '/dashboard/getMonthlyCharts',
		type : 'POST',
		data : filters,
		dataType : 'JSON',
		beforeSend : function() {
			emptyMonthlyChartView();
		}
	}).done(function(data, textStatus, jqXHR) {
		drawMonthlyCharts(data);
	}).fail(function(jqXHR, textStatus, errorThrown) {

	}).always(function() {

	});
}
function initAnnualCharts() {
	$.ajax({
		url : custom_url + '/dashboard/getAnnualCharts',
		type : 'POST',
		data : filters,
		dataType : 'JSON',
		beforeSend : function() {
			emptyAnnualChartView();
		}
	}).done(function(data, textStatus, jqXHR) {
		drawAnnualCharts(data);
	}).fail(function(jqXHR, textStatus, errorThrown) {

	}).always(function() {

	});
}

Array.prototype.random = function () {
	  return this[Math.floor((Math.random()*this.length))];
}

function generateRandomColors(length) {
// var colors = [];
// for (var i = 0; i < length; i++) {
// colors.push('#' + (Math.random().toString(16) + '0000000').slice(2, 8));
// }
// return colors;

	var colorsArr = [ '#1ABC9C', '#2ECC71', '#3498DB', '#9B59B6', '#34495E',
			'#F1C40F', '#E67E22', '#E74C3C', '#6D6FA7', '#95A5A6', '#16A085',
			'#27AE60', '#2980B9', '#8E44AD', '#2C3E50', '#F39C12', '#D35400',
			'#C0392B', '#BDC3C7', '#7F8C8D' ];
	colors = [];
	for (var i = 0; i < length; i++) {
		colors.push(colorsArr.random());
	}
	return colors;
}

function drawMonthlyCharts(data) {
	drawBoxesOfStats(data['MBOX'],'m');
	drawMonthlyNumberOfStats(data['MNUM']);
	drawMonthlyStatusOfStats(data['MSTATUS']);
	drawMonthlyAreaWiseOfStats(data['MAWISE']);
	initTable(data['MRESOL'],'m');
}

function drawAnnualCharts(data) {
	drawBoxesOfStats(data['ABOX'],'a');
	drawAnnualNumberOfStats(data['ANUM']);
	drawAnnualStatusOfStats(data['ASTATUS']);
	drawAnnualAreaWiseOfStats(data['AAWISE']);
	initTable(data['ARESOL'],'a');
}

function drawMonthlyNumberOfStats(data) {
	var ctx = document.getElementById('myChart1').getContext('2d');
	window.myMonthlyCountChartView = new Chart(ctx, monthlyChartConfig(data,'Complaint Modes'));
}

function drawMonthlyStatusOfStats(data){
	var config = {
			type : 'pie',
			data : {
				datasets : [ {
					label : 'Complaint Status',
					data : data.data,
					backgroundColor : ['#00b56a', '#95A5A6'], // generateRandomColors(data.data.length)
				} ],
				labels : data.labels
			},
			plugins: [ChartDataLabels],
			options : {
				responsive : true,
				legend : {
					display : false
				},
	            legendCallback: function (chart) {             
	                // Return the HTML string here.
	                var text = [];
	                text.push('<ul class="' + chart.id + '-legend">');
	                // for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
	                for (var i = 0; i < chart.data.labels.length; i++) {
	                    text.push('<li><span id="legend-' + i + '-item" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"   onclick="updateDataset(event, ' + '\'' + i + '\'' + ',\'MSTATUS\')">');
	                    if (chart.data.labels[i]) {
	                        text.push(chart.data.labels[i]);
	                    }
	                    text.push('</span></li>');
	                }
	                text.push('</ul>');
	                return text.join("");
	            },
				title : {
					display : true,
					text : data.title
				},
	            animation: {
	                animateScale: true,
	                animateRotate: true,
	                onComplete: function(animation){
	                    var firstSet = animation.chartInstance.chart.config.data.datasets[0].data,
	                    dataSum = firstSet.reduce((accumulator, currentValue) => accumulator + currentValue);
	                    if(typeof firstSet !== "object" || dataSum === 0){
	                    }else{
	                    	$("#chart3_legend").html(window.myMonthlyStatusChartView.generateLegend());
	                    }
	                }
	            },
	            tooltips: {
	                intersect: false,
	                callbacks: {
	                    label: function (tooltipItem, data) {
	                        let label = data.datasets[tooltipItem.datasetIndex].label + ' - ' + data.labels[tooltipItem.index];
	                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
	                        return label + ': ' + datasetLabel.toLocaleString() + '%';
	                    }
	                }
	            },
			    plugins: {
			        // Change options for ALL labels of THIS CHART
			        datalabels: {
			          color: "#fff",
			          anchor: "center",
			          clamp: true,
			          display: function(context) {
			            var index = context.dataIndex;
			            var value = context.dataset.data[index];
			            return value > 0.00;
			          },
			          formatter: (val, ctx) => {
//			                let sum = 0;
//			                let dataArr = ctx.chart.data.datasets[0].data;
//			                dataArr.map(data => {
//			                    sum += data;
//			                });
				            var index = ctx.dataIndex;
			        	  	var value = ctx.dataset.data[index];
			                if(value > 0.00){
				                return  value+'%';
			                }
			            },
			       }
			    }
			},
		};
		// Chart 3 Start
        var ctx = document.getElementById("myChart3").getContext("2d");
        window.myMonthlyStatusChartView = new Chart(ctx, config);
}

function drawMonthlyAreaWiseOfStats(data){
	// Chart 5 Start
	var ctx = document.getElementById('myChart5').getContext('2d');
	window.myMonthlyAreaWiseChartView = new Chart(ctx, monthlyChartConfig(data,'Area Wise'));
}

function drawAnnualNumberOfStats(data){
	// Chart 2 Start
	var ctx = document.getElementById('myChart2').getContext('2d');
	window.myAnnualCountChartView = new Chart(ctx, annualChartConfig(data));
}

function drawAnnualStatusOfStats(data){
	
	var config = {
			type : 'pie',
			data : {
				datasets : [ {
					label : 'Complaint Status',
					data : data.data,
					backgroundColor : ['#00b56a', '#95A5A6'], // generateRandomColors(data.data.length)
				} ],
				labels : data.labels
			},
			plugins: [ChartDataLabels],
			options : {
				responsive : true,
				legend : {
					display : false
				},
	            legendCallback: function (chart) {             
	                // Return the HTML string here.
	                var text = [];
	                text.push('<ul class="' + chart.id + '-legend">');
	                // for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
	                for (var i = 0; i < chart.data.labels.length; i++) {
	                    text.push('<li><span id="legend-' + i + '-item" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"   onclick="updateDataset(event, ' + '\'' + i + '\'' + ',\'ASTATUS\')">');
	                    if (chart.data.labels[i]) {
	                        text.push(chart.data.labels[i]);
	                    }
	                    text.push('</span></li>');
	                }
	                text.push('</ul>');
	                return text.join("");
	            },
				title : {
					display : true,
					text : data.title
				},
	            animation: {
	                animateScale: true,
	                animateRotate: true,
	                onComplete: function(animation){
	                    var firstSet = animation.chartInstance.chart.config.data.datasets[0].data,
	                    dataSum = firstSet.reduce((accumulator, currentValue) => accumulator + currentValue);
	                    if(typeof firstSet !== "object" || dataSum === 0){
	                    }else{
	                    	$("#chart4_legend").html(window.myAnnualStatusChartView.generateLegend());
	                    }
	                }
	            },
	            tooltips: {
	                intersect: false,
	                callbacks: {
	                    label: function (tooltipItem, data) {
	                        let label = data.datasets[tooltipItem.datasetIndex].label + ' - ' + data.labels[tooltipItem.index];
	                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
	                        return label + ': ' + datasetLabel.toLocaleString() + '%';
	                    }
	                }
	            },
			    plugins: {
			        // Change options for ALL labels of THIS CHART
			        datalabels: {
			          color: "#fff",
			          anchor: "center",
			          clamp: true,
			          display: function(context) {
			            var index = context.dataIndex;
			            var value = context.dataset.data[index];
			            return value > 0.00;
			          },
			          formatter: (val, ctx) => {
				            var index = ctx.dataIndex;
			        	  	var value = ctx.dataset.data[index];
			        	  	if(value > 0.00){
			        	  		return value+'%';
			                }
			            },
			       }
			    }
			},
		};
	
	// Chart 4 Start
	var ctx = document.getElementById('myChart4').getContext('2d');
	window.myAnnualStatusChartView = new Chart(ctx, config);
}

function drawAnnualAreaWiseOfStats(data){
	// Chart 6 Start
	var ctx = document.getElementById('myChart6').getContext('2d');
	window.myAnnualAreaWiseChartView = new Chart(ctx, annualChartConfig(data));
}

function drawBoxesOfStats(data,mode){
	$('.'+mode+'-title').html(data.title);
	$('.'+mode+'-box-total').html(data.total);
	$('.'+mode+'-box-pending').html(data.pending);
	$('.'+mode+'-box-inprogress').html(data.inprogress);
	$('.'+mode+'-box-replied').html(data.replied);
	$('.'+mode+'-box-completed').html(data.completed);
	$('.'+mode+'-box-closed').html(data.closed);
}

function initTable(data,mode) {
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_stats_'+mode+'-complaint-time-resolution-table');
	
	if(mode == 'm'){
		if(filters.month)
			$('.table-Header-Month').html(moment().month(parseInt(filters.month) - 1).format("MMMM"));
		else
			$('.table-Header-Month').html(moment().format("MMMM"));
			
		columns = [{data : 'description', name: 'description'},{data: 'month', name: 'month'}];
	}else{
		columns = [{data : 'month', name: 'month'},{data: 'complaint_received', name: 'complaint_received'},{data:'attended', name:'attended'},{data:'resolved',name:'resolved'},{data:'unresolved', name:'unresolved'}];
	}
	
	var handleDataTableButtons = function() {
		table[mode] = $('.'+mode+'-complaint-time-resolution-table').DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  "bFilter":false,
		  "bLengthChange" : false,
		  "paging" : false,
		  "ordering" : false,
		  dom: "Blfrtip",
		  buttons: [
		  ],
		  responsive: true,
		  	data: data,
	        columns: columns,
	        createdRow: function (nRow, aData, iDataIndex) {
	            
	        }
		});
	};

	TableManageButtons = function() {
	  "use strict";
	  return {
		init: function() {
		  handleDataTableButtons();
		}
	  };
	}();
	TableManageButtons.init();
	
};

// updateDataset = function(e, legendItem) {
// var index = legendItem.datasetIndex;
// var ci = this.chart;
// var meta = ci.getDatasetMeta(index);
//
// // See controller.isDatasetVisible comment
// meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
//
// // We hid a dataset ... rerender the chart
// ci.update();
// };

updateDataset = function (e, datasetIndex, code) {
    var index = datasetIndex;
    var ci = getChartByCode(e, code);
    var meta = ci.getDatasetMeta(0);    
    var result= (meta.data[datasetIndex].hidden == true) ? false : true;
    if(result==true)
    {
        meta.data[datasetIndex].hidden = true;
        $('#' + e.path[0].id).css("text-decoration", "line-through");
    }else{
        $('#' + e.path[0].id).css("text-decoration","");
        meta.data[datasetIndex].hidden = false;
    }
     
    ci.update();   
};

getChartByCode = function(e, code){
	switch(code){
		case 'MSTATUS'	: return e.view.myMonthlyStatusChartView; break;
		case 'ASTATUS'	: return e.view.myAnnualStatusChartView; break;
	}
}

function monthlyChartConfig(data, label){
return config = {
		type : 'bar',
		data : {
			labels : data.labels,
			datasets : [ {
				label : label,
				data : data.data,
				backgroundColor : data.backgroundColor, //generateRandomColors(data.data.length),
				borderWidth : 1
			} ]
		},
		plugins: [ChartDataLabels],
		options : {
			scales : {
				yAxes : [ {
					ticks : {
						beginAtZero : true
					}
				} ]
			},
			responsive : true,
			legend : {
				display : false,
		        labels: {
			        fontSize: parseInt(graph.fontSize), // We need to force integer here
			        fontFamily: graph.fontFamily,
			        fontColor: graph.fontColor,
			   }
			},
			legendCallback : function(chart) {
				// Return the HTML string here.
				var text = [];
				text.push('<ul class="' + chart.id + '-legend">');
				for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
					text.push('<li><span id="legend-' + i
							+ '-item" style="background-color:'
							+ chart.data.datasets[0].backgroundColor[i]
							+ '"   onclick="updateDataset(event, ' + '\'' + i
							+ '\'' + ')">');
					if (chart.data.labels[i]) {
						text.push(chart.data.labels[i]);
					}
					text.push('</span></li>');
				}
				text.push('</ul>');
				return text.join("");
			},
			title : {
				display : true,
				text : data.title,
		        fontFamily: graph.fontFamily,
		        fontSize: graph.fontSize // No need to explicitly force an integer here
			},
			animation : {
				animateScale : true
			},
			tooltips : {
				intersect : false,
				callbacks : {
					label : function(tooltipItem, data) {
						let label = data.datasets[tooltipItem.datasetIndex].label
								+ ' - ' + data.labels[tooltipItem.index];
						let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return label + ': ' + datasetLabel.toLocaleString();
					}
				}
			},
		    plugins: {
		        // Change options for ALL labels of THIS CHART
		        datalabels: {
		          color: "#fff",
		          anchor: "center",
		          display: function(context) {
		            var index = context.dataIndex;
		            var value = context.dataset.data[index];
		            return value > 0; // display labels with a value greater than 0
		          }
		       }
		    }
		}

	};
}

function annualChartConfig(data){
return config = {
		type : 'bar',
		data : {
			labels : data.labels,
			datasets : data.datasets
		},
		plugins: [ChartDataLabels],
		options : {
			scales : {
				xAxes: [{
					stacked: true,
					ticks: {
//		               callback: function(value, index) {
//		                  if (value !== 0) return value;
//		               }
					}
				}],
				yAxes : [ {
					ticks : {
						beginAtZero : true,
//		                callback: function(value, index) {
//			              if (value !== 0) return value;
//			            }
					},
					stacked: true
				} ]
			},
			responsive : true,
			legend : {
				display : false,
		        labels: {
			        fontSize: parseInt(graph.fontSize), // We need to force integer here
			        fontFamily: graph.fontFamily,
			        fontColor: graph.fontColor,
			   }
			},
			legendCallback : function(chart) {
				// Return the HTML string here.
				var text = [];
				text.push('<ul class="' + chart.id + '-legend">');
				for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
					text.push('<li><span id="legend-' + i
							+ '-item" style="background-color:'
							+ chart.data.datasets[0].backgroundColor[i]
							+ '"   onclick="updateDataset(event, ' + '\'' + i
							+ '\'' + ')">');
					if (chart.data.labels[i]) {
						text.push(chart.data.labels[i]);
					}
					text.push('</span></li>');
				}
				text.push('</ul>');
				return text.join("");
			},
			title : {
				display : true,
				text : data.title,
		        fontFamily: graph.fontFamily,
		        fontSize: graph.fontSize // No need to explicitly force an integer here
			},
			animation : {
				animateScale : true
			},
			tooltips : {
				axis: 'y',
				intersect : false,
				callbacks : {
					label : function(tooltipItem, data) {
						let label = data.datasets[tooltipItem.datasetIndex].label
								+ ' - ' + data.labels[tooltipItem.index];
						let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
						return label + ': ' + datasetLabel.toLocaleString();
					}
				}
			},
		    plugins: {
		        // Change options for ALL labels of THIS CHART
		        datalabels: {
		          color: "#fff",
		          anchor: "center",
		          display: function(context) {
		            var index = context.dataIndex;
		            var value = context.dataset.data[index];
		            return value > 0; // display labels with a value greater than 0
		          }
		       }
		    }
		}

	};
}

