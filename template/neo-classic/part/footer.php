<?php
/**
 * @Author: ido_alit
 * @Date:   2015-11-12 18:46:55
 * @Last Modified by:   ido_alit
 * @Last Modified time: 2015-11-23 20:28:39
 */

if (!(isset($_GET['p']) && ($_GET['p'] == 'login' || $_GET['p'] == 'visitor'))) {
?>

<footer class="slims-row">
    <div class="slims-12">
        <div class="slims-card slims-card--default">Template by "Ido Alit" &copy; 2015</div>
    </div>
</footer>

<?php } ?>

</div> <!-- // wraper end -->

<script>

	<?php if(isset($_GET['search']) && isset($_GET['keywords']) && ($_GET['keywords']) != '') : ?>
  	$('.biblioRecord .detail-list, .biblioRecord .title, .biblioRecord .abstract, .biblioRecord .controls').highlight(<?php echo $searched_words_js_array; ?>);
  	<?php endif; ?>

    <?php if(isset($_GET['p']) && ($_GET['p']) == 'librarian') : ?>
    var noLibrarian = $('.librarian-list p:first').text();
    if ( noLibrarian === 'No librarian data yet') {
        $('.librarian-list p:first').addClass('slims-card slims-card--warning');
    }
    <?php endif; ?>

    $(document).ready(function () {
        var eHeight = $('.slims-vertical').height();
        var docHeight = $(document).height();
        var eMarginTop = ( docHeight / 2 ) - ( eHeight / 2 );

        // vertical element
        $('.slims-vertical').css('margin-top', eMarginTop);

    });

    function search_func(value)
    {
        $.ajax({
           type: "GET",
           url: "?p=suggest",
           data: {'search_keyword' : value},
           dataType: "text",
           success: function(msg){
                       //Receiving the result of search here
           }
        });
    }

                $("#suggest").hide(); 
                $('#keyword').keyup(function(){ 
                   if( !$(this).val() ) {  
                 $(".child").remove();
                }
                $("#suggest").show();
                var parentVal = $('#keyword').val(); 
                $.ajax({
                    url     : '?p=suggest', 
                    type    : 'GET',
                    data    : { keyword: parentVal},
                    dataType :'json',
                    success : function(data){
                      console.log(data);
                      $(".child").remove();
                        $.each(data, function(index,value) {
                       $("#suggest").append( "<div class=\"child\" onClick = \"add('"+value+"');\">"+value+"</div>" );
                     })
                    }
                })
            })

function add(a){
  var text = a.replace(/<\/?[^>]+(>|$)/g, "");
  $('#keyword').val(text).focus();

  $("#suggest").hide();  
  console.log(a);
}

</script>
</body>
</html>