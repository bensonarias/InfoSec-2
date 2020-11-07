$(document).ready(function(){
  
    $(".filter-btn").click(function(){
        var value = $(this).attr('data-filter');
        
        if(value == "All")
        {
            $('.filter').show('1000');
        }
        else
        {

              $(".filter").not('.'+value).hide('3000');
            $('.filter').filter('.'+value).show('3000');
            
        }
    });

});

$(".filter-btn").click().toggleClass("active");