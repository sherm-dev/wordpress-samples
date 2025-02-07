(function($){
	$(function(){
		var questionIds = [];
		var groupId = -1;
		var subGroupId = -1;
		var optionId = -1;
		var scrollOffset = {
			overall_top: 0,
			group_top: 0,
			subgroup_top: 0
		};
		
		function retrieveQuestionsList(){
			if( wp.ajax.settings.url !== undefined){
				var params = {
					action: "retrieve_question_ids"
				};

				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
						console.log("Question IDS");
						questionIds = JSON.parse(data);
						console.log(questionIds);
				});
			}
		}
		
		function retrievePieChartData(){
			if(wp.ajax.settings.url !== undefined){
				var params = {
					action: "pie_chart_data_action",
					question_id: questionIds[$('.question-item').index($('.question-item.active'))],
					option_id: optionId,
					group_id: groupId,
					sub_group_id: subGroupId
				};
				
				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
					console.log(data);
					renderPieChart(JSON.parse(data));
				});
			}
		}
		
		function retrieveBarChartData(barChartId){
			if(wp.ajax.settings.url !== undefined){
				var params = {
					action: "bar_chart_data_action",
					question_id: questionIds[$('.question-item').index($('.question-item.active'))],
					option_id: optionId,
					group_id: groupId,
					sub_group_id: subGroupId
				};
				
				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
					renderBarChart(barChartId, data);
				});
			}
		}
		
		function findOptionIndex(data, optionText){
			if(data.length > 0){
				for(var i = 0; i < data.length; i++){
					if(data[i].label.indexOf(optionText.trim()) !== -1)
						return i;
				}
			}
			
			return -1;
		}
		
		function shadePieChart(parent, index){
			parent.find('.arc > path').eq(index).addClass('result-fade').css({fill: $('.question-item.active .btn-option.btn-active').css("backgroundColor")});
		}
		
		function shadeBarChart(parent, index){
			parent.find('.bar').eq(index).addClass('result-fade').css({fill: $('.question-item.active .btn-option.btn-active').css("backgroundColor")});
		}
		
		function shadeResults(parent){
			$('.result-fade').css({fill: $('.question-item.active .btn-option.btn-active').css("backgroundColor")});
			//shadePieChart(parent, findOptionIndex(parent.find('.sub_group .chart-data').data('results'), $('.question-item.active').find('.btn-option.btn-active').text()));
			shadeBarChart(parent, findOptionIndex(parent.find('.sub_group .chart-data').data('results'), $('.question-item.active').find('.btn-option.btn-active').text()));
		}
		
		function removeShade(){
			if($('.result-fade').length > 0)
				$('.result-fade').each(function(index, element){
					$(element).removeClass('result-fade').css({fill:'#f1ae1d'});
				});
		}
		
		function toggleGroups(groupId, type){
			$('.' + type).each(function(index, element){
				if($(element).hasClass(type + '-' + groupId) && $(element).hasClass('hidden')){
					$(element).removeClass('hidden');
				}else{
					$(element).addClass('hidden');
				}
			});
		}
		
		function hideGroups(){
			$('.question-item.active').find('.overall-results').addClass('hidden');
		}
		
		
		function loadGroup(){
			if(groupId !== -1 && wp.ajax.settings.url !== undefined){
				var params = {
					action: "load_group_action",
					question_id: questionIds[$('.question-item').index($('.question-item.active'))],
					option_id: optionId,
					group_id: groupId
				};
				
				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
						$('.question-item.active').find('.filter-results').find('.container-fluid').empty().append(data);
						//hideGroups();
						//toggleGroups(groupId, "group");
						setFilterButtons();
						goToAnchor('group_top');
				});
			}
		}
		
		
		
		function loadSubGroup(){
			if(subGroupId !== -1 && wp.ajax.settings.url !== undefined){
				var params = {
					action: "load_sub_group_action",
					question_id: questionIds[$('.question-item').index($('.question-item.active'))],
					option_id: optionId,
					group_id: groupId,
					sub_group_id: subGroupId
				};
				
				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
						$('.question-item.active').find('.sub-groups').find('.col').empty().append(data);
						
						/*$('.sub-groups').find('.graph-background script').on('load', function(){
							redraw();
						});*/
						//hideGroups();
						//toggleGroups(subGroupId, "sub_group");
						setFilterButtons();	
						addToolTips($('.question-item.active').find('.sub-groups'));
						setChartListeners($('.question-item.active').find('.sub-groups'));
						shadeResults($('.question-item.active').find('.sub-groups'));
					
						if($(window).width() < 1000)
							goToAnchor('subgroup_top');
				});
			}
		}
		
		function setFilterButtons(){
			$('.question-item.active .filter-button').off('click').click(function(){
				console.log("GROUP TYPE: " + $(this).data('groupType'));
				$(this).parent().find('.btn-active').toggleClass('btn-active');
				$(this).toggleClass('btn-active');
	
				if($(this).data('groupType').toLowerCase().indexOf("sub") !== -1){
					subGroupId = $(this).data('groupId');
					$('.question-item.active').find('.sub-groups').find('.col').empty();
					setSpinner($('.question-item.active').find('.sub-groups').find('.col'));
					loadSubGroup();
				}else{
					groupId = $(this).data('groupId');
					setSpinner($('.question-item.active').find('.filter-results').find('.container-fluid'));
					loadGroup();
				}
			});
		}
		
		function loadOverallView(){
			if(wp.ajax.settings.url !== undefined){
				var params = {
					action: "load_sub_group_action",
					question_id: questionIds[$('.question-item').index($('.question-item.active'))],
					option_id: optionId,
					group_id: groupId,
					sub_group_id: subGroupId
				};
				
				$.post("https://" + window.location.hostname + wp.ajax.settings.url, params, function(data, textStatus, jqxhr){
					$('.question-item.active').find('.results-container').empty().append(data);
					addToolTips($('.question-item.active').find('.results-container'));
					setChartListeners($('.question-item.active').find('.results-container'));
					shadeResults($('.question-item.active').find('.results-container'));
					goToAnchor('overall_top');
				});
			}
		}
		
		function setQuestionEventListeners(){
			$('.question-item.active .btn-option').off('click').click(function(){
				$(this).parent().find('.btn-active').toggleClass('btn-active');
				$(this).toggleClass('btn-active');
				
				if($('.filter-button.btn-active').length > 0)
					$('.filter-button.btn-active').toggleClass('btn-active');
				
				if($('.question-item.active').find('.filter-results').find('.container-fluid').children().length)
					$('.question-item.active').find('.filter-results').find('.container-fluid').empty(); //clear filters if fetched
				$(this).parents('.question-item').find('.overall-results').removeClass('hidden');
				//shadeResults($('.btn-option').index($(this)));
				optionId = $(this).data('optionId');
				subGroupId = '';
				groupId = '';
				
				setSpinner($('.question-item.active').find('.results-container'));
				removeShade();
				loadOverallView();
				
				if($('.question-item.active').find('.sub-groups').find('.col').children().length)
					shadeResults($('.question-item.active').find('.sub-groups'));
			});

			setFilterButtons();
		}
		
		function setSpinner(parent){
			parent.parent().find('.chart-placeholder').append(
				$(document.createElement('DIV')).addClass('text-center').append(
					$(document.createElement('DIV')).addClass('spinner-border').addClass('text-dark').attr("role", "status").append(
						$(document.createElement('SPAN')).addClass('sr-only').text("Loading...")
					)
				)
			);
		}
		
		function setScroll(anchor){
			switch(anchor){
				case 'overall_top':
					if(scrollOffset.overall_top === 0)
						scrollOffset.overall_top = parseInt($('a[name="' + anchor + '"]').offset().top);
					break;
				case 'group_top':
					if(scrollOffset.group_top === 0)
						scrollOffset.group_top = parseInt($('a[name="' + anchor + '"]').offset().top);
					break;
				case 'subgroup_top':
					if(scrollOffset.subgroup_top === 0)
						scrollOffset.subgroup_top = parseInt($('a[name="' + anchor + '"]').offset().top);
					break;
				default:
					break;
			}
			
			console.log(scrollOffset);
			console.log(anchor + ": " + scrollOffset[anchor]);
			return scrollOffset[anchor];
		}
		
		function goToAnchor(anchor){
			if(anchor === "question_top"){
				window.location.hash = "";
				window.location.hash = "#" + anchor;
				$(window).scrollTop($('a[name="question_top"]').scrollTop());
			}else{
				setTimeout(function(){
					window.location.hash = "";
					window.location.hash = "#" + anchor;
					$(window).scrollTop(setScroll(anchor));
				}, 750);
			}
		}
		
		function addToolTips(parent){
			var results = parent.find('.chart-data').data('results');
			
			if(results.length > 0){
				for(var i = 0; i < results.length; i++){
					//parent.find('.pie-chart').find('.arc').eq(i).attr({'data-toggle': 'tooltip', tabindex: 0, 'data-html': true, 'data-title': results[i].label}).tooltip();
					parent.find('.bar-graph').find('.bar').eq(i).attr({'data-toggle': 'tooltip', tabindex: 0, 'data-html': true, 'data-title': results[i].label}).tooltip();
				}
			}
		}
		
		function setChartListeners(parent){
			
			/*parent.find('.pie-chart .arc').off('mouseover').mouseover(function(){
				console.log(this);
				$(this).tooltip('show');
			}).mouseout(function(){
				$(this).tooltip('hide');
			});
			*/
			parent.find('.bar-graph .bar').off('mouseover').mouseover(function(){
				console.log(this);
				$(this).tooltip('show');
			}).mouseout(function(){
				$(this).tooltip('hide');
			});
			
			$('[data-toggle="tooltip"]').tooltip();
		}

		/*function changeActiveIndicator(){
			$('#carousel_section .carousel-indicators li.active').removeClass('active');
			$('#carousel_section .carousel-indicators li').eq($('.question-item').index($('.question-item.active'))).addClass('active');
		}*/
		
		$(window).resize(function(){
			scrollOffset = {
				overall_top: 0,
				group_top: 0,
				subgroup_top: 0
			}; //set to zero on resize
			
			goToAnchor('question_top');
			
			setTimeout(function(){
				shadeResults($('.question-item.active').find('.results-container'));
			
				if($('.question-item.active').find('.sub-groups').find('.col').children().length)
					shadeResults($('.question-item.active').find('.sub-groups'));
				}, 500);
		});
		
		$('#sojla_carousel').carousel({interval: false});
		$('#sojla_carousel').on('slide.bs.carousel', function(){
			goToAnchor('question_top');
		}).on('slid.bs.carousel', function(){
			hideGroups();
			setQuestionEventListeners();
		});
		
		
		
		hideGroups();
		setQuestionEventListeners();
		retrieveQuestionsList();
	});
})(jQuery);