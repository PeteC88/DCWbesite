$(function() {
    $("#submit_contact_form").on('submit', function(e) {
        var valid = false;
        //To stock the form in this variable in the ajax type and URL. It takes directly the action and method from the form in the view.
        $this = $(this);
        e.preventDefault();

        var name = $("#name").val(); //to stock the name via the id selector in the form in the var name
        var surname = $("#surname").val(); //to stock the surname via the id selector in the form in the var surname
        var email = $("#email").val(); //to stock the email via the id selector in the form in the var email
        var phone = $("#phone").val(); //to stock the phone via the id selector in the form in the var phone
        var object = $("#object").val(); //to stock the object of the mail via the id selector in the form in the var object
        var content = $("#content").val(); //to stock the content via the id selector in the form in the var content
        var mailformat = /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;


        $("#error_message").css('padding', '20px');

        if (name.length < 3) {
            $("#error_message").html('Inserire un nome valido di almeno 3 caratteri');
            valid = false;
            $("#name").focus();
        }
        else if(surname.length < 2){
            $("#error_message").html('Inserire un cognome valido di almeno 3 caratteri');
            valid = false;
            $("#surname").focus();
        }
        else if(email.length < 6 || !mailformat.test(email) ){
            $("#error_message").html('Inserire un indirizzo email valido');
            valid = false;
            $("#email").focus();
        }
        else if(isNaN(phone) || phone.length < 10 ){
            $("#error_message").html('Inserire un numero di telefono valido di almeno 10 caratteri');
            valid = false;
            $("#phone").focus();
        }
        else if(!object){
            $("#error_message").html('Scegliere uno dei motivi dal menu a tendina');
            valid = false;
            $("#object").focus();
        }
        else if(content.length < 30){
            $("#error_message").html('Inserire un messaggio di almeno 30 caratteri');
            valid = false;
            $("#content").focus();
        }
        else{
            var dataForm = $(this).serialize(); //to serialize the data in the ajax et don't write it like "name="+name+"&email="+email+"&content="+content in the data field.
            $("#error_message").css('padding', '0px').html('');
            $.ajax({
                type: $this.attr('method'), //it takes the method from the form
                url: $this.attr('action'), //it takes the action from the form
                data: dataForm,
                dataType: "json",
                //in case of the success of the ajax request
                success: function(data){
                    $('.reponse').fadeIn().text(data.content);
                    if(data.reponse === 'success'){
                        $("#submit_contact_form")[0].reset(); //to clear the form after the submission
                        $('.reponse').css('color', 'green');
                        setTimeout(function() {
                            $('.reponse').fadeOut("slow");
                        }, 10000 );
                        valid = true;
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
        }
        return valid;
    });
});