var QQ=function(){
	var _this=this;
	var qq2right=$('#qq2').find('.sidecon').innerWidth();
	var qq5right=$('#qq5').find('.sidecon').innerWidth();
	this.init=function(){
		$('#qq2').css('right',-qq2right);
		$('#qq5').css('right',-qq5right);
		$('#top a').click(function(){
			$('html,body').animate({'scrollTop':0},'slow');
			return false;
		});
		
		$('#qq2').on('click','.del2',function(){
			   _this.removebox();
			   return false;
		});
		$('#qq2').on('click','.side',function(){
			if(parseInt($('#qq2').css('right'))==0){
				$('#qq2').animate({'right':-qq2right},'slow')
				$(this).find('a').show();
			}else{
				$('#qq2').animate({'right':0},'slow');
				$(this).find('a').hide();
			}
			
		});
		$('#qq2').on('click','.del',function(){
			 $('#qq2').animate({'right':-qq2right},'slow');
			 $(this).parents('#qq2').find('.del2').show();
			 return false;
		});
		$('#qq4').on('click','.del',function(){
			$(this).parents('#qq4').remove();
			return false;
		});
		$('#qq5').hover(function(){
			$(this).stop(true,true).animate({'right':'0'},'slow');	
			$(this).find('em').text('>>');
		},function(){
			$(this).stop(true,true).animate({'right':-qq5right},'slow');
			$(this).find('em').text('<<');
		});
		$('#qq8 .siderNav > li').hover(function(){
					
			$(this).find('.hide').stop(true,true).animate({
						'right':0});
					
			$(this).find('.wk,.hide2').show();
					
		},function(){
			$(this).find('.hide').stop(true,true).animate({
						'right':'-163px'},0);
			$(this).find('.wk,.hide2').hide();
		});
		
		$('#qq9 li').hover(function(){
   			if($(this).find('.wk').size()>0){
   				$(this).find('.wk').show();
   			}
   		},function(){
   			$(this).find('.wk').hide();
   		});
   		$('#top').click(function(e){
   			$('html,body').animate({'scrollTop':0},'slow');
   			e.preventDefault();
   		});
	};
	this.removebox=function(){
		$('#qq2').remove();
	}
}
jQuery(function($){
	var qq= new QQ();
	qq.init();
});
