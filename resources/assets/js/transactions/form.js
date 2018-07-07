$('#invoice_id').change(function(){
  if (this.value==-1){
    $('#new_invoice').slideDown();
  } else {
    $('#new_invoice').slideUp();
  }
});
$('#is_transfer').change(function(){
  if (this.checked){
    $('#transfer_account').slideDown();
    $('#is_credit_div').slideUp();
    $('#is_credit').prop('checked', true);
  } else {
    $('#transfer_account').slideUp();
    $('#is_credit_div').slideDown();
  }
});
$(function(){
	$('#invoice_id').change();
	$('#is_transfer').change();
})