/* ------------------------------------------------------------------------------
*
*  # Buttons extension for Datatables. HTML5 examples
*
*  Specific JS code additions for datatable_extension_buttons_html5.html page
*
*  Version: 1.1
*  Latest update: Mar 6, 2016
*
* ---------------------------------------------------------------------------- */
var table;
var filters = {type : 'ALL', area_id_fk : '', category_id_fk : ''};
var columns;
$(function() {
	initSelectors();
	initYear();
	initArea();
	initType();
	initReportComplaintStatus();
	eventHandler();
	initFormValidation(); 
});

function init(level){
/**
 * resource_level carry the category level to be filterd.
 */
	var init_selectors = [
		{
			class: '.select-category-search',
			url: '/dashboard/categories/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#category_id_fk',
			modal_name: "",
			inputLength: 0,
			resource_level: level,
			noResults_btn: false,
			autofocus: false,
			disabled: false
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
}

function eventHandler(){
	$(".detail-table").parents('td').css("text-align", "inherit");
	
//	$('#analysis-form').on('submit', function(e){
//		e.preventDefault();
//		initAnalysis();
//	});
	
	var selects = ['#analysis-type', '#analysis-year','#analysis-status', '#area_id_fk', '#category_id_fk', '#select-type'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			if(element == '#analysis-type'){
				$('.display-area, .display-category').hide();
				clearSelect2('.select-area-search');clearSelect2('.select-category-search');
				filters.area_id_fk = filters.category_id_fk = '';
				filters.code = $(e.currentTarget).find("option:selected").val();
				if(filters.code == 'DAWA'){
					$('.display-area').slideDown();
				}else if(filters.code == 'DCWA'){
					init(1);
					$('.display-category').slideDown();
				}else if(filters.code == 'DSCWA'){		
					init(2);
					$('.display-category').slideDown();
				}else if(filters.code == 'IDSCWA'){		
					init(3);
					$('.display-category').slideDown();
				}
				initHeaders();
			}
			if(element == '#analysis-year'){
				filters.year = $(e.currentTarget).find("option:selected").val();
				initHeaders();
			}
			if(element == '#analysis-status'){
				filters.status = $(e.currentTarget).find("option:selected").val();
				initHeaders();
			}
			if(element == '#area_id_fk'){
				filters.area_id_fk = $(e.currentTarget).find("option:selected").val();
				initHeaders();
			}
			if(element == '#category_id_fk'){
				filters.category_id_fk = $(e.currentTarget).find("option:selected").val();
				initHeaders();
			}
			if(element == '#select-type'){
				filters.type = $(e.currentTarget).find("option:selected").val();
				initHeaders();
			}
		});
		$(element).on('select2:unselect', function (e) {
			emptyChartView();
			emptyView();
			if(element == '#analysis-type'){
				$('.display-area').slideUp( "slow", function() {
					removeErrorPlacement('.select-area-search');
					clearSelect2('.select-area-search');
					filters.area_id_fk = '';
				});
				$('.display-category').slideUp( "slow", function() {
					removeErrorPlacement('.select-category-search');
					clearSelect2('.select-category-search');
					filters.category_id_fk = '';
				});
			}
			if(element == '#area_id_fk'){
				filters.area_id_fk = '';
			}
			if(element == '#category_id_fk'){
				filters.category_id_fk = '';
			}			
			destroyTable('dashboard/analysis');
		});
		if ($.inArray(element, ['#analysis-year','#analysis-status','#area_id_fk','#category_id_fk', '#select-type']) !== -1) {
			$(element).trigger({
				type: 'select2:select'
			});
		}
	});
	
	$('#analysis-form-submit').on('click', function(e){
		e.preventDefault();
		initAnalysis();
	});
}

function initAnalysis(){
	destroyTable('dashboard/analysis');
	if($("#analysis-form").valid()){
		initTable();
	}
}

function emptyView(){
	$('.displayTable').empty();
}

function emptyChartView(){
	$('#chart-area, #do_legend').empty();
	if(typeof window.myPie !== 'undefined')
		window.myPie.destroy();
}

function initFormValidation(){
	$("#analysis-form").validate({
		rules: {
			'analysis_type': {
				required: true
			},
			'area_id_fk': {
				required: true
			},
			'category_id_fk': {
				required: true
			}
		},
		messages: {
			'analysis_type': {
				required: "The analysis is required"
			},
			'area_id_fk': {
				required: "The area is required"
			},
			'category_id_fk': {
				required: "The category is required"
			}
		},
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
			if($(label).is('select')){
				$(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-success').addClass('has-select-error');
			}
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			if($(label).is('select')){
				$(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-select-error');
			}
			
			label.remove();
		},
		errorPlacement: function( error, element ) {
			if(element.hasClass('select2-hidden-accessible')){
				error.appendTo( element.parent() );
			}
			else {
				var placement = element.closest('.input-group');
				if (!placement.get(0)) {
					placement = element;
				}
				if (error.text() !== '') {
					placement.after(error);
				}
			}
		},
		submitHandler: function(form) {
		}
	});
}

function initHeaders(){
	$.ajax({
		url: custom_url + '/dashboard/analysis/table_headers/'+encodeURIComponent($.param({year: filters.year, code: filters.code})),
		type: 'GET',
		processData: false,
		contentType: false,
		dataType: 'JSON',
		beforeSend:function(){
			emptyView();
			emptyChartView();
		},
		success: function (data) {
			if(data.status=="success" && data.component)
				$('.displayTable').empty().html(data.component).hide();
			columns = data.columns;
		},
		error: function (data) {
		},
		complete:function(){
		}
	});
}

function initTable() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_analysis_table');
	
	var handleDataTableButtons = function() {
		table = '';
		table = $(".analysis-table").DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  "bFilter":false,
		  "bLengthChange" : false,
		  "paging" : false,
		  "ordering": false,
//	      "serverSide": true,
		  dom: "Blfrtip",
		  buttons: [
		  ],
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/analysis/get/table",
			  type: "POST",
			  dataFilter: function (res) {
				  if($.inArray(filters.code, ['CRTTM','CRTCCHL','RA','TFCR']) == -1){
					  initChart();
				  }
				  $('.displayTable').show();
				  return res;
			  },
			  data: function (d) {
				  d.code = filters.code,
				  d.year = filters.year,
				  d.status = filters.status,
				  d.area_id_fk = filters.area_id_fk,
				  d.category_id_fk = filters.category_id_fk,
				  d.type = filters.type
			  }
	       },
	        columns: columns,      
	        rowGroup: {
	        	dataSrc: (filters.code == 'AWODA' ? 'Area' : (filters.code == 'BSLZR' ? 'Zone' : '')),
	             startRender: function ( rows, group ) {
	            	 if(filters.code == 'AWODA' || filters.code == 'BSLZR')
	            		 return group +' ('+rows.count()+' rows)';
	             }
	        },
//	        rowsGroup: [// Always the array (!) of the column-selectors in specified order to which rows groupping is applied
//	                    // (column-selector could be any of specified in https://datatables.net/reference/type/column-selector)
////	            'second:name',
//	            0,
//	            3,
//	            4
//	        ],
	        createdRow: function (nRow, aData, iDataIndex) {
				if(filters.code == 'RA' && iDataIndex == 4)
					$(nRow).addClass('total');
	        },
	        drawCallback: function ( settings ) {
				if($.inArray(filters.code, ['RA','TFCR']) == -1){
				}else{
		            var api = this.api();
		            var rows = api.rows( {page:'current'} ).nodes();
		            var last=null;
		            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
						if(filters.code == 'TFCR'){
			                if (i == 3 ) {
			                    $(rows).eq( i ).before(
			                        '<tr class="group"><td colspan="11">'+group+'</td></tr>'
			                    );
			                    $(rows).eq( i ).remove();
			           		}
						}else{
							if(i == 5 || i == 6 || i == 7){
			                    $(rows).eq( i ).before(
			                        '<tr class=""><td colspan="10">'+group+'</td><td>'+$(rows).eq(i).find("td:last").text()+'</td></tr>'
			                    );
			                    $(rows).eq( i ).remove();
			           		}
						}
					})
				}
			}
		});
//		table.rowsgroup.update();
//		table.draw(false);
//		if(filters.code == 'AWODA'){
//			table.column( 0 ).visible( false );
//			table.columns.adjust().draw( false );
//		}
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

function initChart(){
	console.log('init_analysis_chart');
	
	$.ajax({
		url: custom_url + '/dashboard/analysis/get/chart',
		type: 'POST',
		data: filters,
		dataType: 'JSON',
		beforeSend:function(){
			emptyChartView();
		}
	}).done(function(data, textStatus, jqXHR){
		drawChart(data);
	}).fail(function(jqXHR, textStatus, errorThrown){
		
	}).always(function(){
		
	});
}

Array.prototype.random = function () {
	  return this[Math.floor((Math.random()*this.length))];
}

function generateRandomColors(length){
	var colors = [];
	for(var i=0; i<length;i++){
	    colors.push('#' + (Math.random().toString(16) + '0000000').slice(2, 8)); 
	}
	return colors;
	
//	var colorsArr = ['#1ABC9C','#2ECC71','#3498DB','#9B59B6','#34495E','#F1C40F','#E67E22','#E74C3C','#6D6FA7','#95A5A6','#16A085','#27AE60','#2980B9','#8E44AD',
//		'#2C3E50','#F39C12','#D35400','#C0392B','#BDC3C7','#7F8C8D'];
//	colors = [];
//	for(var i=0; i<length;i++){
//		colors.push(colorsArr.random()); 
//	}
//	return colors;
}

function drawChart(data){
	if(typeof data.chartDataSet.data === 'undefined') return;
    var config = {
            type: 'pie',
            data: {
            	datasets: [{
            		label: '',
            		data: data.chartDataSet.data,
            		backgroundColor: data.chartColors.length !== 0 ? data.chartColors : generateRandomColors(data.chartDataSet.data.length)
            	}],
            	labels: data.chartLabels
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                },
	            legendCallback: function (chart) {             
	                // Return the HTML string here.
	                var text = [];
	                text.push('<ul class="' + chart.id + '-legend">');
//	                for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
	                for (var i = 0; i < chart.data.labels.length; i++) {
	                    text.push('<li><span id="legend-' + i + '-item" style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '"   onclick="updateDataset(event, ' + '\'' + i + '\'' + ')">');
	                    if (chart.data.labels[i]) {
	                        text.push(chart.data.labels[i]);
	                    }
	                    text.push('</span></li>');
	                }
	                text.push('</ul>');
	                return text.join("");
	            },
	            title: {
	                display: false,
	                text: 'Analysis Chart View'
	            },
	            animation: {
	                animateScale: true,
	                animateRotate: true,
	                onComplete: function(animation){
	                    var firstSet = animation.chartInstance.chart.config.data.datasets[0].data,
	                    dataSum = firstSet.reduce((accumulator, currentValue) => accumulator + currentValue);
	                    if(typeof firstSet !== "object" || dataSum === 0){
	                    }else{
	                    	$("#do_legend").html(window.myPie.generateLegend());
	                    }
	                }
	            },
//	            tooltips: {
//	                intersect: false,
//	                callbacks: {
//	                    label: function (tooltipItem, data) {
//	                        let label = data.datasets[tooltipItem.datasetIndex].label + ' - ' + data.labels[tooltipItem.index];
//	                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
//	                        return label + ': ' + datasetLabel.toLocaleString();
//	                    }
//	                }
//	            }	
            },
        };

        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myPie = new Chart(ctx, config);
}

updateDataset = function (e, datasetIndex) {    
    var index = datasetIndex;
    var ci = e.view.myPie;
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

window.exportAnalysis = function(mode){
	if($("#analysis-form").valid()){
		window.actionExport({filters: filters, mode:mode, flow:'dashboard/analysis'});
	}
}
