var showpopup = true

function onScroll(){
	if(window.innerWidth < 992) {
		if (document.getElementById('lien-appel')){
			element = document.querySelector('#content-start.blog-single-preview-one')
			lienAppel = document.getElementById('lien-appel')
			closePopup = document.getElementById('close-popup')
			if (element.getBoundingClientRect().top < -300 && showpopup){
				if (lienAppel.classList.contains('unshow')){
					lienAppel.classList.remove('unshow')
					closePopup.classList.remove('d-none')
				}
			} else if (element.getBoundingClientRect().top >= -300 && showpopup){
				if (! lienAppel.classList.contains('unshow')){
					lienAppel.classList.add('unshow')
					closePopup.classList.add('d-none')
				}
			}
		}
		if (showpopup == false && document.querySelector('header').getBoundingClientRect().top > 0) {
			showpopup = true
		}
	} else {
		if (document.querySelector('.sidebar-single-widget.widget_media_image')){
			popup = document.querySelector('.sidebar-single-widget.widget_media_image')
			popupHeight = popup.offsetHeight;
			replace = document.querySelector('.bloc_vide').parentElement;
			if (popup.parentElement.getBoundingClientRect().top <= 50) {
				if (!popup.classList.contains('fixed')) {
					popup.classList.add('fixed')
					popup.style.width = popup.parentElement.offsetWidth + 'px'
					replace.style.display = 'block'
					replace.style.height = popupHeight+'px'
				}
			} else {
				if (popup.classList.contains('fixed')) {
					popup.classList.remove('fixed')
					replace.style.display = 'none'
				}
			}
		}
	}
}

window.addEventListener("scroll", onScroll)

if (document.getElementById('close-popup')) {
	document.getElementById('close-popup').onclick = function(){
		this.classList.add('d-none')
		document.getElementById('lien-appel').classList.add('unshow')
		showpopup = false
	}
}