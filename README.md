# JetBooking Shamsi Dates

**Contributors:** Nima Fadaei (with Jules)
**Tags:** jetbooking, shamsi, jalali, persian, date, booking
**Requires at least:** 5.3
**Tested up to:** 6.8
**Stable tag:** 1.0.0
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

A simple and lightweight plugin that converts all dates in the [JetBooking](https://crocoblock.com/plugins/jetbooking/) plugin from Gregorian to Shamsi (Jalali) calendar.

## Description

This plugin is an add-on for JetBooking that provides Shamsi (Persian) calendar functionality. Once activated, it automatically finds the dates displayed by JetBooking, such as check-in/check-out dates and date ranges, and converts them to the Shamsi calendar.

This plugin is designed to be "plug and play." No configuration is needed.

### Features
*   **Automatic Conversion:** Automatically converts dates without any manual setup.
*   **Shamsi Calendar:** Displays all JetBooking dates in the Shamsi calendar.
*   **Lightweight:** The plugin is built for performance and does not add any bloat to your website.
*   **Easy to Use:** Just install and activate.

## Installation

1.  Upload the `jetbooking-shamsi-dates` folder to the `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  That's it! The dates in JetBooking will now be displayed in Shamsi.

## Frequently Asked Questions

### Does this plugin require any other plugins?
Yes, it requires the [JetBooking](https://crocoblock.com/plugins/jetbooking/) plugin to be installed and activated.

### Does this plugin have any settings?
No, it works out of the box and does not have any settings.

### What if the dates are not converting?
This plugin relies on specific filters from the JetBooking plugin to modify the dates. If the dates are not converting, it's possible that the filter names have changed in a recent update of JetBooking. If you are a developer, you can check the filter hooks used in the main plugin file (`jetbooking-shamsi-dates.php`). The assumed filter names are:
*   `jet-booking/render/check-in-date`
*   `jet-booking/render/check-out-date`
*   `jet-booking/render/dates-range`

You can try to find the correct filter names in the JetBooking plugin's source code and replace them in this plugin.

## Changelog

### 1.0.0
*   Initial release.
