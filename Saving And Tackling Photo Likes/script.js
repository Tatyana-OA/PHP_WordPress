jQuery( document ).ready(function() {
	console.log('JS wants to know: do u like me?')
	let lgLikeBtn;
	if (document.querySelector('div.lightgallery')) {
		document.querySelector('div.lightgallery').addEventListener('click', (e)=> {
			lgLikeBtn = document.querySelector('button.lg-like-btn')
			tackleLgHoverLike(lgLikeBtn);
			likeLgPhoto(lgLikeBtn)
	
		})
	}

	if (window.location.href.includes('lg')) {
		setTimeout(function(){ 
			lgLikeBtn = document.querySelector('button.lg-like-btn')
			tackleLgHoverLike(lgLikeBtn);
			likeLgPhoto(lgLikeBtn)
			document.querySelector('.lg-thumb-outer').addEventListener('click', (e)=> {
				if (e.target.tagName=='IMG') {
					//console.log('img')
					if (document.querySelector('.lg-likes-span')) {
						document.querySelector('.lg-likes-span').remove();
					}
				}
			})
			document.querySelector('.lg-next').addEventListener('click', (e)=> {
				if (document.querySelector('.lg-likes-span')) {
					document.querySelector('.lg-likes-span').remove();
				}
			})
			document.querySelector('.lg-prev').addEventListener('click', (e)=> {
				if (document.querySelector('.lg-likes-span')) {
					document.querySelector('.lg-likes-span').remove();
				}
			})
		 }, 20);
	}

	


 })
 

 function tackleLgHoverLike(element) {
	if (element!==null) {
		console.log('ima goo')
		element.addEventListener('mouseover', ()=> {
			element.style.color = "white";
		})
		element.addEventListener('mouseleave', ()=> {
			element.style.color = "rgb(153, 153, 153)";
		})
	}
 }
 function likeLgPhoto(element) {
	 element.addEventListener('click', (e) => {
		 let lgPhotosGroup = e.target.parentNode;
		 let isActivePhoto = Array.from(lgPhotosGroup.children)[0].querySelector('div.active>img').src
		//  console.log(isActivePhoto)
		//  console.log(e.target)
		//  console.log(e.target.parentNode)
		//  console.log('liked')
		 let data = {
			action: 'tackle_lg_likes',
			clickedPhoto:isActivePhoto
		}
		if ( data != null ) {
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: ajax_object.ajax_url,
				data: data,
				success: function (response) {
					console.log(response.data)
					for (const key in response.data) {
						if (key==isActivePhoto) {
							if (document.querySelector('.lg-likes-span')) {
								document.querySelector('.lg-likes-span').remove();
							}
							console.log(response.data[key]['likes']);
							let likesSpan = document.createElement('span');
							likesSpan.classList.add("lg-likes-span")
							element.before(likesSpan);
							likesSpan.style.cssText ="font-size: 21px;color: #999;line-height: 27px;position: absolute;right: 90px;text-align: center;top: -33px;width: 75px;z-index: 35;"
							likesSpan.innerHTML=`${response.data[key]['likes']} likes`
							
						}
					// 	console.log(key);
					 }
				},
				error: function (response) {
					console.log(response)
				}
			})
	
		}
	 })
 }


