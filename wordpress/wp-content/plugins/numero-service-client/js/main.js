jQuery(document).ready(function($){
	var img = $("img[src='https://i0.wp.com/suivre-mon-colis.be/wp-content/uploads/2020/10/090755721-scaled.jpg?w=525']");
	var imgSMC = $("img[src='https://i1.wp.com/suivremacommande.fr/wp-content/uploads/2020/04/bouton_APPELER_SUIVRE-MA-COMMANE-0893033341.jpg?w=525&ssl=1']");
	var img2 = $("img[src='https://i0.wp.com/contacter.be/wp-content/uploads/2021/07/contacter-comment-contacter-le-service-client-de.jpg?w=525&ssl=1");
	// var link = img.parent();
	img.attr("src","https://i2.wp.com/suivre-mon-colis.be/wp-content/uploads/2022/02/bouton_APPELER_belgique-color-cropped.png?resize=768%2C494&amp;ssl=1");
	img.attr("alt","contacter le service");
	img.attr("lazy","loading");
	imgSMC.attr("src","https://i2.wp.com/suivre-mon-colis.be/wp-content/uploads/2022/02/bouton_APPELER_belgique-color-cropped.png?resize=768%2C494&amp;ssl=1");
	imgSMC.attr("alt","contacter le service");
	imgSMC.attr("lazy","loading");
	img2.attr("src","https://i2.wp.com/suivre-mon-colis.be/wp-content/uploads/2022/02/bouton_APPELER_belgique-color-cropped.png?resize=768%2C494&amp;ssl=1");
	img2.attr("alt","contacter le service");
	img2.attr("lazy","loading");
	// link.removeAttr("href");
});