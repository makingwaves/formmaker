/*
 Name: Form Maker
 Information: Using Bootstrap v3.2.0 and jQuery UI
 Version: 1.0
*/

// Expand panels
var expanPanel = function() {
	// add class 'closed'
	$('.expandable-item, .expandable-section-panel').addClass('closed');
	// open / close section panel
	$('.js-expand-section-panel').click(function() {
		$(this).closest('.page-header').next('.expandable-section-panel').slideToggle(200, function(){
			$(this).toggleClass('closed');
		});
	});
	// open / close item
	$('.js-expand-panel').click(function() {
		var item = $(this).closest('.expandable-item');
		item.find('.panel-body').slideToggle(200, function() {
			item.toggleClass('closed');
		});
	});
	// open / close all items
	$('.js-expand-all-panels').click(function() {
		if ($('.expandable-item.closed').length > 0) {
			$('.expandable-item.closed .panel-body').slideDown(200, function() {
				$('.expandable-item.closed').removeClass('closed');
			});
		}
		else {
			$('.expandable-item:not(.closed) .panel-body').slideUp(200, function() {
				$('.expandable-item:not(.closed)').addClass('closed');
			});
		}
	});
};

// Watch panels label
var watchingLabel = function() {
	var formTimer = 0,
		currentField,
		panelTitle,
		lastValue;
	
	// listen events
	$('input.panel-label').focus(startWatching).blur(stopWatching).keypress(updateCurrentField);

	function startWatching() {
		stopWatching();
		currentField = this;
		panelTitle = $(this).closest('.attributes-item').find('.panel-title-label');
		lastValue = undefined;
		formTimer = setInterval(updateCurrentField, 100);
	}

	function stopWatching() {
		if (formTimer != 0) {
			clearInterval(formTimer);
			formTimer = 0;
		}
		currentField = undefined;
		panelTitle = undefined;
		lastValue = undefined;
	}

	function updateCurrentField() {
		var thisValue;
		
		if (currentField) {
			thisValue = currentField.value || "";
			
			if (thisValue != lastValue) {
				lastValue = thisValue;
				panelTitle.html(thisValue);
			}
		}
	}	
}

// Show panel label in the top bar
var panelLabel = function() {
	$('.panel-title').append("<span class='panel-title-label'></span>");
	// show existing label
	$('.panel-label').each(function() {
		if ( $(this).val() ) {
			$(this).closest('.attributes-item').find('.panel-title-label').html($(this).val());
		}
	});
	// update label
	watchingLabel();
}

// Remove panel
var removePanel = function(clicked) {
	$(clicked).closest('.sortable-item').remove();
}
// Add option
var addOption = function(clicked) {
	var parentList = $(clicked).parent().find('.option-list');
	var optionTemplate = "<li class='option-item'><div class='input-group'><span class='input-group-addon'><span class='glyphicon glyphicon-move'></span></span><input id=''' type='text' class='form-control'><span class='input-group-addon'><input type='checkbox'> Default</span></div><button type='button' class='close remove-option js-remove-option' onclick='removeOption(this)'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button></li>";
	
	parentList.append(optionTemplate);
	
	// confirm
	parentList.find('li:last-child').find('.js-remove-option').popConfirm({
		title: "Are you sure?",
		placement: "top"
	});
}
// Remove option
var removeOption = function(clicked) {
	$(clicked).closest('.option-item').remove();
}

$(document).ready(function() {
	// jquery UI sorting list
	$(".js-sortable").sortable({ tolerance: "pointer" });
	
	// Confirm
	$('.js-close-panel,.js-remove-option').popConfirm({
		title: "Are you sure?",
		placement: "top"
	});
	$('.js-remove-form').popConfirm({
		title: formmakerTranslations['removeFormConfirmation'],
		placement: "top"
	});
	
	// Expand panels
	expanPanel();
	
	// Show panel label in the top bar
	panelLabel();
	
	// Sorting tables
	$(".js-form-list-table").tablesorter({
        sortList: [[1,1]],
        headers: { 
            3: { 
                sorter: false 
            }
        } 
    });

    // process a form_id as a view parameter
    $('#formmaker-set-answers-form').click(function(){
        var form = $(this).parent('form');
        var addr = form.attr( 'action');
        var form_id = form.find('option:selected').val();

        if (form_id > 0) {
            addr += '/(form_id)/' + form_id;
        }

        window.location.href = addr;
    });
});