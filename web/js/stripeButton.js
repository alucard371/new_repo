var handler = StripeCheckout.configure({
    key: 'pk_test_NXKRHAOLVSZXEX39uzgmFjHC',
    image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
    locale: 'auto',
    token: function(token) {
        // You can access the token ID with `token.id`.
        // Get the token ID to your server-side code for use.
        return token.id;
    }
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
    handler.close();
});
