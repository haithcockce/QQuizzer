var booths = Array();
var questions = Array();
var boothName;
var organizer;
    

$(document).ready(function() {
    $('.ModificationContainer').hide();
    $('#logout-button').on('click', logoutButtonHandler);
    $('.question-dropdown, #edit-booth-container .booth-name-input, #edit-booth-container .booth-organizer-input').prop('disabled', true);
    $.post('MasterEditorBackend.php', {'requesting': 'get all booths'}, function(response) {
        JSONToBoothsArray(response);
        insertBoothNames();
    });
    
    
});

/*
    Handles the animation of hiding and showing sections of the form.
    Fade out the currently active sections and
    move the 'active' class to the selected sections
    Then fade in.
*/
function buttonHandler(container) {
    //check to see if this class is already active
    if($(container).hasClass('active')) return;

    $('.active').fadeOut('fast');
    $('.active [class*="error"]').remove();
    //$('.active input').val('');
    //$('.active .question-dropdown, .active .booth-dropdown').val('null');
    $('.active').removeClass('active');
    $(container).addClass('active');
    $('.active').fadeIn('slow');
}

function addQuestion(container) {
    //check for a selected booth
    var $boothChoice = $(container + ' option:selected').val();
    if ($boothChoice === 'null') {
        //alert if there was no booth selected
        if (!($('[class="error 1"]').length > 0)) {
            $(container + ' #booth-selection-container')
                .append('<small class="error 1">Required</small>');
        }
    }
    //remove the alert if a booth is selected
    else {
		if ($('[class="error 1"]').length > 0) 
			$(container + ' [class="error 1"]').remove();
	}
    
    //check if an interrogative clause is provided
    var $questionClause = $(container + ' #question-clause-input').val();
    if (!($questionClause)) {
        //alert if there was no clause is provided
        if (!($('[class="error 2"]').length > 0)) {
            $(container + ' #question-clause-input-container')
                .append('<small class="error 2">Required</small>');
        }
    }
    //remove the alert if the clause is provided
    else {
		if ($('[class="error 2"]').length > 0) 
			$(container + ' [class="error 2"]').remove();
	}
    
    //make an array of provided answers
    var $answers = Array();
    for (var i =0; i < $(container + ' .answer-choice').length; i++) {
        if ($(container + ' .answer-choice').eq(i).val() != '')
            $answers.push($(container + ' .answer-choice').eq(i).val());
    }
    
    //alert if not enough answers were provided and if an 
    //answer wasn't selected
    var $id = $(container + ' .correct-answer-indicator:checked').attr('id').substring(2);
    if ($(container + ' .answer-choice').eq($id).val() == '' && $answers.length < 2) {
        alert('Please provide more answers and select the correct answer!');
        return;
    }
    
    //alert only if there are not enough answers
    if ($answers.length < 2) {
        alert('Need more answers!');
        return;
    }
    
    //alert only if an answer wasn't chosen to be correct
    if ($(container + ' .answer-choice').eq($id).val() == '') {
        alert('Please select the correct answer!');
        return;
    }
        
    //Check if no errors ocurred
    if ($(container + ' .error').length == 0) {
        
        //reaching here means we've passed all tests! Woo!
        $formData = {
            'requesting': 'add a question',
            'booth': $boothChoice,
            'question': $questionClause,
            'answers': $answers,
            'correct': $id
        };
        $.post('MasterEditorBackend.php', $formData, function(response) {
            if (response === 'success') {
                alert('Woo! Successfully added!');
                $(container + ' input').val('');
                $(container + ' select').val('null');
                $('#ca0').prop('checked', true);
                for (var i = $(container + ' .answers-container .row').length - 1; i > 1; i--) {
                    $(container + ' .answers-container .row').eq(i).remove();
                }
            }
            else if (response === 'found question') 
                alert('That question already exists.\nPlease enter a different one.');
            else 
                alert('Please try again. Something went wrong.' + response);
        }); 
    }
}

function editQuestion(container) {
    var booth = $(container + ' .booth-dropdown option:selected').val();
    var oldQuestion = $(container + ' .question-dropdown option:selected').val();
    var newQuestion = $(container + ' #question-input').val();
    var answers = Array();
    for (var i = 0; i < $(container + ' .answer-choice').length; i++)
        answers[i] = $(container + ' .answer-choice').eq(i).val();
    var correctAnswer = -1;
    for (var i = 0; i < $(container + ' .correct-answer-indicator').length; i++) {
        if ($(container + ' .correct-answer-indicator').eq(i).prop('checked') == true) {
            correctAnswer = i;
            break;
        }
    }

    if (booth == 'null') {
        if ($(container + ' [class="error 1"]').length == 0) {
            $(container + ' .columns')
                .eq(0)
                .append('<small class="error 1">Required</small>');
        }
    }
    else {
        if ($(container + ' [class="error 1"]').length != 0) {
            $(container + ' [class="error 1"]').remove();
        }
    }
    if (oldQuestion == 'null') {
        if ($(container + ' [class="error 2"]').length == 0) {
            $(container + ' .columns')
                .eq(1)
                .append('<small class="error 2">Required</small>');
        }
    }
    else {
        if ($(container + ' [class="error 2"]').length != 0) {
            $(container + ' [class="error 2"]').remove();
        }
    }
    if (newQuestion === '' || newQuestion === 'Please select a booth and question to edit') {
        if ($(container + ' [class="error 3"]').length == 0) {
            $(container + ' .columns')
                .eq(2)
                .append('<small class="error 3">Required</small>');
        }
    }
    else {
        if ($(container + ' [class="error 3"]').length != 0) {
            $(container + ' [class="error 3"]').remove();
        }
    }
    var count = 0;
    for (var i = 0; i < answers.length; i++)
        if (answers[i] !== '')
            count++;
    if (count < 2 && correctAnswer == -1) {
        alert('More answers choice are needed and an answer must be selected as correct!');
        return;
    }
    else if (count < 2) {
        alert('More answers choice are needed!');
        return;
    }
    else if (correctAnswer == -1) {
        alert('Please select an answer choice to be the correct answer!');
        return;
    }

    if ($(container + ' .error').length == 0) {
        var formData = {
            'requesting': 'edit a question',
            'oldQuestion': oldQuestion,
            'newQuestion': newQuestion,
            'answers': answers,
            'correctAnswer': correctAnswer
        };

        $.post('MasterEditorBackend.php', formData, function(response) {
            var parsedJSON = $.parseJSON(response);
            if (parsedJSON[0] === true && parsedJSON[1] === true)
                alert('Successfully altered this question!');
            else 
                alert('That question already exists!');
        });
    }
    
}

/**
 * removeQuestion
 * 
 * Allows removal of a question. The person first selects a booth while the 
 * other fields are disabled. The 
 */
function removeQuestion(container) {
    var fromBooth = $(container + ' .booth-dropdown option:selected').val();
    if (fromBooth === 'null') {
        if ($(container + ' [class="error 1"]').length == 0) {
            $(container + ' .columns')
                .eq(0)
                .append('<small class="error 1">Required</small>');
        }
    }
    else {
        if ($(container + ' [class="error 1"]').length != 0) {
            $(container + ' [class="error 1"]').remove();
        }
    }

    var question = $(container + ' .question-dropdown option:selected').val();
    if (question === 'null') {
        if ($(container + ' [class="error 2"]').length == 0) {
            $(container + ' .columns')
                .eq(1)
                .append('<small class="error 2">Required</small>');
        }
    }
    else {
        if ($(container + ' [class="error 2"]').length != 0) {
            $(container + ' [class="error 2"]').remove();
        }
    }

    var formData = {
        'requesting': 'remove a question',
        'question': question
    }

    if (question !== 'null') {
        $.post('MasterEditorBackend.php', formData, function(response) {
            alert('Response: ' + response);
            $(container + ' .question-dropdown [value="'+ question +'"]').remove();
            if ($(container + ' .question-dropdown').length == 1) {
                $(container + ' .question-dropdown')
                    .html('<option value="null">There are no more questions associated with this booth</option>')
                    .prop('disabled', true);
            }

        });
    }

}

/**
 * moveQuestion
 *
 * 
 */
function moveQuestion(container) {
    var fromBooth = $('#from-booth option:selected').val();
    if (fromBooth === 'null'){
        if (!($('[class="error 1"]').length > 0)) {
			//create error message
			$('#from-booth-container')
				.append('<small class="error 1">Required</small>');
		}
	}
    //remove the error message if it exists and there is a value
	else {
		if ($('[class="error 1"]').length > 0) 
			$(container + ' [class="error 1"]').remove();
	}
	
	var question = $('#question-to-move option:selected').val();
	if (question === 'null') {
	    if (!($('[class="error 2"]').length > 0)) {
			//create error message
			$('#question-to-move-container')
				.append('<small class="error 2">Required</small>');
		}
	}
	else {
	    if ($('[class="error 2"]').length > 0) 
			$(container + ' [class="error 2"]').remove();
	}
	
	var toBooth = $('#to-booth option:selected').val();
    if (toBooth === 'null'){
        if (!($('[class="error 3"]').length > 0)) {
			//create error message
			$('#to-booth-container')
				.append('<small class="error 3">Required</small>');
		}
	}
    //remove the error message if it exists and there is a value
	else {
		if ($('[class="error 3"]').length > 0) 
			$(container + ' [class="error 3"]').remove();
	}

    if (fromBooth === toBooth && fromBooth !== 'null') {
        if (!($('[class="error 4"]').length > 0)) {
            //create error message
        $('#to-booth-container')
            .append('<small class="error 4">The booth to move to can not be the same as the booth to move from</small>');
        }
    }

    else {
        if ($('[class="error 4"]').length > 0) 
            $(container + ' [class="error 4"]').remove();
    } 
	
	if (fromBooth !== 'null' && toBooth !== 'null' && question !== 'null' && fromBooth !== toBooth) {

        var formData = {
            'requesting': 'move a question',
            'question': question,
            'toBooth': toBooth
        }
        $.post('MasterEditorBackend.php', formData, function(response) {
            alert('Successfully moved.')
            $(container + ' select').val('null');
            $(container + ' .question-dropdown').prop('disabled', true);
        });
	}
}



/*
 * addBooth
 * container
 *      the DOM object referring to a subsection of the form
 *
 * This function checks that all input fields are filled, inserting error
 * messages when one or both input fields are blank. When all input fields 
 * are filled, it checks the inputs against the BoothNames table with the
 * MasterEditorBackend script. It alerts if either or both fields were found
 * in the database OR if the inputs were successfully inserted. 
 */
function addBooth(container) {
    $boothName = $(container + ' #booth-name').val();
    $organizer = $(container + ' #booth-organizer').val();
    
	//Check for a value in the first input box
	if (!($boothName)) {
		//Check if an error message is already displayed
		if (!($('[class="error 1"]').length > 0)) {
			//create error message
			$(container + ' #booth-name-input-container')
				.append('<small class="error 1">Required</small>');
		}
	}
    //remove the error message if it exists and there is a value
	else {
		if ($('[class="error 1"]').length > 0) 
			$(container + ' [class="error 1"]').remove();
	}


	//Check for a value in the second input box
	if (!($organizer)) {
		//Check if an error message is already displayed
		if (!($('[class="error 2"]').length > 0)) {
			//create error message if one doesn't exist
			$(container + ' #booth-organizer-input-container')
				.append('<small class="error 2">Required</small>');
		}
	}
	//remove the error message if it exists and there is a value
	else {
		if ($('[class="error 2"]').length > 0) 
			$(container + ' [class="error 2"]').remove();
	}

    //If there are no error messages
    if ($(container + ' .error').length == 0) {
        
        $('a, input, select').prop('disabled', true);
		
        $formData = {
            'requesting': 'add a booth',
            'name': $boothName,
            'organizer': $organizer
        };
        $.post('MasterEditorBackend.php', $formData, function(response) {
            if (response === 'success') {
                alert('Huzzah!\nSuccessfully added the booth ' + $boothName + ' with organizer ' + $organizer + '.');
                $(container + ' input').val("");
                booths.push($boothName);
                $('.booth-dropdown').append('<option value="' + $boothName + '">' + $boothName + '</option>')
            }
            else if (response === 'found both') 
                alert('It appears that booth already exists with that organizer.');
            else if(response === 'found booth') 
                alert('It appears that booth already exists.');
            else if(response === 'found organizer') 
                alert('It appears that person is already exhibiting.');
        });	
        $('a, input, select').prop('disabled', false);
    }
}

/**
 * editBooth
 *
 * Allows editing of a particular booth's information
 *
 * @param container
 *      the effected container
 */
function editBooth(container) {
    var oldBoothName = $(container + ' option:selected').val();
    if (oldBoothName === 'null') {
        //Check if an error message is already displayed
        if ($('[class="error 1"]').length == 0) {
            //create error message
            $(container + ' .columns')
                .eq(0)
                .append('<small class="error 1">Required</small>');
        }
    }
    //remove the error message if it exists and there is a value
    else {
        if ($(container + ' [class="error 1"]').length != 0) 
            $(container + ' [class="error 1"]').remove();
    }

    var newBoothName = $(container + ' .booth-name-input').val();
    if (newBoothName === '') {
        //Check if an error message is already displayed
        if ($('[class="error 2"]').length == 0) {
            //create error message
            $(container + ' .columns')
                .eq(1)
                .append('<small class="error 2">Required</small>');
        }
    }
    //remove the error message if it exists and there is a value
    else {
        if ($(container + ' [class="error 2"]').length != 0) 
            $(container + ' [class="error 2"]').remove();
    }

    var newOrganizer = $(container + ' .booth-organizer-input').val();
    if (newOrganizer === '') {
        //Check if an error message is already displayed
        if ($('[class="error 3"]').length == 0) {
            //create error message
            $(container + ' .columns')
                .eq(2)
                .append('<small class="error 3">Required</small>');
        }
    }
    //remove the error message if it exists and there is a value
    else {
        if ($(container + ' [class="error 3"]').length != 0) 
            $(container + ' [class="error 3"]').remove();
    }

    if (newBoothName === oldBoothName && newOrganizer === organizer) {
        alert('Nothing to do. Values did not change');
    }
    else if (newBoothName === oldBoothName && newOrganizer !== organizer) {
        var formData = {
            'requesting': 'edit a booth',
            'changing': 'organizer only',
            'booth': oldBoothName,
            'organizer': newOrganizer
        };
        $.post('MasterEditorBackend.php', formData, function(response) {
            if (response === 'success')
                alert('Successfully changed this booth\'s organizer!');
            
        });
    }
    else if (newBoothName !== oldBoothName && newOrganizer === organizer) {
        var formData = {
            'requesting': 'edit a booth',
            'changing': 'booth name only',
            'oldName': oldBoothName,
            'newName': newBoothName
        };
        $.post('MasterEditorBackend.php', formData, function(response) {
            if (response === 'success') {
                alert('Successfully changed the name of this booth!');
                booths.splice(booths.indexOf(oldBoothName), 1, newBoothName);
                $(container + ' .booth-dropdown option:selected').remove()
                $(container + ' .booth-dropdown')
                    .append('<option value="' + newBoothName + '">' + newBoothName + '</option>')
                    .val(newBoothName);
            }
        });
    }
    else {
        var formData = {
            'requesting': 'edit a booth',
            'changing': 'both',
            'oldName': oldBoothName,
            'newName': newBoothName,
            'organizer': newOrganizer
        };
        
        $.post('MasterEditorBackend.php', formData, function(response) {
            if (response === 'success') {
                alert('Successfully changed this booth\'s info!');
                booths.splice(booths.indexOf(oldBoothName), 1, newBoothName);
                $(container + ' .booth-dropdown option:selected').remove()
                $(container + ' .booth-dropdown')
                    .append('<option value="' + newBoothName + '">' + newBoothName + '</option>')
                    .val(newBoothName);
            }
        });
    }

}

/*
 * removeBooth
 *
 * @param container
 *      The DOM object referring to the remove booth subsection of the form.
 *
 * removeBooth uses MasterEditorBackend.php to remove a record from BoothNames.
 * If the choice wasn't made, or left on the default value, an alert is made
 * to the error. Otherwise, removeBooth posts to the script, and, upon success,
 * sets the dropdown to the default value, alerts to the success, removes all
 * occurances of the removed booth from all other dropdown menus in the form, 
 * and removes the booth from the array of booths. If the removal failed,
 * the user is alerted.
 */
function removeBooth(container) {
    $boothChoice = $(container + ' option:selected').val();
    if ($boothChoice === 'null') {
        alert('Please select a booth to remove');
        return;
    }
    $formData = {'requesting': 'remove a booth', 'booth': $boothChoice};
    $.post('MasterEditorBackend.php', $formData, function(response) {
        if (response === 'success') {
            $(container + ' select').val('null');
            alert('Successfully remove the booth!');
            $('[value="' + $boothChoice + '"]').remove();
            booths.splice(booths.indexOf($boothChoice), 1);
        }
        else alert('This is embarrassing. Would you kindly try again?');
    });
}

/* ##############
 * Helper methods
 * ##############*/

/** 
 * JSONToBoothsArray
 *
 * Convert a JSON Object to an Array with one less dimension.
 * Used upon page load.
 */
function JSONToBoothsArray(jsonObj) {
    parsedJSON = $.parseJSON(jsonObj);
    for (i = 0; i < parsedJSON.length; i++) {
        booths[i] = parsedJSON[i][0];
    }
}

/**
 * insertBoothNames
 *
 * Insert the booths names into each of the dropdown menus
 * where the names appear
 */
function insertBoothNames() {
    var str = '<option value="null">Please select a booth</option>'; 
    for(i = 0; i < booths.length; i++) {
        str += '<option value="'+ booths[i] +'">'+ booths[i] +'</option>';
    }
    $('select.booth-dropdown').html(str);
}
/*
function generateBooths() {
    var formData = {
        'requsting': 'generate questions'
    };
    $.post('MasterEditorBackend.php', formData, function(response) {

    });
}
*/
/**
 * logoutButtonHandler
 *
 * Posts to a php script to handle logging out. Redirects webpage if successful.
 */
function logoutButtonHandler() {
    $.post('LoginLogout.php', {'login': 'false'}, function(response) {
        if(response === 'success') {
            window.location.replace('http://student.cs.appstate.edu/haithcockce/QQuizzer/php/login.php');
        }
    });
}

/**
 * insertQuestions
 *
 * Tosses a JSON Object to a php script requesting the questions associated
 * with a chosen booth. Allows selection of a specific set of questions.
 * This function is executed by onchange hooks from the html on lines 129, 170,
 * and 195
 *
 * @param container
 *      The container this method with effect. 
 */
function insertQuestions(container) {

    //grab the chosen booth
    var $boothChoice = $(container + ' option:selected').val();

    //if default is selected
    if ($boothChoice === 'null') {
        //remove the previous menu and return
        $(container + ' .question-dropdown')
            .html('<option value="null">Please select booth to move from</option>')
            .prop('disabled', true);
        return;
    }
    //else, create the JSON to toss to the script
    $formData = {
        'requesting': 'get all questions',
        'booth': $boothChoice
    }
    
    //post to the server
    $.post('MasterEditorBackend.php', $formData, function(response) {
        //if no questions were found
        if (response === 'no questions') {
            //remove previous questions and return
            $(container + ' .question-dropdown')
                .html('<option value="null">No questions associated with this booth yet.</option>')
                .prop('disabled', true);
            return;
        }

        //parse the JSONed response, build the option list, and inject them
        var parsedJSON = $.parseJSON(response);
        var str = '<option value="null">Please select a question</option>';
        for (i = 0; i < parsedJSON.length; i++) {
            str += '<option value="'+ parsedJSON[i][0] +'">'+ parsedJSON[i][0] +'</option>';
        }
        $(container + ' .question-dropdown').html(str);
    });
    $(container + ' .question-dropdown').prop('disabled', false);   
}

function insertBoothNameAndOrganizer(container) {
    var boothName = $(container + ' option:selected').val();
    if (boothName === 'null') {
        $(container + ' .booth-name-input, ' + container + ' .booth-organizer-input')
            .val('')
            .prop('disabled', true);
        return;
    }
    var formData = {
        'requesting': 'get booth info',
        'booth': boothName
    };
    $.post('MasterEditorBackend.php', formData, function(response) {
        var parsedJSON = $.parseJSON(response);
        $(container + ' .booth-name-input')
            .val(boothName)
            .prop('disabled', false);
        $(container + ' .booth-organizer-input')
            .val(parsedJSON[0][0])
            .prop('disabled', false);
        organizer = parsedJSON[0][0];
    });
}

function insertQuestionAndAnswers(container) {
    var question = $(container + ' .question-dropdown').val();
    if (question === 'null') {
        $(container + ' input').eq(0)
            .val('Please select a booth and question to edit')
            .prop('disabled', true);
        for (var i = $(container + '.answers-container .row').length - 1; i > -1; i--) {
            if (i > 1)
                $(container + '.answers-container .row').eq(i).remove();
            else {
                $(container + '.answers-container .row').eq(i)
                    .val('')
                    .prop('disabled', true);
            }
        }
        return;
    }
    var formData = {
        'requesting': 'get question answers',
        'question': question
    }
    $.post('MasterEditorBackend.php', formData, function(response) {
        var parsedJSON = $.parseJSON(response);
        
        $(container + ' #question-input')
            .val(question)
            .prop('disabled', false);
        for (var i = 0; i < parsedJSON.length - 1; i++) {
            if (i > 1)
                addAnswerChoice(container);
            $(container + ' .answer-choice')
                .eq(i)
                .prop('disabled', false)
                .val(parsedJSON[i]);
        }
        var i = $(container + ' .answers-container .row').length - (parsedJSON.length - 1);
        for (i; i > 0; i--) {
            $(container + ' .answers-container .row').eq(parsedJSON.length - 1).remove()
        }
        
                
        $(container + ' input').prop('disabled', false);
        $(container + ' .correct-answer-indicator')
            .eq(parsedJSON[parsedJSON.length - 1])
            .prop('checked', true);
    });
}

/**
 * addAnswerChoice
 *
 * Injects an additional textbox to use if an additional answer is needed.
 */
function addAnswerChoice(container) {
    var i = $(container + ' .answers-container .row').length;
    if ($(container + ' .answers-container input').eq(0).prop('disabled') == true)
        return;
    $(container + ' .answers-container label').append(
        "<div class='row' id='a" + i + "'>" +    
            "<div class='large-11 medium-11 small-11 columns'>" +
                "<input class='answer-choice' type='text' placeholder='Another answer choice...' />" +
            "</div>" +
            "<div class='large-1 medium-1 small-1 columns'>" + 
                "<input class='correct-answer-indicator' id='ca" + i + "' type='radio' name='correct-answer' />" + 
            "</div>" + 
        "</div>"
    );
}

/**
 * removeAnswerChoice
 *
 * Removes the last textbox of answers. If that textbox was selected as the
 * 'correct' answer, then the first answer choice is selected as the 
 * 'correct' answer.
 */
function removeAnswerChoice(container) {
    //get the index of the last input box
    var i = $(container + ' .answers-container .row').length - 1;
    
    //prevent removal if 2 or less input boxes exist
    if (i < 2) return;
    if ($(container + ' .correct-answer-indicator').eq(i).prop('checked') === true) 
        $(container + ' .correct-answer-indicator').eq(0).prop('checked', true);
    $(container + ' .answers-container .row').eq(i).remove();
}