$(function(){
$("#submit_form").on('submit', function(e) {
     $this = $(this) //To stock the form in this variable and get theò in the ajax type and URL. It takes directly the action and method from the form in the view.
        e.preventDefault();

        var author = $("#author").val(); //to stock the author name via the id selector in the form in the var author
        var email = $("#email").val(); //to stock the email via the id selector in the form in the var email
        var comment = $("#comment").val(); //to stock the comment via the id selector in the form in the var comment

        var dataForm = $(this).serialize(); //to serialize the data in the ajax et don't write it like "author="+author+"&email="+email+"&comment="+comment in the data field.

        $.ajax({
            type: $this.attr('method'), //it takes the method from the form
            url: $this.attr('action'), //it takes the action from the form
            data: dataForm,
            dataType: "json",
            //in case of the success of the ajax request
            success: function(data){
                $('.reponse').fadeIn().text(data.content);
                if(data.reponse == 'success'){
                    $("#submit_form")[0].reset(); //to clear the form after the submission
                    $('.reponse').css('color', 'green');
                    setTimeout(function() {
                        $('.reponse').fadeOut("slow");
                    }, 5000 );
                }
                else{
                    $('.reponse').css('color', 'red');
                }
            },
            //In case of the failure in the Ajax request
            error: function (data) {
                $(".reponse").text("Si è prodotto un errore, impossibile inviare il messaggio");
            }
        });
    });
});
