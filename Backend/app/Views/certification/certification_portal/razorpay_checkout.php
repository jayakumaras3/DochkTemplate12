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
                            credentials: "same-origin",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "razorpay_order_id=<?= $order_id ?>" +
                                "&<?= csrf_token() ?>=<?= csrf_hash() ?>"
                        })
                        .then(res => {
                            console.log("Cancel Status:", res.status);
                            return res.text();
                        })
                        .then(data => {
                            console.log(data);

                            window.location.href =
                                "<?= base_url('Certification/Certification_Portal/buyNowDetails') ?>";
                        })
                        .catch(err => {
                            console.error("Cancel Error:", err);
                        });

                }
            }
        };

        var rzp = new Razorpay(options);

        /* ADD FAILURE HANDLER HERE */
        rzp.on('payment.failed', function(response) {

            console.log(response);

            fetch("<?= base_url('Certification/Certification_Payment/paymentFailed') ?>", {
                    method: "POST",
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "razorpay_order_id=<?= $order_id ?>" +
                        "&<?= csrf_token() ?>=<?= csrf_hash() ?>"
                })
                .then(res => {
                    console.log("Failed Status:", res.status);
                    return res.text();
                })
                .then(data => {

                    console.log(data);

                    window.location.href =
                        "<?= base_url('Certification/Certification_Portal/buyNowDetails') ?>";

                })
                .catch(err => {

                    console.error(err);

                });

        });
        window.onload = function() {
            rzp.open();
        };
    </script>

</body>

</html>