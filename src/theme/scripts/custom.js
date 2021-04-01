$(function(){
	/* form validator */
	$(".validate").validate();
	
	/* Phone no masking */
	$(".phone").mask("999-999-9999");
	
	/* product filter for search page */
	$(".filter-sortby").on("change",function(){
        var productname = $(".productname").val();
        var minprice = $("#price-min").val();
        var maxprice = $("#price-max").val();
        var category = $("#categoryval").val();
        var orderby = $(this).val();
        $.post(HomeURL+'/handler/search.php',{
        	productname:productname,
        	minprice:minprice,
        	maxprice:maxprice,
        	category:category,
        	orderby:orderby
        },function(data){
        	if(data.action=='success')
    		{
        		$(".producthtml").html("");
        		$(".producthtml").html(data.productHTML);
    		}
        });
	});
	
	/* select 2 define */
	$(".select2").select2();
});