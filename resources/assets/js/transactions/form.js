$('#invoice_id').change(function(){
  if (this.value==-1){
    $('#new_invoice').slideDown();
  } else {
    $('#new_invoice').slideUp();
  }
});
$('#is_transfer').change(function(){
  if (this.checked){
    $('#account_id_transfer').closest('.form-group').slideDown();
    $('#is_credit').closest('.form-group').slideUp();
    $('#is_credit').prop('checked', true);
  } else {
    $('#account_id_transfer').closest('.form-group').slideUp();
    $('#is_credit').closest('.form-group').slideDown();
  }
});
$(function(){
	$('#invoice_id').change();
	$('#is_transfer').change();
})