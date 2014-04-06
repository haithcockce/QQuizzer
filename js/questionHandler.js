var QUESTION_NUMBER = 0;
var QUESTION_CLAUSE = 1;
var QUESTION_CORRECT_ANSWER_INDEX = 2;
var QUESTION_ANSWER_CHOICES = 3;


function getQuestion(booth, answeringStatus) {
    if (answeringStatus === 'answered') {
        window.location = 'alreadyAnswered.php';
        return;
    }

    var formData;
    if (answeringStatus === 'incomplete') {
        formData = {
            'key': localStorage.getItem('deviceID'),
            'requesting': 'get old question',
            'booth': booth
        };
    }
    else if (answeringStatus === 'start') {
        formData = {
            'key': localStorage.getItem('deviceID'),
            'requesting': 'get new question',
            'booth': booth
        };
    }
        
    $.post('../questions/questionHandler.php', formData, function(response) {
        var question = $.parseJSON(response);
        QUESTION_CORRECT_ANSWER_INDEX = question[QUESTION_CORRECT_ANSWER_INDEX];
        $('h2').text(question[QUESTION_CLAUSE]);
        var questionHTML = '';
        for (var i = 0; i < question[QUESTION_ANSWER_CHOICES].length; i++) {
           questionHTML += '<div class="row">\n' +
                           '   <div class="small-1 medium-1 large-1 column">\n' +
                           '      <input class="answer-selector" id="' + i + '" type="radio" name="answer-choices">\n' + 
                           '   </div>\n' +
                           '   <div class="large-11 medium-11 small-11 column">\n' +
                           '      <p>\n' + question[QUESTION_ANSWER_CHOICES][i] + '</p>\n' +
                           '   </div>\n' + 
                           '</div>\n';
        }
        $('.answer-choices-container').html(questionHTML);
        localStorage.setItem(booth, 'incomplete');
    });
}

function checkAnswer(booth) {
    //Make answer selection error alert more elegant!
    if ($('input:checked').length == 0){
        if ($('.error').length == 0) {
            $('.submit-button-container')
                .append('<small class="error">Please pick an answer.</small>')
        }
        return;
    }
    else {
        if ($('.error').length > 0)
            $('.error').remove();
    }

    $('input').prop('disabled', true);
    $('a').attr('disabled', 'disabled').removeAttr('onclick').unbind('click');
    var correct = (($('input:checked').attr('id') == QUESTION_CORRECT_ANSWER_INDEX) ? true : false);
    var formData = {
        'requesting': 'answer a question',
        'key': localStorage.getItem('deviceID'),
        'question': $('h2').text(),
        'answerChoice': $('input:checked').parent().next().text().trim(),
        'correct': correct
    };
    $.post('../questions/questionHandler.php', formData, function(respoonse) {
        localStorage.setItem(booth, 'answered');
        if (correct) 
            window.location = 'correctAnswerResponse.php';
        else 
            window.location = 'incorrectAnswerResponse.php';
    });

}