<?php

ob_start();
?>
<script type="text/javascript">
$( "#keyword" ).after( "<div id=\"list\"></div>" );
$('#keyword').keyup(function() {
	
  var keyword = $("#keyword").val();
  $.ajax({
  	method: "POST",
  	data: {keyword : keyword},
  	url: "?p=suggest",

  	success:function(response) {
  		//alert(response);

        $("#list").html('yaayay');
       },
       error:function(){
        alert("error");
    }
	});

});


</script>
<?php

echo ob_get_clean();
