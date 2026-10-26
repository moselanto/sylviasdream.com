# cPanel licence error on the dev host

**Resolved 26 October 2026.** The host fixed the licence; sign-in, Git Version Control (Update from Remote, HEAD 4dccb83) and File Manager work again. cPanel's "The system cannot deploy" note only means the repo has no `.cpanel.yml`; deployment stays a File Manager copy or a WP Admin theme upload. Kept for reference if it happens again.

Symptom: cPanel on topnotchmall.co.ke shows "License is not for this host. This server does not have a valid license." Git Version Control and File Manager cannot be used. Only the hosting provider can fix it (the licence belongs to their server).

## Ask the host to fix it

1. Note the account details: domain `topnotchmall.co.ke`, cPanel username `topnotch`, and the date and time you saw the error.
2. Take a screenshot of the error page.
3. Log in to the hosting provider's client area (the site where you pay for hosting), open **Support > Open ticket** (or email/WhatsApp support), department **Technical support**, priority **High**.
4. Send the message below with the screenshot attached.
5. When they reply that it is fixed: log in to cPanel, open **Git Version Control** and **File Manager** to confirm both work, and tell the team so deployments go back to the normal route.
6. If there is no reply within 24 hours, follow up on the same ticket; if they cannot fix it, ask them to move the account to a licensed server.

### Message to send

> Subject: cPanel licence error on topnotchmall.co.ke (account: topnotch)
>
> Hello, when I log in to cPanel for topnotchmall.co.ke (username topnotch) I get "License is not for this host. This server does not have a valid license." I cannot use Git Version Control or File Manager. Please renew or reassign the cPanel licence for the server hosting my account, or move my account to a licensed server, and let me know when it is done. Screenshot attached. Thank you.

## Until it is fixed: update the theme through WordPress

1. Get the theme zip: in the repo run `bash scripts/build-theme-zip.sh` (writes `dist/sylviasdream-child-<version>.zip`), or ask the assistant for the zip.
2. Sign in to https://topnotchmall.co.ke/sylviasdream/wp-admin/.
3. Go to **Appearance > Themes > Add New Theme > Upload Theme**, choose the zip, click **Install Now**.
4. WordPress says the theme is already installed: click **Replace active with uploaded**.
5. Open the site in a private window and check Home, Books menu and Contact load with no error. If something breaks, upload the previous zip the same way.
6. The core plugin is updated the same way under **Plugins > Add New Plugin > Upload Plugin** (Replace current with uploaded).
