/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function () {
   
   $('#collect-ote-credentials button').click(function(){
      
      var url = 'tests/start.php';
      var data = $('#collect-ote-credentials input').serialize();
      $.getJSON(url, data, function (data) {
          if (!data.success) {
              var error = "There was a problem with this step.";
              if (data.message) {
                  error += " The server said: " + data.message;
              }
              window.alert(error);
          }
      });
       
   });
    
});