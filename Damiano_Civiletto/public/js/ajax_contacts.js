$("#submit_contact_form").on('submit', function(e) {
    //To stock the form in this variable in the ajax type and URL. It takes directly the action and method from the form in the view.
    $this = $(this);
    e.preventDefault();
    var name = $("#name").val(); //to stock the name via the id selector in the form in the var name
    var surname = $("#surname").val(); //to stock the surname via the id selector in the form in the var surname
    var email = $("#email").val(); //to stock the email via the id selector in the form in the var email
    var phone = $("#phone").val(); //to stock the phone via the id selector in the form in the var phone
    var object = $("#object").val(); //to stock the object of the mail via the id selector in the form in the var object
    var content = $("#content").val(); //to stock the content via the id selector in the form in the var content


    var dataForm = $(this).serialize(); //to serialize the data in the ajax et don't write it like "name="+name+"&email="+email+"&content="+content in the data field.

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
