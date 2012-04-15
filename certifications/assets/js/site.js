/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
    
    function moveUiStep(step) {
        $('ul.steps li:not(.step-' + step + ')').removeClass('active');
        $('ul.steps li.step-' + step).addClass('active');
    }
    
    function disableAccountInputs () {
        $('#collect-ote-credentials').attr('readonly', 'readonly').attr('disabled', 'disabled');
    }
    
    function enableAccountInputs () {
        $('#collect-ote-credentials').removeAttr('readonly').removeAttr('disabled');
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
            enableAccountInputs();
            return true;
        }
        
        return false;
    }
    
    function Step7 () {
        var url = 'tests/test-7.php';
        return {
            execute: function () {
                moveUiStep(7);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        window.alert("Congratulations! Your Wild West Account should now be certified.");
                    }
                });
            }
        };
    }
    
    function Step6 () {
        var url = 'tests/test-6.php';
        return {
            execute: function () {
                moveUiStep(6);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step7().execute();
                    }
                });
            }
        };
    }
    
    function Step5 () {
        var url = 'tests/test-5.php';
        return {
            execute: function () {
                moveUiStep(5);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step6().execute();
                    }
                });
            }
        };
    }
    
    
    function Step4 () {
        var url = 'tests/test-4.php';
        return {
            execute: function () {
                moveUiStep(4);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step5().execute();
                    }
                });
            }
        };
    }
    
    function Step3 () {
        var url = 'tests/test-3.php';
        return {
            execute: function () {
                moveUiStep(3);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step4().execute();
                    }
                });
            }
        };
    }
    
    function Step2 () {
        var url = 'tests/test-2.php';
        
        return {
            execute: function () {
                moveUiStep(2);
                $.getJSON(url, function (resp) {
                    if (!detectAndAlertError(resp)) {
                        new Step3().execute();
                    }
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
                moveUiStep(0);
                $.getJSON(url, data, function (resp) {
                   if (!detectAndAlertError(resp)) {
                       new Step1().execute();
                   }
                });
            }
        }
    }
    
    
    $('#collect-ote-credentials button').click(function(){
        disableAccountInputs();
        new Step0().execute();
    });
    
});