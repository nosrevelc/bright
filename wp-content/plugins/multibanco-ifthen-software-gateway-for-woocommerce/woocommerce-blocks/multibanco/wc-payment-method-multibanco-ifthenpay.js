!(function (e) {
	var t = {};
	function n(r) {
		if (t[r]) return t[r].exports;
		var c = (t[r] = { i: r, l: !1, exports: {} });
		return e[r].call(c.exports, c, c.exports, n), (c.l = !0), c.exports;
	}
	(n.m = e),
		(n.c = t),
		(n.d = function (e, t, r) {
			n.o(e, t) || Object.defineProperty(e, t, { enumerable: !0, get: r });
		}),
		(n.r = function (e) {
			"undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }), Object.defineProperty(e, "__esModule", { value: !0 });
		}),
		(n.t = function (e, t) {
			if ((1 & t && (e = n(e)), 8 & t)) return e;
			if (4 & t && "object" == typeof e && e && e.__esModule) return e;
			var r = Object.create(null);
			if ((n.r(r), Object.defineProperty(r, "default", { enumerable: !0, value: e }), 2 & t && "string" != typeof e))
				for (var c in e)
					n.d(
						r,
						c,
						function (t) {
							return e[t];
						}.bind(null, c)
					);
			return r;
		}),
		(n.n = function (e) {
			var t =
				e && e.__esModule
					? function () {
						  return e.default;
					  }
					: function () {
						  return e;
					  };
			return n.d(t, "a", t), t;
		}),
		(n.o = function (e, t) {
			return Object.prototype.hasOwnProperty.call(e, t);
		}),
		(n.p = ""),
		n((n.s = 23));
})({
	1: function (e, t) {
		!(function () {
			e.exports = this.wp.i18n;
		})();
	},
	23: function (e, t, n) {
		"use strict";
		n.r(t);
		var r = n(6),
			c = n(1),
			o = n(5),
			i = n(7),
			u = Object(o.getSetting)("multibanco_ifthen_for_woocommerce_data", {}),
			l = Object(c.__)( 'Pagamento de Serviços no Multibanco (IfthenPay)', 'multibanco-ifthen-software-gateway-for-woocommerce' ),
			a = Object(i.decodeEntities)(u.title) || l,
			f = function () {
				return React.createElement("div", null, Object(i.decodeEntities)(u.description || ""));
			},
			s = function (e) {

				var icon = React.createElement( 'img', { src: u.icon, width: 28, height: 24, style: { display: 'inline' } } );
				var span = React.createElement( 'span', { className: 'wc-block-components-payment-method-label wc-block-components-payment-method-label--with-icon' }, icon, a );
				return span;

			},
			d = {
				name: "multibanco_ifthen_for_woocommerce",
				label: React.createElement(s, null),
				//label: <span>{ a }</span>,
				content: React.createElement(f, null),
				edit: React.createElement(f, null),
				icons: null,
				canMakePayment: function ( canPayArgument ) {

					//console.log( 'Multibanco canMakePayment' );
					//console.log( canPayArgument );

					//Euro?
					if ( canPayArgument.cartTotals.currency_code != 'EUR' ) {
						return false;
					}

					//Portugal?
					if ( u.only_portugal && canPayArgument.billingData ) { //canPayArgument.billingData fixed in WooCommerce Blocks 4.7.0
						if ( canPayArgument.billingData.country != 'PT' && canPayArgument.shippingAddress.country != 'PT' ) {
							return false;
						}
					}

					//Minimum and maximum value
					var cart_total = canPayArgument.cartTotals.total_price / 100; //It's return in cents (?)
					if ( u.only_above ) {
						if ( cart_total < u.only_above ) {
							return false;
						}
					}
					if ( u.only_bellow ) {
						if ( cart_total > u.only_bellow ) {
							return false;
						}
					}

					return true;

				},
				ariaLabel: a,
			};
		
		//Our settings on the console
		console.log(u);
		
		Object(r.registerPaymentMethod)(function (e) {
			return new e(d);
		});
	},
	5: function (e, t) {
		!(function () {
			e.exports = this.wc.wcSettings;
		})();
	},
	6: function (e, t) {
		!(function () {
			e.exports = this.wc.wcBlocksRegistry;
		})();
	},
	7: function (e, t) {
		!(function () {
			e.exports = this.wp.htmlEntities;
		})();
	},
});
