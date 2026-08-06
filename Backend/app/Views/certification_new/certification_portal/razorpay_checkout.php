<!DOCTYPE html>
<html>

<head>
    <title>Payment</title>
</head>

<body>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        var options = {
            key: "<?= $key_id ?>",
            amount: <?= $amount * 100 ?>,
            currency: "INR",
            name: "DOCHEK",
            description: "Certification Payment",
            order_id: "<?= $order_id ?>",

            handler: function(response) {

                console.log(response);

                fetch("<?= base_url('Certification/Certification_Payment/verifyPayment') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "razorpay_payment_id=" + response.razorpay_payment_id +
                            "&razorpay_order_id=" + response.razorpay_order_id +
                            "&razorpay_signature=" + response.razorpay_signature +
                            "&certificate_id=" + <?= $certificate_id ?> +
                            "&certificate_name=" + encodeURIComponent("<?= esc($certificate_name) ?>")
                    })
                    .then(res => res.text())
                    .then(data => {
                        // alert(data);
                        // console.log(data);
                        window.location.href =
                            window.location.href =
                            "<?= base_url('Certification/Certification_Payment/paymentSuccess') ?>";
                    })
                    .catch(err => {
                        // alert('Fetch Error');
                        console.log(err);
                    });
            },
            modal: {
                ondismiss: function() {

                    fetch("<?= base_url('Certification/Certification_Payment/paymentCancelled') ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "razorpay_order_id=<?= $order_id ?>"
                        })
                        .then(() => {

                            window.location.href =
                                "<?= base_url('Certification/Certification_Portal/buyNowDetails') ?>";

                        });
                }
            }
        };

        var rzp = new Razorpay(options);

        /* ADD FAILURE HANDLER HERE */
        rzp.on('payment.failed', function(response) {

            fetch("<?= base_url('Certification/Certification_Payment/paymentFailed') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "razorpay_order_id=<?= $order_id ?>"
                })
                .then(res => res.json())
                .then(data => {

                    window.location.href =
                        "<?= base_url('Certification/Certification_Portal/buyNowDetails') ?>";
                });
        });
        window.onload = function() {
            rzp.open();
        };
    </script>

</body>

</html>