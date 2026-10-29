<?php
$_['tab_stripe'] = 'Stripe';
$_['entry_stripe_secret_key'] = 'Stripe secret key:';
$_['text_stripe_secret_key_note'] = 'Secret key (sk_live_... or sk_test_...) from the Stripe Dashboard. Required for the "InvoiceFlash Pro" subscribe button in account/subscription to work.';
$_['entry_stripe_webhook_secret'] = 'Stripe webhook secret:';
$_['text_stripe_webhook_secret_note'] = 'Signing secret (whsec_...) of the webhook configured in Stripe pointing to index.php?route=account/subscription/webhook. Optional, but without it the subscription status will not update automatically.';
