// This is client.js

// Replace with your server URL where '/create-payment-intent' endpoint is exposed
const serverUrl = 'http://localhost:3000/create-payment-intent';

// Create a Stripe client
const stripe = Stripe('your_stripe_public_key');

// Create an instance of Elements
const elements = stripe.elements();

// Create an instance of the card Element
const card = elements.create('card');

// Add an instance of the card Element into the `card-element` div
card.mount('#card-element');

// Handle form submission
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Call your server to create a payment intent
    const { amount } = form.elements;
    const response = await fetch(serverUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            amount: amount.value,
        }),
    });

    const data = await response.json();

    // Use the client secret to confirm the payment on the client side
    const result = await stripe.confirmCardPayment(data.clientSecret, {
        payment_method: {
            card: card,
        }
    });

    if (result.error) {
        console.error(result.error.message);
        alert('Payment failed. Please try again.');
    } else {
        alert('Payment successful!');
    }
});
