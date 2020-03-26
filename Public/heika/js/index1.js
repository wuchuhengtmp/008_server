var scn_data = {
	banner:[],
	catlist:[],
	tequan:[],
	top: 0,
};

var vm = new Vue({
	el: '#container',
	data: scn_data,
	created(){
		// this.setNumberTransform()
		let thiz=this;
		$.ajax({
			url:"https://tkyx.taokeyun.cn/app.php?c=Card&a=index",
			dataType:'json',
			success:(res)=>{
				console.log(res);
				
				if(res.code==0){
					thiz.banner=res.data.banner;
					thiz.catlist=res.data.catlist;
					thiz.tequan=res.data.tequan;
				}
				console.log(thiz.banner,thiz.catlist,thiz.tequan);
			
			}}
		);
		this.top=$('#menu').offset().top
	},
	
	mounted(){
		let thiz=this;
		var swiper = new Swiper('.swiper-container',
        {
            controller: true,
            loop: true,
            autoplay: {
                delay:2000
            },
            observer:true,
    		observeParents:true,
            pagination: {
                el: '.swiper-pagination',
            }
		});
		$("#container").scroll(function (){
			console.log($("#container").scrollTop());
			if($("#container").scrollTop()<thiz.top){
				$("#menu").removeClass("fixed");
			}else{
				$("#menu").addClass("fixed");
			}
			// var topPin = $('.mobile_pin').offset().top-50;
		});
		
	},
	methods:{
		
		toUrl(url){
			window.location.href=url
		},
		jump(ids) {

			$("#menu").addClass("fixed");
	
			$("#menu").stop().animate({
				scrollLeft: $("#" + ids)[0].offsetLeft - $("#" + ids).width() / 2
			}, 100, "swing")
	
			window.onscroll = null;
	
			$("#" + ids).addClass("cate_titlesel").siblings().removeClass("cate_titlesel");
	
			$("#container").stop().animate({
				scrollTop: $("#item" + ids).offset().top - $("#menu").height()
			}, 100, "swing")
		}
	},
})