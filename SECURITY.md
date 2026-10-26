# Security policy

## Reporting a vulnerability

Please do not open a public issue for security problems. Email Pimofy Digital (Moses Munyi) privately with the subject "Security report: sylviasdream.com", a description, the affected URL or file, and steps to reproduce. We aim to acknowledge within 3 working days and fix confirmed issues as quickly as possible. If the issue involves buyer, subscriber or donor data, say so in the subject.

## Supported versions

Only the latest `sylviasdream-child` theme and `sylviasdream-core` plugin receive fixes. See [CHANGELOG.md](CHANGELOG.md).

## How the site is protected

Controls, findings and residual risks are in [SECURITY-AUDIT.md](SECURITY-AUDIT.md): output escaping and input sanitisation, nonce and capability checks on every save, no secrets or private data in Git, payments handled only by Stripe/PayPal through WooCommerce, legacy download protection, 2FA for administrators, and automated PHP lint and theme checks on every push.
