$('#invoice_id').change(function(){
  if (this.value==-1){
    $('#new_invoice').slideDown();
  } else {
    $('#new_invoice').slideUp();
  }
});
$(function(){
	$('#invoice_id').change();
})