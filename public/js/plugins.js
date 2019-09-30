function setTooltip(t,e){$(t).tooltip("hide").attr("data-original-title",e).tooltip("show")}function hideTooltip(t){setTimeout(function(){$(t).tooltip("hide")},1e3)}!function(t){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=t();else if("function"==typeof define&&define.amd)define([],t);else{("undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this).Clipboard=t()}}(function(){return function t(e,n,i){function o(s,a){if(!n[s]){if(!e[s]){var l="function"==typeof require&&require;if(!a&&l)return l(s,!0);if(r)return r(s,!0);var c=new Error("Cannot find module '"+s+"'");throw c.code="MODULE_NOT_FOUND",c}var h=n[s]={exports:{}};e[s][0].call(h.exports,function(t){var n=e[s][1][t];return o(n||t)},h,h.exports,t,e,n,i)}return n[s].exports}for(var r="function"==typeof require&&require,s=0;s<i.length;s++)o(i[s]);return o}({1:[function(t,e,n){var i=t("matches-selector");e.exports=function(t,e,n){for(var o=n?t:t.parentNode;o&&o!==document;){if(i(o,e))return o;o=o.parentNode}}},{"matches-selector":5}],2:[function(t,e,n){function i(t,e,n,i){return function(n){n.delegateTarget=o(n.target,e,!0),n.delegateTarget&&i.call(t,n)}}var o=t("closest");e.exports=function(t,e,n,o,r){var s=i.apply(this,arguments);return t.addEventListener(n,s,r),{destroy:function(){t.removeEventListener(n,s,r)}}}},{closest:1}],3:[function(t,e,n){n.node=function(t){return void 0!==t&&t instanceof HTMLElement&&1===t.nodeType},n.nodeList=function(t){var e=Object.prototype.toString.call(t);return void 0!==t&&("[object NodeList]"===e||"[object HTMLCollection]"===e)&&"length"in t&&(0===t.length||n.node(t[0]))},n.string=function(t){return"string"==typeof t||t instanceof String},n.fn=function(t){return"[object Function]"===Object.prototype.toString.call(t)}},{}],4:[function(t,e,n){var i=t("./is"),o=t("delegate");e.exports=function(t,e,n){if(!t&&!e&&!n)throw new Error("Missing required arguments");if(!i.string(e))throw new TypeError("Second argument must be a String");if(!i.fn(n))throw new TypeError("Third argument must be a Function");if(i.node(t))return function(t,e,n){return t.addEventListener(e,n),{destroy:function(){t.removeEventListener(e,n)}}}(t,e,n);if(i.nodeList(t))return function(t,e,n){return Array.prototype.forEach.call(t,function(t){t.addEventListener(e,n)}),{destroy:function(){Array.prototype.forEach.call(t,function(t){t.removeEventListener(e,n)})}}}(t,e,n);if(i.string(t))return function(t,e,n){return o(document.body,t,e,n)}(t,e,n);throw new TypeError("First argument must be a String, HTMLElement, HTMLCollection, or NodeList")}},{"./is":3,delegate:2}],5:[function(t,e,n){var i=Element.prototype,o=i.matchesSelector||i.webkitMatchesSelector||i.mozMatchesSelector||i.msMatchesSelector||i.oMatchesSelector;e.exports=function(t,e){if(o)return o.call(t,e);for(var n=t.parentNode.querySelectorAll(e),i=0;i<n.length;++i)if(n[i]==t)return!0;return!1}},{}],6:[function(t,e,n){e.exports=function(t){var e;if("INPUT"===t.nodeName||"TEXTAREA"===t.nodeName)t.focus(),t.setSelectionRange(0,t.value.length),e=t.value;else{t.hasAttribute("contenteditable")&&t.focus();var n=window.getSelection(),i=document.createRange();i.selectNodeContents(t),n.removeAllRanges(),n.addRange(i),e=n.toString()}return e}},{}],7:[function(t,e,n){function i(){}i.prototype={on:function(t,e,n){var i=this.e||(this.e={});return(i[t]||(i[t]=[])).push({fn:e,ctx:n}),this},once:function(t,e,n){function i(){o.off(t,i),e.apply(n,arguments)}var o=this;return i._=e,this.on(t,i,n)},emit:function(t){for(var e=[].slice.call(arguments,1),n=((this.e||(this.e={}))[t]||[]).slice(),i=0,o=n.length;o>i;i++)n[i].fn.apply(n[i].ctx,e);return this},off:function(t,e){var n=this.e||(this.e={}),i=n[t],o=[];if(i&&e)for(var r=0,s=i.length;s>r;r++)i[r].fn!==e&&i[r].fn._!==e&&o.push(i[r]);return o.length?n[t]=o:delete n[t],this}},e.exports=i},{}],8:[function(t,e,n){!function(i,o){if(void 0!==n)o(e,t("select"));else{var r={exports:{}};o(r,i.select),i.clipboardAction=r.exports}}(this,function(t,e){"use strict";var n=function(t){return t&&t.__esModule?t:{default:t}}(e),i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t},o=function(){function t(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,n,i){return n&&t(e.prototype,n),i&&t(e,i),e}}(),r=function(){function t(e){(function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")})(this,t),this.resolveOptions(e),this.initSelection()}return t.prototype.resolveOptions=function(){var t=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];this.action=t.action,this.emitter=t.emitter,this.target=t.target,this.text=t.text,this.trigger=t.trigger,this.selectedText=""},t.prototype.initSelection=function(){this.text?this.selectFake():this.target&&this.selectTarget()},t.prototype.selectFake=function(){var t=this,e="rtl"==document.documentElement.getAttribute("dir");this.removeFake(),this.fakeHandler=document.body.addEventListener("click",function(){return t.removeFake()}),this.fakeElem=document.createElement("textarea"),this.fakeElem.style.fontSize="12pt",this.fakeElem.style.border="0",this.fakeElem.style.padding="0",this.fakeElem.style.margin="0",this.fakeElem.style.position="fixed",this.fakeElem.style[e?"right":"left"]="-9999px",this.fakeElem.style.top=(window.pageYOffset||document.documentElement.scrollTop)+"px",this.fakeElem.setAttribute("readonly",""),this.fakeElem.value=this.text,document.body.appendChild(this.fakeElem),this.selectedText=(0,n.default)(this.fakeElem),this.copyText()},t.prototype.removeFake=function(){this.fakeHandler&&(document.body.removeEventListener("click"),this.fakeHandler=null),this.fakeElem&&(document.body.removeChild(this.fakeElem),this.fakeElem=null)},t.prototype.selectTarget=function(){this.selectedText=(0,n.default)(this.target),this.copyText()},t.prototype.copyText=function(){var t=void 0;try{t=document.execCommand(this.action)}catch(e){t=!1}this.handleResult(t)},t.prototype.handleResult=function(t){t?this.emitter.emit("success",{action:this.action,text:this.selectedText,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)}):this.emitter.emit("error",{action:this.action,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)})},t.prototype.clearSelection=function(){this.target&&this.target.blur(),window.getSelection().removeAllRanges()},t.prototype.destroy=function(){this.removeFake()},o(t,[{key:"action",set:function(){var t=arguments.length<=0||void 0===arguments[0]?"copy":arguments[0];if(this._action=t,"copy"!==this._action&&"cut"!==this._action)throw new Error('Invalid "action" value, use either "copy" or "cut"')},get:function(){return this._action}},{key:"target",set:function(t){if(void 0!==t){if(!t||"object"!==(void 0===t?"undefined":i(t))||1!==t.nodeType)throw new Error('Invalid "target" value, use a valid Element');if("copy"===this.action&&t.hasAttribute("disabled"))throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');if("cut"===this.action&&(t.hasAttribute("readonly")||t.hasAttribute("disabled")))throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');this._target=t}},get:function(){return this._target}}]),t}();t.exports=r})},{select:6}],9:[function(t,e,n){!function(i,o){if(void 0!==n)o(e,t("./clipboard-action"),t("tiny-emitter"),t("good-listener"));else{var r={exports:{}};o(r,i.clipboardAction,i.tinyEmitter,i.goodListener),i.clipboard=r.exports}}(this,function(t,e,n,i){"use strict";function o(t){return t&&t.__esModule?t:{default:t}}function r(t,e){var n="data-clipboard-"+t;if(e.hasAttribute(n))return e.getAttribute(n)}var s=o(e),a=o(n),l=o(i),c=function(t){function e(n,i){!function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,e);var o=function(t,e){if(!t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?t:e}(this,t.call(this));return o.resolveOptions(i),o.listenClick(n),o}return function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)}(e,t),e.prototype.resolveOptions=function(){var t=arguments.length<=0||void 0===arguments[0]?{}:arguments[0];this.action="function"==typeof t.action?t.action:this.defaultAction,this.target="function"==typeof t.target?t.target:this.defaultTarget,this.text="function"==typeof t.text?t.text:this.defaultText},e.prototype.listenClick=function(t){var e=this;this.listener=(0,l.default)(t,"click",function(t){return e.onClick(t)})},e.prototype.onClick=function(t){var e=t.delegateTarget||t.currentTarget;this.clipboardAction&&(this.clipboardAction=null),this.clipboardAction=new s.default({action:this.action(e),target:this.target(e),text:this.text(e),trigger:e,emitter:this})},e.prototype.defaultAction=function(t){return r("action",t)},e.prototype.defaultTarget=function(t){var e=r("target",t);return e?document.querySelector(e):void 0},e.prototype.defaultText=function(t){return r("text",t)},e.prototype.destroy=function(){this.listener.destroy(),this.clipboardAction&&(this.clipboardAction.destroy(),this.clipboardAction=null)},e}(a.default);t.exports=c})},{"./clipboard-action":8,"good-listener":4,"tiny-emitter":7}]},{},[9])(9)}),$(document).ready(function(){$("#nav-icon").click(function(){$(this).toggleClass("nav-icon--open"),$(this).parent().toggleClass("navbar-toggled-show")}),$(".password-text").hidePassword(!0),$("#password").passwordValidator({require:["lower","special","upper","length","digit"],length:8}),$("#view_page").on("shown.bs.modal",()=>{$iframe=$(this).find("iframe"),$iframe.attr("src")||$iframe.prop("src",function(){return $(this).data("src")})})}),$("button").tooltip({trigger:"click",placement:"bottom"});var clipboard=new Clipboard(".btn-share, .copy-to-clipboard",{text:function(t){return t.getAttribute("data-href")}});function openWindow(t,e,n,i){n=n||400,i=i||600;var o=($(window).width()-i)/2,r=`resizable=yes,scrollbars=yes,height=${n},width=${i},top=${($(window).height()-n)/2},left=${o}`;window.open(t,"rocketjar",r)}clipboard.on("success",function(t){setTooltip(t.trigger,"Link copied to clipboard"),hideTooltip(t.trigger)}),$(document).on("click",".modal-dialog button.popup",function(t){$(".popuptext").toggleClass("show")}),$("body").click(function(){$(".popuptext").removeClass("show")}),$(document).on("click",".showShareModal",function(t){$(`#${$(this).data("modal-id")}`).addClass("show")}),$(document).on("click",".close-share-popup",function(t){$(".modal-share").removeClass("show")}),$(document).on("click","button.share-button",function(){var t=$(this).data("share-url")||window.location.href,e=$(this).data("share-windowname")||"rocketjar",n="";switch($(this).data("share-button")){case"facebook":n=`https://www.facebook.com/sharer/sharer.php?u=${t}`;break;case"twitter":var i=$(this).data("share-text");n=`https://twitter.com/intent/tweet?text=${encodeURIComponent(i)}`;break;case"copylink":new ClipboardJS(".btn",{text:function(t){return t.getAttribute("aria-label")}})}""!=n&&openWindow(n,e)}),function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports?t(require("jquery")):t(jQuery)}(function(t,e){var n="plugin_hideShowPassword",i=["show","innerToggle"],o={show:"infer",innerToggle:!1,enable:function(){var t=document.body,e=document.createElement("input"),n=!0;t||(t=document.createElement("body")),e=t.appendChild(e);try{e.setAttribute("type","text")}catch(t){n=!1}return t.removeChild(e),n}(),triggerOnToggle:!1,className:"hideShowPassword-field",initEvent:"hideShowPasswordInit",changeEvent:"passwordVisibilityChange",props:{autocapitalize:"off",autocomplete:"off",autocorrect:"off",spellcheck:"false"},toggle:{element:'<button type="button">',className:"hideShowPassword-toggle",touchSupport:"undefined"!=typeof Modernizr&&Modernizr.touchevents,attachToEvent:"click.hideShowPassword",attachToTouchEvent:"touchstart.hideShowPassword mousedown.hideShowPassword",attachToKeyEvent:"keyup",attachToKeyCodes:!0,styles:{position:"absolute"},touchStyles:{pointerEvents:"none"},position:"infer",verticalAlign:"middle",offset:0,attr:{role:"button","aria-label":"Show Password",title:"Show Password",tabIndex:0}},wrapper:{element:"<div>",className:"hideShowPassword-wrapper",enforceWidth:!0,styles:{position:"relative"},inheritStyles:["display","verticalAlign","marginTop","marginRight","marginBottom","marginLeft"],innerElementStyles:{marginTop:0,marginRight:0,marginBottom:0,marginLeft:0}},states:{shown:{className:"hideShowPassword-shown",changeEvent:"passwordShown",props:{type:"text"},toggle:{className:"hideShowPassword-toggle-hide",content:"Hide",attr:{"aria-pressed":"true",title:"Hide Password"}}},hidden:{className:"hideShowPassword-hidden",changeEvent:"passwordHidden",props:{type:"password"},toggle:{className:"hideShowPassword-toggle-show",content:"Show",attr:{"aria-pressed":"false",title:"Show Password"}}}}};function r(e,n){this.element=t(e),this.wrapperElement=t(),this.toggleElement=t(),this.init(n)}r.prototype={init:function(e){this.update(e,o)&&(this.element.addClass(this.options.className),this.options.innerToggle&&(this.wrapElement(this.options.wrapper),this.initToggle(this.options.toggle),"string"==typeof this.options.innerToggle&&(this.toggleElement.hide(),this.element.one(this.options.innerToggle,t.proxy(function(){this.toggleElement.show()},this)))),this.element.trigger(this.options.initEvent,[this]))},update:function(t,e){return this.options=this.prepareOptions(t,e),this.updateElement()&&this.element.trigger(this.options.changeEvent,[this]).trigger(this.state().changeEvent,[this]),this.options.enable},toggle:function(t){return t=t||"toggle",this.update({show:t})},prepareOptions:function(e,n){var i,o=e||{},r=[];if(n=n||this.options,e=t.extend(!0,{},n,e),o.hasOwnProperty("wrapper")&&o.wrapper.hasOwnProperty("inheritStyles")&&(e.wrapper.inheritStyles=o.wrapper.inheritStyles),e.enable&&("toggle"===e.show?e.show=this.isType("hidden",e.states):"infer"===e.show&&(e.show=this.isType("shown",e.states)),"infer"===e.toggle.position&&(e.toggle.position="rtl"===this.element.css("text-direction")?"left":"right"),!t.isArray(e.toggle.attachToKeyCodes))){if(!0===e.toggle.attachToKeyCodes)switch((i=t(e.toggle.element)).prop("tagName").toLowerCase()){case"button":case"input":break;case"a":if(i.filter("[href]").length){r.push(32);break}default:r.push(32,13)}e.toggle.attachToKeyCodes=r}return e},updateElement:function(){return!(!this.options.enable||this.isType())&&(this.element.prop(t.extend({},this.options.props,this.state().props)).addClass(this.state().className).removeClass(this.otherState().className),this.options.triggerOnToggle&&this.element.trigger(this.options.triggerOnToggle,[this]),this.updateToggle(),!0)},isType:function(t,n){return(n=n||this.options.states)[t=t||this.state(e,e,n).props.type]&&(t=n[t].props.type),this.element.prop("type")===t},state:function(t,n,i){return i=i||this.options.states,t===e&&(t=this.options.show),"boolean"==typeof t&&(t=t?"shown":"hidden"),n&&(t="shown"===t?"hidden":"shown"),i[t]},otherState:function(t){return this.state(t,!0)},wrapElement:function(e){var n,i=e.enforceWidth;return this.wrapperElement.length||(n=this.element.outerWidth(),t.each(e.inheritStyles,t.proxy(function(t,n){e.styles[n]=this.element.css(n)},this)),this.element.css(e.innerElementStyles).wrap(t(e.element).addClass(e.className).css(e.styles)),this.wrapperElement=this.element.parent(),!0===i&&(i=this.wrapperElement.outerWidth()!==n&&n),!1!==i&&this.wrapperElement.css("width",i)),this.wrapperElement},initToggle:function(e){return this.toggleElement.length||(this.toggleElement=t(e.element).attr(e.attr).addClass(e.className).css(e.styles).appendTo(this.wrapperElement),this.updateToggle(),this.positionToggle(e.position,e.verticalAlign,e.offset),e.touchSupport?(this.toggleElement.css(e.touchStyles),this.element.on(e.attachToTouchEvent,t.proxy(this.toggleTouchEvent,this))):this.toggleElement.on(e.attachToEvent,t.proxy(this.toggleEvent,this)),e.attachToKeyCodes.length&&this.toggleElement.on(e.attachToKeyEvent,t.proxy(this.toggleKeyEvent,this))),this.toggleElement},positionToggle:function(t,e,n){var i={};switch(i[t]=n,e){case"top":case"bottom":i[e]=n;break;case"middle":i.top="50%",i.marginTop=this.toggleElement.outerHeight()/-2}return this.toggleElement.css(i)},updateToggle:function(t,e){var n,i;return this.toggleElement.length&&(n="padding-"+this.options.toggle.position,t=t||this.state().toggle,e=e||this.otherState().toggle,this.toggleElement.attr(t.attr).addClass(t.className).removeClass(e.className).html(t.content),i=this.toggleElement.outerWidth()+2*this.options.toggle.offset,this.element.css(n)!==i&&this.element.css(n,i)),this.toggleElement},toggleEvent:function(t){t.preventDefault(),this.toggle()},toggleKeyEvent:function(e){t.each(this.options.toggle.attachToKeyCodes,t.proxy(function(t,n){if(e.which===n)return this.toggleEvent(e),!1},this))},toggleTouchEvent:function(t){var e,n,i,o=this.toggleElement.offset().left;o&&(e=t.pageX||t.originalEvent.pageX,"left"===this.options.toggle.position?(n=e,i=o+=this.toggleElement.outerWidth()):(n=o,i=e),i>=n&&this.toggleEvent(t))}},t.fn.hideShowPassword=function(){var e={};return t.each(arguments,function(n,o){var r={};if("object"==typeof o)r=o;else{if(!i[n])return!1;r[i[n]]=o}t.extend(!0,e,r)}),this.each(function(){var i=t(this),o=i.data(n);o?o.update(e):i.data(n,new r(this,e))})},t.each({show:!0,hide:!1,toggle:"toggle"},function(e,n){t.fn[e+"Password"]=function(t,e){return this.hideShowPassword(n,t,e)}})}),this.JST=this.JST||{},this.JST.input_wrapper=function(obj){obj||(obj={});var __t,__p="",__e=_.escape;with(obj)__p+='<div class="jq-password-validator">\n';return __p},this.JST.length=function(obj){obj||(obj={});var __t,__p="",__e=_.escape;with(obj)__p+='<div class="jq-password-validator__rule is-valid length">\n\tBe at least'+(null==(__t=length)?"":__t)+" characters minimum\n</div>\n";return __p},this.JST.popover=function(obj){obj||(obj={});var __t,__p="",__e=_.escape;with(obj)__p+='<div class="jq-password-validator__popover">\n\t<header></header>\n</div>\n';return __p},this.JST.row=function(obj){obj||(obj={});var __t,__p="",__e=_.escape;with(obj)__p+='<div class="jq-password-validator__rule '+(null==(__t=ruleName)?"":__t)+'">\n\t<svg xmlns="http://www.w3.org/2000/svg" class="jq-password-validator__checkmark" viewBox="0 0 8 8">\n\t  <path d="M6.41 0l-.69.72-2.78 2.78-.81-.78-.72-.72-1.41 1.41.72.72 1.5 1.5.69.72.72-.72 3.5-3.5.72-.72-1.44-1.41z" transform="translate(0 1)" />\n\t</svg>\n\t'+(null==(__t=preface)?"":__t)+"\n\t"+(null==(__t=message)?"":__t)+"\n</div>\n";return __p},function(t,e,n,i){"use strict";var o="passwordValidator",r={length:12,require:["length","lower","upper","digit"]};function s(e,n){this.element=e,this.settings=t.extend({},r,n),this._defaults=r,this._name=o,this.init()}var a={upper:{validate:function(t){return null!=t.match(/[A-Z]/)},message:"uppercase letter"},lower:{validate:function(t){return null!=t.match(/[a-z]/)},message:"lowercase letter"},digit:{validate:function(t){return null!=t.match(/\d/)},message:"number"},length:{validate:function(t,e){return t.length>=e.length},message:function(t){return t.length+" characters minimum"},preface:" "},special:{validate:function(t){return null!=t.match(/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/)},message:"special character"}};t.extend(s.prototype,{init:function(){this.wrapInput(this.element),this.inputWrapper.append(this.buildUi()),this.bindBehavior()},wrapInput:function(e){return t(".password-messages").append(JST.input_wrapper()),this.inputWrapper=t(".jq-password-validator"),this.inputWrapper},buildUi:function(){var e=t(JST.popover()),n=this;return _.each(this.settings.require,function(i){var o;o=a[i].message instanceof Function?a[i].message(n.settings):a[i].message;var r=a[i].preface||"One",s=JST.row({ruleName:i,message:o,preface:r});e.append(t(s))}),this.ui=e,e.hide(),e},bindBehavior:function(){var e=this;t(this.element).on("focus",function(){e.validate(),e.showUi()}),t(this.element).on("blur",function(){e.hideUi()}),t(this.element).on("keyup",function(){e.validate()})},showUi:function(){this.ui.show(),t(this.element).parent().removeClass("is-hidden"),t(this.element).parent().addClass("is-visible")},hideUi:function(){this.ui.hide(),t(this.element).parent().removeClass("is-visible"),t(this.element).parent().addClass("is-hidden")},validate:function(){var e=t(this.element).val(),n=this;_.each(this.settings.require,function(t){a[t].validate(e,n.settings)?n.markRuleValid(t):n.markRuleInvalid(t)})},markRuleValid:function(t){var e=this.ui.find("."+t);e.addClass("is-valid"),e.removeClass("is-invalid")},markRuleInvalid:function(t){var e=this.ui.find("."+t);e.removeClass("is-valid"),e.addClass("is-invalid")}}),t.fn[o]=function(e){return this.each(function(){t.data(this,"plugin_"+o)||t.data(this,"plugin_"+o,new s(this,e))})}}(jQuery,window,document);
