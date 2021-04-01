$(function(){
	/*Count cart item*/
	CountCartItem();
	  
	$(".quantity-minus").click(function(){
		var qty = $(".qty").val();
		if(qty > 1)
		{
			var newqty = parseInt(qty) - parseInt(1);
			$(".qty").val(newqty);
		}
	});
	
	$(".quantity-plus").click(function(){
		var qty = $(".qty").val();
		var newqty = parseInt(qty) + parseInt(1);
		$(".qty").val(newqty);
		
	});
	
	/* variation on change data*/
	$(".variationid").on("change",function(){
		var pcode = $(this).find(":selected").data("productcode");
		var price = $(this).find(":selected").data("price");
		$("#pcode").html(pcode);
		$("#pprice").html(price);
	});

	
	/* Add to cart validation */
	$("#addtocart").on("click",function(){
		var variationCheck = $(".variationid").data('variation');
		if(variationCheck==undefined)
		{
			$(".validate").submit();
		}
		else if(variationCheck=='yes')
		{
			var variationid = $(".variationid").val();
			if(variationid > 0)
			{
				$(".validate").submit();
			}
			else
			{
				$.alert('Please select any variation.');
			}
		}
	});

	
	/*Delete Cart Item */
	$('.deletecartitem').click(function (e) {
		e.preventDefault();
		var cartid = $(this).data('cartid');
		var me = $(this);
		$.post(HomeURL + '/handler/deletecartitem.php', 
		{
			cartid: cartid
		},function (data) {
			if (data == 1) {
				me.closest('tr').remove();
				CountCartItem();
				ChangeTotal();
			} 
		});
	});
	
	/* quantity modify */
	$('.quantity').on('keyup', function(){
		var quantity = $(this).val();
		var price = $(this).data("price");
		if(quantity=='0')
		{
			$(this).val('1');
		}
		
		
		var total = (quantity * price);
		var total = total.toFixed(2);
		$(this).closest('tr').find('.price').html(total,2);
		
		$.post(HomeURL + '/handler/updatecartquantity.php',{
			quantity : $(this).val(),
			itemid : $(this).data("itemid"),
			type : 'quantity'
		},function(data){
		});
		
		ChangeTotal();
	 });
	
	
	/* Cart Total Calculation */
	function ChangeTotal() {
		var total = 0;
		if ($('.productrow').length <= 0) {
			window.location.href = HomeURL + '/cart.php';
		}

		$('.productrow').each(function () {
			var ntotal = $(this).find('.price').html();
			ntotal = ntotal.replace(/\,/g, '');
			total = total + parseFloat(ntotal);
			ftotal = total.toFixed(2);
		});
		$('.grandtotal').html(ftotal);
	}
	
	/* Count cart item function */
	function CountCartItem()
	{
		 $.post(HomeURL+'/handler/countcartdata.php',
		 {},function(data)
		 {
			$("#countcart").html(data);
		 });
	}
});