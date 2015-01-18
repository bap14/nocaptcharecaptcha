# NoCAPTCHA ReCAPTCHA Extension

An extension for phpBB 3 which uses the new "NoCAPTCHA" ReCAPTCHA from Google.

## Requirements

- phpBB 3.1 or greater
- [PHP cURL extension](http://www.php.net/curl) enabled
- A ReCAPTCHA account at http://www.google.com/recaptcha

## Installation

1. Download this extension.
2. Create a directory `bpat1434` under the `ext` directory.
3. Create the directory `nocaptcharecaptcha` under the `bpat1434` created in the previous step.
4. Extract the contents of the downloaded extension to `ext/bpat1434/nocaptcharecaptcha`.
5. Navigate in the ACP to `Customize -> Manage extensions`.
6. Look for `NoCAPTCHA ReCAPTCHA` under the Disabled Extensions list, and click its `Enable` link.
7. Set up and configure the NoCAPTCHA ReCAPTCHA by navigating in the ACP to `General -> Board Configuration -> Spambot countermeasures`.

## Uninstall

1. Choose a different Spambot Countermeasure in the ACP at `General -> Board Configuration -> Spambot countermeasures`.
2. Go to `Customize -> Manage extensions` and click its `Disable` link.
3. To permanently uninstall, delete the `ext/bpat1434/nocaptcharecaptcha` directory.

## License

[GNU General Public License v2](http://opensource.org/licenses/GPL-2.0)