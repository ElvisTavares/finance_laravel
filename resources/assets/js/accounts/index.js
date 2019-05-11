function show_invoice(id){
	$("#invoice_"+id).show();
}

$(function(){
  $('table#accounts th, table#accounts td').hover(function(){
      $('table#accounts th, table#accounts td').removeClass('litte-hovered').removeClass('hovered');
      const account = $(this).data('account');
      const month = $(this).data('month');
      if (!account && !month) return;
      if (account && month){
          $('[data-account='+account+'][data-month='+month+']').addClass('hovered');
          $('[data-account='+account+']').addClass('litte-hovered');
          $('[data-month='+month+']').addClass('litte-hovered');
      } else if(account && !month){
          $('[data-account='+account+']').addClass('hovered');
      } else if(!account && month){
          $('[data-month='+month+']').addClass('hovered');
      }
  });
});