$(document).ready(function () {
		$("#m-menu").click(function (e) {
            $("#menu").toggleClass("active");
        });
		$(function() {
			function reposition() {
				var modal = $(this),
					dialog = modal.find('.modal-dialog');
				modal.css('display', 'block');
				dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 3));
			}
			$('.modal').on('show.bs.modal', reposition);
			$(window).on('resize', function() {
				$('.modal:visible').each(reposition);
			});
		});
    });