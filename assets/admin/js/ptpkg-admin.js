!function(t){var e={};function n(a){if(e[a])return e[a].exports;var r=e[a]={i:a,l:!1,exports:{}};return t[a].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=t,n.c=e,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:a})},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/",n(n.s=275)}({275:function(t,e,n){n(276),n(277),n(281),t.exports=n(282)},276:function(t,e){!function(t){"use strict";t(function(){var e;t.fn.upload_banner_image=function(n){var a=n.attr("id"),r=a.replace("_button","");e?e.open():((e=wp.media.frames.file_frame=wp.media({title:t(this).data("uploader_title"),button:{text:t(this).data("uploader_button_text")},multiple:!1})).on("select",function(){var n=e.state().get("selection").first().toJSON();t("#"+r).val(n.id),t("#package-meta img").attr("src",n.url),t("#package-meta img").show(),t("#"+a).attr("id","remove_banner_image_button"),t("#remove_banner_image_button").text("Remove banner image")}),e.open())},t("#package-meta").on("click","#upload_banner_image_button",function(e){e.preventDefault(),t.fn.upload_banner_image(t(this))}),t("#package-meta").on("click","#remove_banner_image_button",function(e){e.preventDefault(),t("#upload_banner_image").val(""),t("#package-meta img").attr("src",""),t("#package-meta img").hide(),t(this).attr("id","upload_banner_image_button"),t("#upload_banner_image_button").text("Set banner image")})})}(jQuery)},277:function(t,e){},281:function(t,e){},282:function(t,e){}});