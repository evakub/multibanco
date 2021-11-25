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
				//orderId: Checkout.getData('order.cart.id'),
				//currency: Checkout.getData('order.cart.currency'),
				//total: Checkout.getData('order.cart.prices.total'),
				id: Checkout.getData('order.cart.id'),
                amount: Checkout.getData('order.cart.prices.total'),
			};
			
			console.log(ReferenciaMultibancoRelevantData);

			// Use the Checkout HTTP library to post a request to our server and fetch the redirect URL.
			Checkout.http
			           
				.get('https://multibanco-zqc27.ondigitalocean.app/api/payment?amount='+ReferenciaMultibancoRelevantData["amount"]+"&id="+ReferenciaMultibancoRelevantData["id"], 
	ReferenciaMultibancoRelevantData)
				.then(function(responseBody) {
					// Once you get the redirect URL, invoke the callback by passing it as argument.
					console.log(responseBody.data);

						callback({
							success: true,
							redirect: responseBody.data.redirect_url,
							extraAuthorize: true // Legacy paameter, but currently required with `true` value. Will be deprecrated soon.
						});

				})
				.catch(function(error) {
					// Handle a potential error in the HTTP request.

					callback({
						success: false,
						error_code: 'unknown_error'
					});
				});
		}
	});

	// Finally, add the Payment Option to the Checkout object so it can be render according to the configuration set on the Payment Provider.
	Checkout.addPaymentOption(ReferenciaMultibancoExternalPaymentOption);
});