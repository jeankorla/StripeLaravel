<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Título Aqui</title>
    <!-- Outras tags e estilos -->
</head>
<body>
<h1>Checkout</h1>
<form id="payment-form">
    <div id="card-element">
        <!-- Stripe Elements irá aqui -->
    </div>
    <button id="submit-button">Pagar</button>
</form>
</body>
</html>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(ev) {
        ev.preventDefault();
        stripe.confirmCardPayment('{{ $clientSecret }}', {
            payment_method: {
                card: card,
                // Você pode adicionar detalhes de faturamento aqui se necessário
            }
        }).then(function(result) {
            if (result.error) {
                // Mostrar erro ao cliente
                console.error(result.error.message);
                // Você pode exibir o erro no HTML aqui
            } else {
                if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
                    // Pagamento processado
                    console.log('Pagamento realizado!');
                    // Aqui você pode redirecionar para outra página ou atualizar a interface
                }
            }
        });
    });
</script>
