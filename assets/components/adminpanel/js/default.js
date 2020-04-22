AdminPanel = {
    timeout: null,
    panel: jQuery('.adminpanel'),
    close: jQuery('.ap-close'),
    scroll: jQuery('.ap-scroll-up'),

    initialize: function () {
        this.close.on('click touchend', function (e) {
            e.preventDefault();
            if (AdminPanel.panel.hasClass('ap-opened')) {
                AdminPanel.closePanel();
            } else {
                AdminPanel.openPanel();
            }
        });

        jQuery(document).on('mouseenter', '.ap-close, .adminpanel', function () {
            clearTimeout(AdminPanel.timeout);
            AdminPanel.timeout = setTimeout(function () {
                jQuery('.adminpanel').animate({
                    opacity: AdminPanelConfig.active_opacity
                }, 100);
            }, 1);
        });

        jQuery(document).on('mouseleave', '.ap-close, .adminpanel', function () {
            clearTimeout(AdminPanel.timeout);
            AdminPanel.timeout = setTimeout(function () {
                jQuery('.adminpanel').animate({
                    opacity: AdminPanelConfig.inactive_opacity
                }, 100);
            }, 1);
        });

        this.adjustHeight();
        this.panel.css('opacity', AdminPanelConfig.inactive_opacity);
        this.close.css('opacity', AdminPanelConfig.inactive_opacity);

        jQuery(window).on('resize', this.adjustHeight);

        if (this.scroll.length) {
            jQuery(window).on('scroll', function () {
                if (jQuery(this).scrollTop() > 100) {
                    AdminPanel.scroll.fadeIn().css('display', 'block');
                }
                else {
                    AdminPanel.scroll.fadeOut();
                }
            });
            AdminPanel.scroll.on('click', function (e) {
                e.preventDefault();
                jQuery('html, body').animate({scrollTop: 0}, jQuery(window).height());
            });
            jQuery(document).trigger('scroll');
        }
    },


    openPanel: function () {
        this.panel.animate({
            opacity: AdminPanelConfig.active_opacity,
            left: 0,
        }, 300, function () {
            AdminPanel.close.find('.ap-caret').removeClass('ap-closed').addClass('ap-opened');
            AdminPanel.panel.removeClass('ap-closed').addClass('ap-opened');
            Cookies.remove('adminpanel_closed');
            AdminPanel.adjustHeight();
        });
    },

    closePanel: function () {
        this.panel.animate({
            opacity: AdminPanelConfig.inactive_opacity,
            left: '-100%',
        }, 300, function () {
            AdminPanel.close.find('.ap-caret').removeClass('ap-opened').addClass('ap-closed');
            AdminPanel.panel.removeClass('ap-opened').addClass('ap-closed');
            Cookies.set('adminpanel_closed', true, {expires: 365, path: '/'});
            AdminPanel.adjustHeight();
        });
    },

    adjustHeight: function () {
        var height = jQuery('.adminpanel').height();
        if (height) {
            AdminPanel.close.css('height', height)
                .find('.ap-caret').css('bottom', height - (height / 2) - 5);
        }
    }
};

jQuery(document).ready(function () {
    jQuery.getScript(AdminPanelConfig.jsUrl + 'lib/js.cookie-2.1.3.min.js');
    jQuery.getScript(AdminPanelConfig.jsUrl + 'lib/bootstrap.js', function () {
        AdminPanel.initialize();
    });
});
