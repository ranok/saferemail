Safer Email
==========

Secure webmail system for a safer email experience.

Motivation
----------

Safer Email was born in an attempt to bring secure, encrypted email to the masses. It is designed with the following
ideals in mind:
* Open Source - Those motivated to do so should always be able to see and understand the code running on their systems
* Secure - The emails are all encrypted and unreadable on the server, so even if your database is compromised, the attacker cannot recover your email
* Easy - While secure, encrypted email is currently very possible with a little effort and some technical skills; Safer Email aims to lessen the learning curve, both for end-users and people who want to deploy Safer Email on their servers
* Transparent - Safer Email must work across domains and protocols to ensure a transparent, automatic encryption experience (you might not even know you're using encryption!)

Design
-------

Safer Email is designed to require only a domain name and a LAMP server and provide a simple installation and user experience. 
When installing, the server administrator must add 2 DNS TXT records to his/her domain name to allow other Safer Mail servers to automatically discover public keys from an authoritative source, and directly HTTP POST messages to the remote system.

To receive mail over SMTP, there will be a number of proxy servers which you can add as your MX record, they will accept incoming SMTP messages, and HTTP POST them to your webmail service (alternatively you can host your own SMTP relay).
This allows a much lower barrier to entry for individuals or groups who want to take their email and security into their own hands, only a web hosting service (e.g. DreamHost) is required.

Safer Email uses the latest advancements in HTML5 to provide *client-side* transparent encryption, while still being fully interoperable with the existing email infrastructure and openPGP users. With this system, even a server compromise or insider-threat will not be able to recover the messages in your mailbox.

Safer Email also aims to provide a similar user-experience to currently webmail systems (e.g. Gmail, Yahoo! Mail, etc...).
A simple email/password combination is all that is required to login to view your email, where you can sort your messages into folders, tag messages, and compose rich text messages to any email address.
