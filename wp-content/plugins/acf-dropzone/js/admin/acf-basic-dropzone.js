!function(){var e={};(function(n){(function(){"use strict";var a,o=(a="undefined"!=typeof window?window.jQuery:void 0!==n?n.jQuery:null)&&a.__esModule?a:{default:a};e=function e(n){var a=n.el;!function(n,a){if(!(n instanceof e))throw new TypeError("Cannot call a class as a function")}(this);var t=(0,o.default)(a).find('[type="file"]').get(0);(0,o.default)(a).on("dragover",function(e){(0,o.default)(a).addClass("drag-over"),e.preventDefault()}).on("dragleave",function(e){(0,o.default)(a).removeClass("drag-over")}).on("drop",function(e){(0,o.default)(a).removeClass("drag-over"),e.preventDefault(),t.files=e.originalEvent.dataTransfer.files})}}).call(this)}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{});var n,a=(n=e)&&n.__esModule?n:{default:n},o=function(e){var n;e.$el.is(".dropzone")&&(e.parent(),(n=e.$('[data-uploader="basic"]').get(0))&&new a.default({el:n,field:e}))};acf_dropzone.file_fields.forEach(function(e){acf.addAction("ready_field/type=".concat(e),o),acf.addAction("append_field/type=".concat(e),o)})}();