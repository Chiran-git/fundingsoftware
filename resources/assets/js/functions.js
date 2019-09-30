$(document).ready(function(){
	$('#nav-icon').click(function(){
		$(this).toggleClass('nav-icon--open');
		$(this).parent().toggleClass('navbar-toggled-show');
	});

	$('.password-text').hidePassword(true);
	$("#password").passwordValidator({
		// list of qualities to require
		require: [ 'lower', 'special', 'upper', 'length', 'digit',],
		// minimum length requirement
		length: 8
    });
    // if ( $('.list-fixed-head').length ) {
    //     setTimeout(function() {
    //         console.log('test-1');
    //         $('.list-fixed-head > li:first-child li').each(function(){
    //             var $this = $(this);
    //             var txt   = $this.text();
    //             var indx  = $this.index();
    //             $('.list-fixed-head li:nth-child(' + (indx + 1) + ')').attr('data-text', txt);
    //         });
    //     }, 5000);
    // }

    // Load the View Organization iframe content only when View Organization modal is shown
    $('#view_page').on('shown.bs.modal', () => {
        $iframe = $(this).find("iframe");
        // If the iframe is not already loaded, then set its
        // src attribute to load the content
        if (! $iframe.attr("src")) {
            $iframe.prop("src", function() {
                // Set their src attribute to the value of data-src
                return $(this).data("src");
            });
        }
    });
});

// Copied link
$('button').tooltip({
	trigger: 'click',
	placement: 'bottom'
});

function setTooltip(btn, message) {
	$(btn).tooltip('hide')
		.attr('data-original-title', message)
		.tooltip('show');
}

function hideTooltip(btn) {
	setTimeout(function () {
		$(btn).tooltip('hide');
	}, 1000);
}

// Clipboard
var clipboard = new Clipboard('.btn-share, .copy-to-clipboard', {
	text: function (trigger) {
		return trigger.getAttribute("data-href");
	}
});

clipboard.on('success', function (e) {
	setTooltip(e.trigger, 'Link copied to clipboard');
	hideTooltip(e.trigger);
});

// Social icons popup message
$(document).on("click", ".modal-dialog button.popup", function(event) {
	$(".popuptext").toggleClass("show");
});
$('body').click(function() {
	$(".popuptext").removeClass("show");
})

// Organization view social share
$(document).on("click", ".showShareModal", function(event) {
    $(`#${$(this).data('modal-id')}`).addClass("show");
});
$(document).on("click", ".close-share-popup", function(event) {
    $(".modal-share").removeClass("show");
});

$(document).on("click", "button.share-button", function() {
    var shareUrl = $(this).data('share-url') || window.location.href;

    var name = $(this).data('share-windowname') || `rocketjar`;

    var url = '';

    switch ($(this).data('share-button')) {
        case 'facebook':
            var url = `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`;
            break;
        case 'twitter':
            var text = $(this).data('share-text');
            var url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}`;
            break;
        case 'copylink':
            new ClipboardJS('.btn', {
                text: function(trigger) {
                    return trigger.getAttribute('aria-label');
                }
            });
        default:
            break;
    }

    if (url != '') {
        openWindow(url, name);
    }
});

function openWindow(url, name, height, width) {
    var name = name || 'rocketjar';
    var height = height || 400;
    var width = width || 600;
    var leftOffset = ($(window).width() - width) / 2;
    var topOffset  = ($(window).height() - height) / 2;

    var opts = `resizable=yes,scrollbars=yes,height=${height},width=${width},top=${topOffset},left=${leftOffset}`;

    window.open(url, 'rocketjar', opts);
}

// $("a[href^='#']").click(function(e) {
// 	e.preventDefault();

// 	var position = $($(this).attr("href")).offset().top;

// 	$("body, html").animate({
// 		scrollTop: position
// 	} /* speed */ );
// });
