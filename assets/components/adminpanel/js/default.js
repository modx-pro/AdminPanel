AdminPanel = {
	timeout: null
	,initialize: function () {

		$(document).on('click touchend', '.ap-close', function() {
			var close = $(this);
			var panel = $('.adminpanel');

			if (panel.is(':visible')) {
				panel.animate({
					opacity: AdminPanelConfig.inactive_opacity
					,left: "-100%"
					,right: "100%"
				}, 300, function() {
					panel.hide();
					close.find('.ap-caret').removeClass('ap-opened').addClass('ap-closed');
					$.cookie('adminpanel_closed', 1);
				});
			}
			else {
				panel.show().animate({
					opacity: AdminPanelConfig.active_opacity
					,left: 0
					,right: 0
				}, 300, function() {
					close.find('.ap-caret').removeClass('ap-closed').addClass('ap-opened');
					$.cookie('adminpanel_closed', 0);
				});
			}
			return false;
		});

		$(document).on('mouseenter', '.ap-close, .adminpanel', function() {
			clearTimeout(AdminPanel.timeout);
			AdminPanel.timeout = setTimeout(function() {
				$('.adminpanel').animate({
					opacity: AdminPanelConfig.active_opacity
				}, 100);
			}, 1);
		});
		$(document).on('mouseleave', '.ap-close, .adminpanel', function() {
			clearTimeout(AdminPanel.timeout);
			AdminPanel.timeout = setTimeout(function() {
				$('.adminpanel').animate({
					opacity: AdminPanelConfig.inactive_opacity
				}, 100);
			}, 1);
		});
	}
};

$(document).ready(function () {
	$.getScript(AdminPanelConfig.jsUrl + 'lib/jquery.cookie.min.js');
	$.getScript(AdminPanelConfig.jsUrl + 'lib/bootstrap.js', function() {
		AdminPanel.initialize();
	});
});