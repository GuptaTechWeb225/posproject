<template>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <form :action="action_url" method="post" id="payment-form">
                    <div class="form-group">
                        <div class="card-header">
                            {{ $t('enter_credit_card_information') }}
                        </div>
                        <div class="card-body">
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                            <input type="hidden" name="plan" value=""/>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button
                            id="card-button"
                            class="btn btn-dark"
                            type="submit"
                        > {{ $t('make_stripe_payment') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import {STRIPE_ACTION_URL} from "../../../Config/ApiUrl-CPB";

export default {
    name: "Stripe",
    props: ['intent', 'publicKey'],
    data() {
        return {
            action_url: STRIPE_ACTION_URL,
            stripe: '',
            elements: '',
            card: ''
        }
    },
    created() {
        this.includeStripe('js.stripe.com/v3/', function () {
            this.configureStripe();
        }.bind(this));
    },

    methods: {
        includeStripe(URL, callback) {
            let documentTag = document, tag = 'script',
                object = documentTag.createElement(tag),
                scriptTag = documentTag.getElementsByTagName(tag)[0];
            object.src = '//' + URL;
            if (callback) {
                object.addEventListener('load', function (e) {
                    callback(null, e);
                }, false);
            }
            scriptTag.parentNode.insertBefore(object, scriptTag);
        },
        configureStripe() {
            let style = {
                base: {
                    color: '#32325d',
                    lineHeight: '22px',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            this.stripe = Stripe(this.publicKey);
            this.elements = this.stripe.elements();
            this.card = this.elements.create('card', {style: style});
            this.card.mount('#card-element');
            this.card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            let form = document.getElementById('payment-form');
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                this.stripe.handleCardPayment(this.intent, this.card, {
                    payment_method_data: {}
                }).then((result) => {
                    if (result.error) {
                        let errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        this.$toastr.s(result.paymentIntent.status)
                    }
                });
            });
        },
    }
}
</script>
