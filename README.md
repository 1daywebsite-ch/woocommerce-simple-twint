# WooCommerce Simple Twint #

## Beschreibung ##

### Ermöglicht eine einfache (und kostenlose) Möglichkeit Twint in einem WooCommerce-Shop zu benützen ###

Aktuell gibt es nur Plugins für Twint zum Kaufen, ab 99 Franken bis 200 Franken im Jahr (falls man Updates auch nach einem Jahr möchte). Wir finden, dass gerade kleine Shops eine quelloffene Lösung haben sollten, die nichts kostet und auch wenig Wartung (keine technischen Probleme punkto Verbindung zwischen Twint und dem Shop) benötigt.

Sie hat jedoch zwei grosse Nachteile gegenüber den Lösungen, die gekauft werden können:

- der QR-Code zum Einlesen muss als Bild (JPEG, PNG usw.) in den Shop raufgeladen werden und anschliessend das Link mit diesem Plugin verknüpft werden. Den QR-Code kann man direkt aus dem Twint-Konto (muss ein Geschäftskonto sein) bestellen. Diese Broschüre von Twint erklärt wie: https://www.twint.ch/content/uploads/2019/10/Anleitung-TWINT-QR-Code-Sticker-bestellen.pdf.
- es gibt keine automatische Rückmeldung der eingegangen Zahlung direkt im Shop, d.h. der Shop-Manager erhält einfach die SMS oder E-Mail-Benachrichtigung im Twint-Konto und kann anschliessend von Hand in WooCommerce die Bestellung auf fertig gestellt setzen.

Da gemäss des E-Commerce Stimmungsbarometer der Post (Link: https://e-commerce.post.ch/download/de/E-Commerce_Stimmungsbarometer_2019.pdf) etwa 23 % der Nutzer von Online-Shops in der Schweiz mobile Zahlungen benützen (dazu gehören Twint, Apple Pay, Google Pay usw.), finden wir das unsere Lösung durchaus für Shops mit wenigen Twint-Zahlungen Sinn macht. Es liegt ja im Ermessen jedes Shop-Betreibers, wo er oder sie findet, dass jetzt die Umsätze über Twint eine bessere Anbindung über ein kommerzielles WooCommerce-Plugin rechtfertigen.

Übrigens: Am beliebtesten bleiben in der Schweiz als Zahlungsmittel online die Kreditkarte mit 76 % und Zahlung auf Rechnung mit 75 %.

## Wie funktioniert das Plugin? ###

### Kassenseite zeigt neue Zahlungsmöglichkeit Twint###

Die Zahlungsmöglichkeit mit dem Titel "Bezahlen Sie bequem mit Twint" mit Instruktionen ist aktiv:

<p align="center">
  ![simple-twint-screenshot-1](simple-twint-screenshot-1.jpg)
</p

### Thank you page & customer email ###

Adds the installment plan with the number of installments and the monthly amount on the thank you page after the order details.

![simple-installation-screenshot-2](simple-installation-screenshot-2.jpg)

Adds the installment plan to the admin and customer email, with the number of installments and monthly amount after the order details.

![simple-installation-screenshot-3](simple-installation-screenshot-3.jpg)

### Admin orders page ###

Adds the installment plan with number of installments and monthly amount to the admin screen.

![simple-installation-screenshot-4](simple-installation-screenshot-4.jpg)

### Payment Gateway settings page ###

The screenshot below shows all available options:

- You can set a general customer message before the installment plan info, which is the number of monthly rates and the monthly rate amount.
- You can set a minimum cart total as a threshold for this payment gateway to show up on the checkout.
- You can activiate this payment gateway for guests. By default only customers who are logged in and who have one completeted order may see this payment gateway.
- You can set the number of monthly installments.
- You can set an extra fee for the handling of installments.
- By default the order status is in waiting.
- The admin notice by default is hidden.

![simple-installation-screenshot-5](simple-installation-screenshot-5.jpg)

## Installation ##
Download ZIP file and unpack the plugin directory.

You can install it by placing it directly into the `/wp-content/plugins/`directory of your WordPress installation and then activate it from the "Plugins" screen, or you can go to "Plugins" - "Install" - "Upload Plugin" and upload it, and then activate it.

Go then to "WooCommerce" - "Settings" and activate this payment option under "Checkout".

## Changelog ##

### 1.0.0 ###
First version: tested with Woocommerce 4.2 and WordPress 5.4.1
