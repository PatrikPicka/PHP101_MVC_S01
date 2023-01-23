$(document).ready(() => {
	/**Scroll down button**/
	$('.btn-showmore').click(function () {
		let offset = $('#about-us').offset().top;

		$('html').animate({scrollTop: offset}, 700);
	});
});