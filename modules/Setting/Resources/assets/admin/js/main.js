window.admin.removeSubmitButtonOffsetOn(["#logo", "#courier"]);

let currencyRateExchangeService = $("#currency_rate_exchange_service");

$(`#${currencyRateExchangeService.val()}-service`).removeClass("hide");

currencyRateExchangeService.on("change", (e) => {
    $(".currency-rate-exchange-service").addClass("hide");

    $(`#${e.currentTarget.value}-service`).removeClass("hide");
});

$("#auto_refresh_currency_rates").on("change", () => {
    $("#auto-refresh-currency-rates-frequency-field").toggleClass("hide");
});

$("#auto_refresh_currency_rates").on("change", () => {
    $("#auto-refresh-frequency-field").toggleClass("hide");
});

let smsService = $("#sms_service");

$(`#${smsService.val()}-service`).removeClass("hide");

smsService.on("change", (e) => {
    $(".sms-service").addClass("hide");

    $(`#${e.currentTarget.value}-service`).removeClass("hide");
});

$("#google_recaptcha_enabled").on("change", () => {
    $("#google-recaptcha-fields").toggleClass("hide");
});

$("#facebook_login_enabled").on("change", () => {
    $("#facebook-login-fields").toggleClass("hide");
});

$("#google_login_enabled").on("change", () => {
    $("#google-login-fields").toggleClass("hide");
});

$("#paypal_enabled").on("change", () => {
    $("#paypal-fields").toggleClass("hide");
});

$("#stripe_enabled").on("change", () => {
    $("#stripe-fields").toggleClass("hide");
});

$("#paytm_enabled").on("change", () => {
    $("#paytm-fields").toggleClass("hide");
});

$("#razorpay_enabled").on("change", () => {
    $("#razorpay-fields").toggleClass("hide");
});

$("#instamojo_enabled").on("change", () => {
    $("#instamojo-fields").toggleClass("hide");
});

$("#paystack_enabled").on("change", () => {
    $("#paystack-fields").toggleClass("hide");
});

$("#authorizenet_enabled").on("change", () => {
    $("#authorizenet-fields").toggleClass("hide");
});

$("#mercadopago_enabled").on("change", () => {
    $("#mercadopago-fields").toggleClass("hide");
});

$("#flutterwave_enabled").on("change", () => {
    $("#flutterwave-fields").toggleClass("hide");
});

$("#iyzico_enabled").on("change", () => {
    $("#iyzico-fields").toggleClass("hide");
});

$("#payfast_enabled").on("change", () => {
    $("#payfast-fields").toggleClass("hide");
});

$("#bank_transfer_enabled").on("change", () => {
    $("#bank-transfer-fields").toggleClass("hide");
});

$("#check_payment_enabled").on("change", () => {
    $("#check-payment-fields").toggleClass("hide");
});

$("#shipping_city").on("change", (e) => {
    let oldCity = $("#shipping_city").val();

    $.ajax({
        type: "GET",
        url: route("countries.states.city", e.currentTarget.value),
        success(response) {
            let city = response.data;
            $("#shipping_city").addClass("hide");

            if (_.isEmpty(states)) {
                return $("#shipping_city.input")
                    .removeClass("hide")
                    .find("input")
                    .val(oldCity);
            }

            let options = "";

            for (let code in city) {
                options += `<option value="${code}">${states[code]}</option>`;
            }

            $(".store-state.select")
                .removeClass("hide")
                .find("select")
                .html(options)
                .val(oldCity);
        },
    });
});

$(function () {
    $("#shipping_city").trigger("change");

    if ($("#logo").hasClass("active")) {
        $("#logo")
            .parent()
            .find('button[type="submit"]')
            .parent()
            .removeClass("col-md-offset-2");
    }
});
