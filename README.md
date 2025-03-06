# Category Password Protection WordPress Plugin

This WordPress plugin allows you to add password protection to individual categories.  Only users who enter the correct password can view the content within a protected category.

## Features

*   **Category-Specific Passwords:** Set unique passwords for each category on your WordPress site.
*   **Simple Password Form:** Presents a straightforward password form to users attempting to access protected categories.
*   **Cookie-Based Access:** Remembers the password for a period (30 days by default) using cookies, so users don't have to re-enter it repeatedly.
*   **Easy to Use:** Seamlessly integrates with the WordPress category management interface.

## Installation

1.  Download the plugin as a ZIP file.
2.  In your WordPress admin area, go to "Plugins" -> "Add New."
3.  Click "Upload Plugin."
4.  Select the downloaded ZIP file and click "Install Now."
5.  Activate the "Category Password Protection" plugin.

## Usage

1.  Go to "Posts" -> "Categories" in your WordPress admin area.
2.  Edit or add a category.
3.  You will see a "Category Password" field. Enter a password for the category. Leave it blank for no password protection.
4.  Update or add the category.
5.  When a visitor tries to view the protected category, they will be prompted to enter the password.

## Security Considerations

*   **HTTPS Required:** **This plugin relies on browser cookies for session management.  It is *essential* that your website uses HTTPS (SSL certificate) to encrypt the connection between the user's browser and your server.**  Without HTTPS, the password can be intercepted in transit.
*   **MD5 Hashing:** The plugin uses MD5 to hash the password *only* for cookie storage.  MD5 is considered cryptographically broken for storing passwords in databases and should never be used for that purpose.  This plugin *does not* store user passwords in the database.  The MD5 is purely for a simple cookie-based session.
*   **Sanitization:** The plugin uses `sanitize_text_field()` to sanitize the password input to help prevent XSS vulnerabilities.
*   **Cookies:**  Cookies are stored locally on the user's machine and can be accessed and modified by the user.  While MD5 helps obscure the password within the cookie, it's not a perfect security measure.  Consider this a basic level of protection.

## Planned Improvements (Roadmap)

*   **Improved User Experience:** More informative error messages, password strength meter.
*   **Nonce Verification:** Add nonce verification to the password form to prevent CSRF attacks.
*   **Custom Template:** Allow users to customize the password form's appearance.
*   **Configurable Cookie Expiration:** Allow changing the cookie expiration time.
