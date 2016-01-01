$(".delete_button1").hide();
$(".submit_button").hide();

/*
$( ".goals_content" ).hover(
  function() {
    $( this ).addClass( "hover" );
    $(".hover .delete_button1").show("fast");
    $(".hover .submit_button").show("fast");
  }, function() {
    $(".hover .delete_button1").hide("fast");
    $(".hover .submit_button").hide("fast");
    $( this ).removeClass( "hover" );
  }
);
*/

$(".goals_content").hover(
    function() {
        $(this).addClass("hover");
        $(".hover .submit_button").stop();
        $(".hover .delete_button1").toggle(200);
        $(".hover .submit_button").toggle(200);
    }, function() {
        $(".hover .submit_button").stop();
        $(".hover .delete_button1").toggle(200);
        $(".hover .submit_button").toggle(200);
        $( this ).removeClass( "hover" );
    }
);