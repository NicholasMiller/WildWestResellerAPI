/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    
    function moveUiStep(step) {
        $('ul.steps li:not(.step-' + step + ')').removeClass('active');
        $('ul.steps li.step-' + step).addClass('active');
    }
    
    // Alerts server error messages. 
    // @return boolean, true if error was discovered. False otherwise.
    function detectAndAlertError(serverResponse) {
        if (!serverResponse.success) {
            var error = "There was a problem with this step.";
            if (serverResponse.message) {
                error += " The server said: " + serverResponse.message;
            }
            
            window.alert(error);
            return true;
        }
        
        return false;
    }
    
    
    function Step2 () {
        return {
            execute: function () {
                moveUiStep(2);
                var url = 'tests/test-2.php';
                $.getJSON(url, function (resp) {
                    return detectAndAlertError(resp);
                });
            }
        };
    }
    
    
    function Step1 () {
        return {
            execute: function () {
                moveUiStep(1);
                var url = 'tests/test-1.php';
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step2().execute();
                    }
                });
            }
        };
    }
    
    function Step0 () {
        
        var url = 'tests/start.php';
        var data = $('#collect-ote-credentials input').serialize();
        
        return {
            execute: function () {
                $.getJSON(url, data, function (resp) {
                   if (!detectAndAlertError(resp)) {
                       new Step1().execute();
                   }
                });
            }
        }
    }
    
    
    $('#collect-ote-credentials button').click(function(){
        new Step0().execute();
    });
    
});