<?php

/*
|--------------------------------------------------------------------------
| All Things Tobago — site content
|--------------------------------------------------------------------------
| Business details for the public site. Items marked CONFIRM are placeholders
| taken from public profiles and must be checked with the client before launch.
*/

return [
    'name' => 'All Things Tobago',
    'short' => 'ATT',
    'tagline' => 'Every tour, every boat, every beach. One island, one call.',
    'intro' => 'Island tours, glass-bottom boat trips, clear kayaks, jet skis, beach massages and stays, all arranged by the Tobago crew behind @allthingstobago.',
    'description' => 'Book Tobago tours and water activities with All Things Tobago: 360 island tours, Buccoo Reef and Nylon Pool boat trips, clear kayaking, jet skiing, fishing charters, beach massages, accommodation and event tickets.',

    // CONFIRM with the client: contact details.
    'phone' => '(868) 000-0000',
    'phone_href' => '+18680000000',
    'whatsapp' => '18680000000',
    'email' => 'hello@allthingstobago.com',
    'hours' => 'Every day, 7:00 am to 9:00 pm',
    'base' => 'Crown Point, Tobago',
    'pickup_note' => 'Free pickup from hotels and villas in Crown Point, Bon Accord, Canaan and Buccoo. Other areas on request.',
    'timezone' => 'America/Port_of_Spain',

    'social' => [
        'instagram' => 'https://www.instagram.com/allthingstobago/',
        'instagram_handle' => '@allthingstobago',
        'instagram_followers' => '34K',
        'tiktok' => 'https://www.tiktok.com/@allthingstobago',
        'facebook' => 'https://www.facebook.com/share/1DXtjUmiic/',
    ],

    'currency' => [
        'home' => 'TTD',
        'usd_rate' => 6.80, // TTD per 1 USD, display only. CONFIRM.
    ],

    'stats' => [
        ['value' => 34, 'suffix' => 'K', 'label' => 'Instagram followers'],
        ['value' => 450, 'suffix' => '+', 'label' => 'Posts from the island'],
        ['value' => 12, 'suffix' => '', 'label' => 'Experiences to choose from'],
        ['value' => 7, 'suffix' => ' days', 'label' => 'A week, every week'],
    ],

    'pickup_areas' => ['Crown Point', 'Bon Accord', 'Canaan', 'Buccoo', 'Mt Irvine', 'Black Rock', 'Scarborough', 'Plymouth', 'Speyside / Charlotteville', 'Other (tell us)'],

    'faqs' => [
        ['q' => 'How do I book?', 'a' => 'Pick an experience, choose your date and group size, and send the request. You get a reference straight away and we confirm availability on WhatsApp, usually within the hour during the day. Nothing is charged until we confirm.'],
        ['q' => 'How do I pay?', 'a' => 'Cash in TTD or USD on the day, bank transfer, or a payment link for card payments. Some boat trips need a small deposit to hold the spot in peak season.'],
        ['q' => 'Do you pick us up?', 'a' => 'Yes. Pickup from hotels, guesthouses and villas in the Crown Point, Bon Accord, Canaan and Buccoo area is included on most tours. Other areas can be arranged for a small transport fee.'],
        ['q' => 'What should I bring on a boat trip?', 'a' => 'Swimwear, a towel, reef-safe sunscreen, water shoes if you have them, and a dry bag for your phone. We supply snorkel gear and life jackets.'],
        ['q' => 'What happens if the weather turns?', 'a' => 'Boat and water trips run in most Tobago weather, but if the captain calls it off for safety we move your booking to another day or refund any deposit in full.'],
        ['q' => 'Can children come?', 'a' => 'Children are welcome on island tours, reef trips and clear kayaking with an adult. Jet skis and the Super Mable tow ride have minimum age and height rules, listed on each page.'],
        ['q' => 'Do you do private or group bookings?', 'a' => 'Yes. Boats, jet skis and tours can be booked privately for couples, families, birthdays and bachelorette groups. Use the trip planner to put a day together and we will quote it as one package.'],
        ['q' => 'Is the 360 tour the whole island?', 'a' => 'It is. Crown Point to Speyside and back along both coasts in one day, with stops at the Argyle Waterfall, Castara, the Mystery Tombstone, Fort King George and lunch on the way.'],
    ],

    'seo' => [
        'keywords' => ['All Things Tobago', 'Tobago tours', 'Nylon Pool tour', 'Buccoo Reef glass bottom boat', 'clear kayak Tobago', 'jet ski Tobago', '360 island tour Tobago', 'Tobago boat tour', 'things to do in Tobago'],
    ],
];
