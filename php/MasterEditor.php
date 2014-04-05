<?php
   session_start();
   if(!isset($_SESSION['Name'])) {
       header('Location: http://student.cs.appstate.edu/haithcockce/QQuizzer/php/login.php');
   }

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <title>Quession Submission</title>

   <!--Foundation Stuff-->
   <link rel="stylesheet" href="../css/foundation.css" />
   <script src="../js/vendor/jquery.js"></script>
   <script src="../js/foundation.min.js"></script>
   <script>$(document).foundation();</script>
   <script src="../js/vendor/modernizr.js"></script>

   <!--Custom Stuff-->
   <link rel="stylesheet" href="../css/custom.css" />
   <script src='../js/MasterEditor.js'></script> 
</head>

<body>
   <div class='row global-container'>
      <form>
         <fieldset>
            <div class='row'>
               <div class='small-12 medium-12 large-12 column'>
                  <h3>Master Editor</h3>
               </div>
            </div>
            <div class='row button-container'>
               <div class='small-12 medium-12 large-12 column'>
                  <h6>Question Edit Options</h6>
                  <!--##################### 
                      Question Edit options
                      #####################-->
               </div>
               <div class='small-12 medium-6 large-3 columns'>
                  <a href='#' id='add-question-button' onClick='buttonHandler("#add-question-container")' class='button expand'>Add Question</a>
               </div>
               <div class='small-12 medium-6 large-3 columns'>
                  <a href='#' id='edit-question-button' onClick='buttonHandler("#edit-question-container")' class='button expand'>Edit Question</a>
               </div>
               <div class='small-12 medium-6 large-3 columns'>
                  <a href='#' id='remove-question-button' onClick='buttonHandler("#remove-question-container")' class='button expand'>Remove Question</a>
               </div>
               <div class='small-12 medium-6 large-3 columns'>
                  <a href='#' id='move-question-button' onClick='buttonHandler("#move-question-container")' class='button expand'>Move Question</a>
               </div>

               <hr />
               <div class='small-12 medium-12 large-12 columns'>
                  <h6>Booth Edit Options</h6>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='add-booth-button' onClick='buttonHandler("#add-booth-container")' class='button expand'>Add Booth</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='edit-booth-button' onClick='buttonHandler("#edit-booth-container")' class='button expand'>Edit Booth</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='remove-booth-button' onClick='buttonHandler("#remove-booth-container")' class='button expand'>Remove Booth</a>
               </div>
               <hr />
            </div>


            <!-- #############################
                 Add Question part of the form 
                 #############################-->
            <div class='row ModificationContainer' id='add-question-container'>
               <div class='large-6 medium-6 small-12 columns' id='booth-selection-container'>
                  <label>Select the booth
                     <select class='booth-dropdown'>
                        <!-- Injected content-->
                     </select>
                  </label>
               </div>
               <div class='large-12 small-12 column' id='question-clause-input-container'>
                  <label>Question
            	     <input type='text' id='question-clause-input' placeholder='What is the answer to life, the universe, and everything?' />
                  </label>
               </div>
               
               <div class='large-12 medium-12 small-12 columns answers-container'>
                  <label>Possible answers (Make sure to indicate which answer is the correct answer!)
                     <div class='row' id='a0'>
                        <div class='large-11 medium-11 small-11 columns'>
                           <input class='answer-choice' type='text' placeholder='First answer choice' /> 
                        </div>
                        <div class='large-1 medium-1 small-1 columns'>
                            <input class='correct-answer-indicator' id='ca0' type='radio' name='correct-answer' checked />
                        </div>
                     </div>
                     <div class='row' id='a1'>
                        <div class='large-11 medium-11 small-11 columns'>
                           <input class='answer-choice' type='text' placeholder='Another answer choice...' />
                        </div>
                        <div class='large-1 medium-1 small-1 columns'>
                            <input class='correct-answer-indicator' id='ca1' type='radio' name='correct-answer' />
                        </div>
                     </div>
                  </label>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='additional-answer-button' class='button expand' onClick='addAnswerChoice("#add-question-container")'>Add another answer</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='remove-answer-button' class='button expand' onClick='removeAnswerChoice("#add-question-container")'>Remove last choice</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='submit' class='button expand' onClick='addQuestion("#add-question-container")'>Submit</a>
               </div>
            </div>
   

            <!-- ##############################
                 Edit Question part of the form
                 ############################## -->
            <div class='row ModificationContainer' id='edit-question-container'>
               <div class='large-6 medium-6 small-12 columns'>
                  <label>Select the booth
                     <select class='booth-dropdown' onchange='insertQuestions("#edit-question-container")'>
                        <!-- Injected content-->
                     </select>
                  </label>
               </div>
               <div class='large-6 medium-6 small-12 columns'>
                  <label>Select the question
                     <select class='question-dropdown' onchange='insertQuestionAndAnswers("#edit-question-container")' >
                        <option value='null'>Please select booth to move from</option>
                        <!-- Injected content -->
                     </select>
                  </label>
               </div>
               <div class='large-12 small-12 column'>
                  <label>Question
                     <input id='question-input' type='text' value='Please select a booth and question to edit' disabled />
                  </label>
               </div>
               <div class='large-12 small-12 column answers-container'>
                  <label>Possible Answers
                     <div class='row' id='ea0'>
                        <div class='large-11 medium-11 small-11 columns'>
                           <input class='answer-choice' type='text' disabled /> 
                        </div>
                        <div class='large-1 medium-1 small-1 columns'>
                            <input class='correct-answer-indicator' id='eca0' type='radio' name='correct-answer' checked disabled />
                        </div>
                     </div>
                     <div class='row' id='ea1'>
                        <div class='large-11 medium-11 small-11 columns'>
                           <input class='answer-choice' type='text' disabled />
                        </div>
                        <div class='large-1 medium-1 small-1 columns'>
                            <input class='correct-answer-indicator' id='eca1' type='radio' name='correct-answer' disabled />
                        </div>
                     </div>
                  </label>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='additional-answer-button' class='button expand' onclick='addAnswerChoice("#edit-question-container")'>Add another answer</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='remove-answer-button' class='button expand' onClick='removeAnswerChoice("#edit-question-container")'>Remove last choice</a>
               </div>
               <div class='small-12 medium-4 large-4 columns'>
                  <a href='#' id='submit' class='button expand' onclick='editQuestion("#edit-question-container")'>Submit</a>
               </div>
            </div>


            <!-- ################################
                 Remove Question part of the form 
                 ################################ -->
            <div class='row ModificationContainer' id='remove-question-container'>
               <div class='large-6 medium-6 small-12 columns'>
                  <label>Select the booth
                     <select class='booth-dropdown' onchange='insertQuestions("#remove-question-container")'>
                        <!-- Injected content-->
                     </select>
                  </label>
               </div>
               <div class='large-6 medium-6 small-12 columns'>
                  <label>Select the question
                     <select class='question-dropdown'>
                        <option value='null'>Please select booth to move from</option>
                        <!-- Injected Content -->
                     </select>
                  </label>
               </div>
               <div class='small-12 medium-12 large-12 columns'>
                  <a href='#' id='submit' class='button expand' onClick='removeQuestion("#remove-question-container")'>Submit</a>
               </div>
            </div>


            <!-- ##############################
                 Move Question part of the form 
                 ##############################-->
            <div class='row ModificationContainer' id='move-question-container'>
               <div class='large-4 medium-4 small-12 columns' id='from-booth-container'>
                  <label>Select the booth
                     <select class='booth-dropdown' id='from-booth' onchange='insertQuestions("#move-question-container")'>
                     <!-- Injected content-->
                     </select>
                  </label>
               </div>
               <div class='large-4 medium-4 small-12 columns' id='question-to-move-container'>
                  <label>Select the question
                     <select class='question-dropdown' id='question-to-move'>
                        <option value='null'>Please select booth to move from</option>
                        <!-- Injected content -->
                     </select>
                  </label>
               </div>
               <div class='large-4 medium-4 small-12 columns' id='to-booth-container'>
                  <label>Select the new booth
                     <select class='booth-dropdown' id='to-booth'>
                        <!-- Injected content-->
                     </select>
                  </label>
               </div>
               <div class='small-12 medium-12 large-12 columns'>
                  <a href='#' id='submit' class='button expand' onClick='moveQuestion("#move-question-container")'>Submit</a>
               </div>
            </div>


            <!-- ##########################
                 Add Booth part of the form
                 ########################## -->
            <div class='row ModificationContainer' id='add-booth-container'>
               <div class='large-6 medium-6 small-12 column' id='booth-name-input-container'>
                  <label>Booth Topic/Title
                     <input id='booth-name' type='text' placeholder='AI GONE WRONG!' />
                  </label>
               </div>
               <div class='large-6 medium-6 small-12 column' id='booth-organizer-input-container'>
                  <label>organizer
                     <input id='booth-organizer' type='text' placeholder='Charles Haithcock' />
                  </label>
               </div>
               <div class='large-12 medium-12 small-12 column'>
                  <a href='#' class='button expand' onClick='addBooth("#add-booth-container")'>Submit</a>
               </div>
            </div>

            <!-- ###########################
                 Edit Booth Part of the form
                 ########################### -->
            <div class='row ModificationContainer' id='edit-booth-container'>
               <div class='large-4 medium-4 small-12 columns'>
                  <label>Select the booth
                     <select class='booth-dropdown' onchange='insertBoothNameAndOrganizer("#edit-booth-container")'>
                        <!-- Injected content-->
                     </select> 
                  </label>
               </div>
               <div class='large-4 medium-4 small-12 columns'>
                  <label>Topic or Booth Name
                     <input class='booth-name-input' type='text'/>
                  </label>
               </div>
               <div class='large-4 medium-4 small-12 columns'>
                  <label>organizer
                     <input class='booth-organizer-input' type='text'/>
                  </label>
               </div>
               <div class='large-12 medium-12 small-12 column'>
                  <a href='#' class='button expand' onclick='editBooth("#edit-booth-container")'>Submit</a>
               </div>
            </div>

            <!-- #######################
                 Remove Booth Subsection
                 ####################### -->
            <div class='row ModificationContainer' id='remove-booth-container'>
               <div class='large-6 medium-6 small-12 column'>
                  <label>Select the booth
                     <select class='booth-dropdown'>
                         <!-- Content is injected -->
                     </select> 
                  </label>
               </div>
               <div class='large-6 medium-6 small-12 column'>
                  <a href='#' class='button expand' onClick='removeBooth("#remove-booth-container")'>Submit</a>
               </div>
            </div>

            <!-- Logout Button -->
            <div class='row logout-button-container'>
               <div class='large-12 medium-12 small-12 column'>
                  <a href='#' id='logout-button' class='button expand'>Logout</a>
               </div>
            </div>
         </fieldset>
      </form>
   </div>
</body>
</html>
