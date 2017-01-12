/* ========================================================================
 * Bootstrap: ap_dropdown.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#ap_dropdowns
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) {
    "use strict";

    // DROPDOWN CLASS DEFINITION
    // =========================

    var backdrop = '.ap_dropdown-backdrop';
    var toggle = '[data-toggle=ap_dropdown]';
    var Dropdown = function (element) {
        var $el = $(element).on('click.bs.ap_dropdown', this.toggle)
    };

    Dropdown.prototype.toggle = function (e) {
        var $this = $(this);

        if ($this.is('.disabled, :disabled')) return;

        var $parent = getParent($this);
        var isActive = $parent.hasClass('ap-open');

        clearMenus();

        if (!isActive) {
            if ('ontouchstart' in document.documentElement) {
                // if mobile we we use a backdrop because click events don't delegate
                $('<div class="ap_dropdown-backdrop"/>').insertAfter($(this)).on('click', clearMenus)
            }

            $parent.trigger(e = $.Event('show.bs.ap_dropdown'));

            if (e.isDefaultPrevented()) return;

            $parent
                .toggleClass('ap-open')
                .trigger('shown.bs.ap_dropdown')
        }

        $this.focus();

        return false
    };

    Dropdown.prototype.keydown = function (e) {
        if (!/(38|40|27)/.test(e.keyCode)) return;

        var $this = $(this);

        e.preventDefault();
        e.stopPropagation();

        if ($this.is('.disabled, :disabled')) return;

        var $parent = getParent($this);
        var isActive = $parent.hasClass('ap-open');

        if (!isActive || (isActive && e.keyCode == 27)) {
            if (e.which == 27) $parent.find(toggle).focus();
            return $this.click()
        }

        var $items = $('[role=menu] li:not(.divider):visible a', $parent);

        if (!$items.length) return;

        var index = $items.index($items.filter(':focus'));

        if (e.keyCode == 38 && index > 0) index--;                        // up
        if (e.keyCode == 40 && index < $items.length - 1) index++;                        // down
        if (!~index) index = 0;

        $items.eq(index).focus()
    };

    function clearMenus() {
        $(backdrop).remove();
        $(toggle).each(function (e) {
            var $parent = getParent($(this));
            if (!$parent.hasClass('ap-open')) return;
            $parent.trigger(e = $.Event('hide.bs.ap_dropdown'));
            if (e.isDefaultPrevented()) return;
            $parent.removeClass('ap-open').trigger('hidden.bs.ap_dropdown')
        })
    }

    function getParent($this) {
        var selector = $this.attr('data-target');

        if (!selector) {
            selector = $this.attr('href');
            selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, ''); //strip for ie7
        }

        var $parent = selector && $(selector);

        return $parent && $parent.length ? $parent : $this.parent()
    }


    // DROPDOWN PLUGIN DEFINITION
    // ==========================

    var old = $.fn.ap_dropdown;

    $.fn.ap_dropdown = function (option) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('ap_dropdown');

            if (!data) $this.data('ap_dropdown', (data = new Dropdown(this)));
            if (typeof option == 'string') data[option].call($this)
        })
    };

    $.fn.ap_dropdown.Constructor = Dropdown;


    // DROPDOWN NO CONFLICT
    // ====================

    $.fn.ap_dropdown.noConflict = function () {
        $.fn.ap_dropdown = old;
        return this
    };


    // APPLY TO STANDARD DROPDOWN ELEMENTS
    // ===================================

    $(document)
        .on('click.bs.ap_dropdown.data-api', clearMenus)
        .on('click.bs.ap_dropdown.data-api', '.ap_dropdown form', function (e) {
            e.stopPropagation()
        })
        .on('click.bs.ap_dropdown.data-api', toggle, Dropdown.prototype.toggle)
        .on('keydown.bs.ap_dropdown.data-api', toggle + ', [role=menu]', Dropdown.prototype.keydown)

}(window.jQuery);

/* ========================================================================
 * Bootstrap: transition.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#transitions
 * ========================================================================
 * Copyright 2013 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */


+function ($) {
    "use strict";

    // CSS TRANSITION SUPPORT (Shoutout: http://www.modernizr.com/)
    // ============================================================

    function transitionEnd() {
        var el = document.createElement('bootstrap');

        var transEndEventNames = {
            'WebkitTransition': 'webkitTransitionEnd'
            , 'MozTransition': 'transitionend'
            , 'OTransition': 'oTransitionEnd otransitionend'
            , 'transition': 'transitionend'
        };

        for (var name in transEndEventNames) {
            if (el.style[name] !== undefined) {
                return {end: transEndEventNames[name]}
            }
        }
    }

    // http://blog.alexmaccaw.com/css-transitions
    $.fn.emulateTransitionEnd = function (duration) {
        var called = false, $el = this;
        $(this).one($.support.transition.end, function () {
            called = true
        });
        var callback = function () {
            if (!called) $($el).trigger($.support.transition.end)
        };
        setTimeout(callback, duration);
        return this
    };

    $(function () {
        $.support.transition = transitionEnd()
    })

}(window.jQuery);
