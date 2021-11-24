// Call 'LoadCheckoutPaymentContext' method and pass a function as parameter to get access to the Checkout context and the PaymentOptions object.

LoadCheckoutPaymentContext(function(Checkout, PaymentOptions) {
  
	// Create a new instance of external Payment Option and set its properties.
	var ReferenciaMultibancoExternalPaymentOption = PaymentOptions.ExternalPayment({
		// Set the option's unique id as it is configured on the Payment Provider so Checkout can relate them.
		id: 'referencia_multibanco_redirect',

    // This parameter renders the billing information form and requires the information to the consumer.
		fields: {
			billing_address: true
		},

		// This function handles the order submission event.
		onSubmit: function(callback) {

			// Gather the minimum required information. You should include all the relevant data here.
			let ReferenciaMultibancoRelevantData = {
				orderId: Checkout.getData('order.cart.id'),
				currency: Checkout.getData('order.cart.currency'),
				total: Checkout.getData('order.cart.prices.total')
			};

			callback({
						success: true,
						redirect: "https://ifthenpay.com/api/gateway/paybylink/EGAS-319193",
						extraAuthorize: true // Legacy paameter, but currently required with `true` value. Will be deprecrated soon.
			});
		}
	});

	// Finally, add the Payment Option to the Checkout object so it can be render according to the configuration set on the Payment Provider.
	Checkout.addPaymentOption(ReferenciaMultibancoExternalPaymentOption);
});

