<?php

namespace App\Data;

/**
 * Static content for the /destinations country travel pages.
 *
 * Content is deliberately static (no DB) so pages render fast and remain
 * indexable. Airport codes reference the same airports.json feed the flight
 * search autocomplete uses, so each page's search form pre-fills reliably.
 */
class CountryDestinations
{
    public static function all(): array
    {
        return [
            'italy' => [
                'slug' => 'italy',
                'name' => 'Italy',
                'gateway_code' => 'FCO',
                'hero_image' => 'images/unsplash/photo-1506748686214-e9df14d4d9d0.webp',
                'route_slugs' => [
                    'london-lhr-to-rome-fco',
                ],
                'seo' => [
                    'title' => 'Travel to Italy | Flights, Things to Do & Life in Italy | Nurud Travels',
                    'description' => 'Why visit Italy? Discover what to see and do, how to get there from London, everyday life in Italy, and the best cheap flights to Rome with Nurud Travels.',
                ],
                'h1' => 'Travelling to Italy',
                'short' => 'Italy is where history, food and la dolce vita collide. From the Colosseum in Rome to the canals of Venice, it is Europe\'s most loved short-break and honeymoon destination.',
                'why' => [
                    'Iconic art and archaeology — Rome, Florence and Pompeii hold some of the world\'s greatest treasures.',
                    'Unbeatable food and wine — regional pasta, pizza, gelato and world-class vintages.',
                    'Varied landscapes — Alpine lakes, Amalfi coastline, Tuscan hills and active volcanoes.',
                    'Warm, sociable culture — family meals, festivals and piazzas made for people-watching.',
                ],
                'where_to_go' => [
                    ['name' => 'Rome', 'note' => 'The Colosseum, Pantheon, Vatican and Trevi Fountain — three millennia of history on every corner.'],
                    ['name' => 'Florence', 'note' => 'The cradle of the Renaissance with the Duomo, Uffizi Gallery and Ponte Vecchio.'],
                    ['name' => 'Venice', 'note' => 'Gondola-lined canals, St Mark\'s Square and the Rialto Market built across 100+ islands.'],
                    ['name' => 'Amalfi Coast', 'note' => 'Cliff-hugging towns like Positano and Ravello above turquoise coves.'],
                    ['name' => 'Milan & the Lakes', 'note' => 'Fashion capital Milan plus Lake Como and Lake Garda for a relaxed mountain escape.'],
                ],
                'life' => [
                    'Italians live at a slower, social pace — long lunches, late dinners and strong local identity.',
                    'The cost of living is generally lower than the UK, especially outside major tourist centres.',
                    'Public transport is excellent in cities; Italy\'s Frecciarossa high-speed rail links north to south.',
                    'English is spoken in tourist areas, but a few Italian phrases go a long way with locals.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa to travel to Italy?', 'answer' => 'No. British citizens can travel to Italy for tourism for up to 90 days in any 180-day period without a visa. Your passport must be valid for at least three months beyond your intended stay.'],
                    ['question' => 'How long is the flight from London to Rome?', 'answer' => 'Direct flights from London Heathrow to Rome Fiumicino (FCO) take around 2 hours 30 minutes. British Airways and other carriers operate multiple daily departures.'],
                    ['question' => 'When is the best time to visit Italy?', 'answer' => 'April–June and September–October give pleasant weather with fewer crowds. July–August is hot and busy; winter is quieter and cheaper, ideal for cities and skiing.'],
                    ['question' => 'What currency is used in Italy?', 'answer' => 'Italy uses the Euro (€). Cards are widely accepted, but carry some cash for small shops, markets and rural areas.'],
                ],
            ],
            'spain' => [
                'slug' => 'spain',
                'name' => 'Spain',
                'gateway_code' => 'BCN',
                'hero_image' => 'images/spain-city.webp',
                'route_slugs' => [
                    'london-lhr-to-barcelona-bcn',
                ],
                'seo' => [
                    'title' => 'Travel to Spain | Flights, Things to Do & Life in Spain | Nurud Travels',
                    'description' => 'From Barcelona\'s Gaudí masterpieces to Andalusian festivals — what to see and do, everyday life and cheap flights from London to Spain with Nurud Travels.',
                ],
                'h1' => 'Travelling to Spain',
                'short' => 'Spain pairs world-famous beaches with vibrant cities, flamenco, tapas and some of Europe\'s best-value sunshine — an easy, year-round escape from the UK.',
                'why' => [
                    'Sunshine all year round — even Madrid\'s winter is mild compared with London.',
                    'Rich cultural mix — Moorish palaces in Andalusia, Gaudí\'s surreal Barcelona, and Basque pintxos.',
                    'Great-value food and drink — generous tapas portions, fresh seafood and local wine.',
                    'Easy to explore — high-speed AVE trains connect Barcelona, Madrid, Seville and Valencia.',
                ],
                'where_to_go' => [
                    ['name' => 'Barcelona', 'note' => 'Sagrada Família, Park Güell, the Gothic Quarter and the beach — the city\'s calling cards.'],
                    ['name' => 'Madrid', 'note' => 'The Royal Palace, Prado Museum and grand plazas in Spain\'s buzzing capital.'],
                    ['name' => 'Seville', 'note' => 'Home of flamenco, orange-tree patios and the majestic Alcázar palace.'],
                    ['name' => 'Valencia', 'note' => 'Birthplace of paella with futuristic architecture and one of Spain\'s best beaches.'],
                    ['name' => 'Granada & the Alhambra', 'note' => 'A fairytale Moorish fortress with snow-capped Sierra Nevada views.'],
                ],
                'life' => [
                    'Larger cities keep long hours — shops may close for a siesta but restaurants run until late.',
                    'Cost of living is lower than the UK; property and dining are particularly good value outside cities.',
                    'Well-connected metro, bus and high-speed rail make inter-city travel easy without a car.',
                    'Locals are warm and expressive; regional languages such as Catalan are proudly used.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Spain?', 'answer' => 'No, for tourist stays of up to 90 days in any 180-day period. Your passport must be valid for at least three months beyond your departure date from Spain.'],
                    ['question' => 'How long is the flight from London to Barcelona?', 'answer' => 'Direct flights from London Heathrow to Barcelona El Prat (BCN) take around 2 hours 15 minutes. Numerous airlines compete on this very popular route.'],
                    ['question' => 'When is the best time to visit Spain?', 'answer' => 'April–June and September–October are ideal for sightseeing and beaching. July–August is hot on the coast and lively; winter suits cities and the Canary Islands.'],
                    ['question' => 'What currency is used in Spain?', 'answer' => 'The Euro (€). Card payments are accepted almost everywhere, though small bars and markets still prefer cash.'],
                ],
            ],
            'thailand' => [
                'slug' => 'thailand',
                'name' => 'Thailand',
                'gateway_code' => 'BKK',
                'hero_image' => 'images/thailand-city.webp',
                'route_slugs' => [
                    'london-lhr-to-bangkok-bkk',
                ],
                'seo' => [
                    'title' => 'Travel to Thailand | Flights, Things to Do & Life in Thailand | Nurud Travels',
                    'description' => 'Planning a trip to the Land of Smiles? Discover Thailand\'s islands, temples and street food, everyday life there and cheap flights from London to Bangkok with Nurud Travels.',
                ],
                'h1' => 'Travelling to Thailand',
                'short' => 'Thailand mixes glittering temples, world-famous street food and island-hopping adventures at a fraction of western prices — South-East Asia\'s ultimate first-stop.',
                'why' => [
                    'Extraordinary value — excellent hotels, meals and travel for a fraction of UK prices.',
                    'Island diversity — party beaches in Phuket, calm lagoons in Krabi and jungle vibes on Koh Samui.',
                    'Unmissable food — pad thai, green curry and mango sticky rice from tiny street stalls.',
                    'Spirit and hospitality — ornate temples, friendly locals and a uniquely welcoming culture.',
                ],
                'where_to_go' => [
                    ['name' => 'Bangkok', 'note' => 'The Grand Palace, floating markets and legendary street food at Chinatown and Yaowarat.'],
                    ['name' => 'Chiang Mai', 'note' => 'Old-wall temples, Sunday night markets and elephant sanctuaries in the north.'],
                    ['name' => 'Phuket', 'note' => 'Thailand\'s biggest island — beaches, nightlife, boat tours to the Phi Phi islands.'],
                    ['name' => 'Krabi & Railay', 'note' => 'Limestone cliffs, emerald waters and beaches only reachable by boat.'],
                    ['name' => 'Ayutthaya', 'note' => 'Ancient ruined capital and UNESCO World Heritage, a short trip from Bangkok.'],
                ],
                'life' => [
                    'Thailand is far more affordable than the UK — dining out is cheap and comfortable hotels are plentiful.',
                    'The tropical climate runs hot all year; plan around the May–October rainy season.',
                    'Buddhism shapes daily life — dress modestly at temples and never touch a monk or the head of another.',
                    'English is common in tourist hubs, and the Thai rail and domestic flight network makes travel simple.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Thailand?', 'answer' => 'British tourists can visit Thailand visa-free for up to 60 days. Carry proof of onward travel and a passport valid for at least six months beyond your stay.'],
                    ['question' => 'How long is the flight from London to Bangkok?', 'answer' => 'Direct flights from London Heathrow to Bangkok Suvarnabhumi (BKK) take around 11–12 hours. Connections via Doha, Dubai or Singapore take 13–16 hours.'],
                    ['question' => 'When is the best time to visit Thailand?', 'answer' => 'November to February is the cool, dry high season — ideal for beaches, with cooler evenings. March–May is hot; June–October brings rain but big discounts.'],
                    ['question' => 'What currency is used in Thailand?', 'answer' => 'The Thai baht (THB). Cash is still king for street food and markets, while cards work in hotels and big stores.'],
                ],
            ],
            'japan' => [
                'slug' => 'japan',
                'name' => 'Japan',
                'gateway_code' => 'HND',
                'hero_image' => 'images/unsplash/photo-1542051841857-5f90071e7989.webp',
                'route_slugs' => [
                    'london-lhr-to-tokyo-hnd',
                ],
                'seo' => [
                    'title' => 'Travel to Japan | Flights, Things to Do & Life in Japan | Nurud Travels',
                    'description' => 'From neon Tokyo to the serene temples of Kyoto — what to see and do, everyday life in Japan, and cheap flights from London to Tokyo with Nurud Travels.',
                ],
                'h1' => 'Travelling to Japan',
                'short' => 'Japan is a fascinating collision of ancient temples and futuristic cities, with bullet trains, impeccable food and a culture of quiet courtesy that surprises every visitor.',
                'why' => [
                    'Perfect blend of old and new — serene shrines one day, neon districts the next.',
                    'World-class rail and infrastructure — the Shinkansen bullet train is an experience itself.',
                    'Exceptional food and service — from sushi counters to convenience-store quality that puts the UK to shame.',
                    'Safety and order — clean streets, punctual trains and a culture built on respect.',
                ],
                'where_to_go' => [
                    ['name' => 'Tokyo', 'note' => 'Shibuya\'s crossing, Asakusa\'s Senso-ji temple, and the world\'s best eating and shopping streets.'],
                    ['name' => 'Kyoto', 'note' => 'Golden Pavilion, bamboo groves and hundreds of historic temples and gardens.'],
                    ['name' => 'Osaka', 'note' => 'Japan\'s food capital — street eats, Osaka Castle and a short hop from universal attractions.'],
                    ['name' => 'Mount Fuji & Hakone', 'note' => 'Iconic volcano views, hot springs (onsen) and lake scenery a day trip from Tokyo.'],
                    ['name' => 'Hiroshima & Miyajima', 'note' => 'The Peace Memorial plus the floating torii gate and tame deer of Miyajima island.'],
                ],
                'life' => [
                    'Daily life is orderly and punctual — trains never seem to run late and people queue with patience.',
                    'Japan is more expensive than South-East Asia but comparable to London for city living.',
                    'Public transport is world-leading; the transport IC card works across rail, subway and buses.',
                    'Etiquette matters — removal of shoes indoors, quiet trains and polite bowing are everyday norms.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Japan?', 'answer' => 'No. British citizens can visit Japan visa-free for tourism for up to 90 days. Your passport must remain valid for the whole stay and you may be asked for travel insurance and booking proof.'],
                    ['question' => 'How long is the flight from London to Tokyo?', 'answer' => 'Direct flights from London Heathrow to Tokyo Haneda (HND) take around 14 hours. Services also fly into Narita (NRT) westbound roughly half an hour longer.'],
                    ['question' => 'When is the best time to visit Japan?', 'answer' => 'March–May is cherry-blossom season and the finest weather. October–November brings autumn colours and mild days. Summer is hot and humid; winter suits ski resorts and snowy Hokkaido.'],
                    ['question' => 'What currency is used in Japan?', 'answer' => 'The Japanese yen (JPY). Cash is still widely expected at temples, small restaurants and rural areas, though cards are increasingly accepted in cities.'],
                ],
            ],
            'india' => [
                'slug' => 'india',
                'name' => 'India',
                'gateway_code' => 'BOM',
                'hero_image' => 'images/unsplash/photo-1524492412937-b28074a5d7da.webp',
                'route_slugs' => [
                    'london-lhr-to-mumbai-bom',
                ],
                'seo' => [
                    'title' => 'Travel to India | Flights, Things to Do & Life in India | Nurud Travels',
                    'description' => 'Golden temples, the Taj Mahal and vibrant street life — what to see and do, everyday life in India, and cheap flights from London to Mumbai with Nurud Travels.',
                ],
                'h1' => 'Travelling to India',
                'short' => 'India is a sensory overload in the best way — awe-inspiring forts and temples, bold flavours, colourful festivals and hospitality that makes every traveller feel welcome.',
                'why' => [
                    'Unmatched heritage — the Taj Mahal, Rajasthan\'s palaces and centuries-old forts.',
                    'Extraordinary value — excellent hotels, trains and meals for a fraction of western costs.',
                    'Incredible food culture — regional curries, street snacks and spice trails from north to south.',
                    'Fabulous festivals — Holi, Diwali and local celebrations erupt in colour across the year.',
                ],
                'where_to_go' => [
                    ['name' => 'Mumbai', 'note' => 'India\'s frenetic heart — Gateway of India, Marine Drive and the best street food in the country.'],
                    ['name' => 'Delhi', 'note' => 'Red Fort, India Gate, bustling Old Delhi and superb museums.'],
                    ['name' => 'Agra & the Taj Mahal', 'note' => 'The world\'s most beautiful building — best seen at sunrise before the crowds.'],
                    ['name' => 'Jaipur', 'note' => 'The Pink City\'s amber forts and Hawa Mahal, gateway to Rajasthan\'s desert kingdoms.'],
                    ['name' => 'Kerala & Goa', 'note' => 'Backwaters and Ayurveda in Kerala; beaches, sunshine and laid-back Goa on the west coast.'],
                ],
                'life' => [
                    'Life is fast, loud and deeply social — family, food and festivals sit at the centre.',
                    'The cost of living is significantly lower than the UK; travel by train is extraordinarily cheap.',
                    'Weather varies wildly — cool winters in the north, tropical heat and monsoons in the south.',
                    'Haggling is normal in markets; dress modestly at religious sites; give your right hand for money and food.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for India?', 'answer' => 'Yes. British citizens must obtain an e-visa before travel for tourism, business or medical visits. Apply online through the official Indian government e-visa portal well before departure.'],
                    ['question' => 'How long is the flight from London to Mumbai?', 'answer' => 'Direct flights from London Heathrow to Mumbai Chhatrapati Shivaji (BOM) take around 9 hours. Connecting itineraries via Dubai, Abu Dhabi or Doha take 11–14 hours depending on the layover.'],
                    ['question' => 'When is the best time to visit India?', 'answer' => 'November to March is the cool dry season across most of India and by far the best time to travel. April–June is hot and June–September brings the monsoon to much of the country.'],
                    ['question' => 'What currency is used in India?', 'answer' => 'The Indian rupee (INR). Cards and UPI digital payments are common in cities, but carry cash for markets, small shops and rural travel.'],
                ],
            ],
            'greece' => [
                'slug' => 'greece',
                'name' => 'Greece',
                'gateway_code' => 'ATH',
                'hero_image' => 'images/unsplash/photo-1533104816931-20fa691ff6ca.webp',
                'route_slugs' => [],
                'seo' => [
                    'title' => 'Travel to Greece | Flights, Things to Do & Life in Greece | Nurud Travels',
                    'description' => 'Whitewashed islands, ancient ruins and azure seas — what to see and do, everyday life in Greece, and flights to Athens with Nurud Travels.',
                ],
                'h1' => 'Travelling to Greece',
                'short' => 'Greece is where western civilisation began and where the Mediterranean dream lives on — ancient sites in Athens, sun-soaked islands, and food that tastes like summer.',
                'why' => [
                    'The cradle of civilisation — the Acropolis, Delphi and Olympia are bucket-list archaeology.',
                    'Island perfection — Santorini, Mykonos, Crete and thousands of lesser-known gems.',
                    'Superb Mediterranean food — fresh seafood, olive oil, grilled meats and legendary hospitality.',
                    'Markedly cheaper than the UK — dining, ferries and local accommodation are real value.',
                ],
                'where_to_go' => [
                    ['name' => 'Athens', 'note' => 'The Acropolis and Parthenon above a thriving modern city of tavernas and markets.'],
                    ['name' => 'Santorini', 'note' => 'Whitewashed cliff villages, blue-domed churches and the famous caldera sunsets.'],
                    ['name' => 'Mykonos', 'note' => 'Cycladic charm with the best nightlife and beach clubs on the Greek islands.'],
                    ['name' => 'Crete', 'note' => 'Europe\'s oldest island — Samaria Gorge, Venetian harbours and superb food.'],
                    ['name' => 'Rhodes & Corfu', 'note' => 'Medieval towns and lush landscapes, great for families and couples alike.'],
                ],
                'life' => [
                    'Greek life revolves around the café and the family — meals are unhurried and social.',
                    'The cost of living is lower than the UK, and island summers extend to late in the day.',
                    'Ferries connect the islands, while Athens\' Metro is modern, cheap and safe.',
                    'Expect warm, expressive hospitality — a shared plate of meze can turn into an invitation.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Greece?', 'answer' => 'No. British citizens can visit Greece for tourism for up to 90 days in any 180-day period without a visa, as part of the EU Schengen zone rules.'],
                    ['question' => 'How long is the flight from London to Athens?', 'answer' => 'Direct flights from London Heathrow to Athens International (ATH) take around 3 hours 45 minutes. Budget and full-service carriers compete on this route.'],
                    ['question' => 'When is the best time to visit Greece?', 'answer' => 'May–June and September–October offer the best balance of warm weather, open island resorts and fewer crowds. July–August is peak and hottest; winter suits Athens and mainland archaeology.'],
                    ['question' => 'What currency is used in Greece?', 'answer' => 'The Euro (€). Cards are widely accepted on the mainland and bigger islands, but carry cash for smaller restaurants and ferry tickets.'],
                ],
            ],
            'australia' => [
                'slug' => 'australia',
                'name' => 'Australia',
                'gateway_code' => 'SYD',
                'hero_image' => 'images/unsplash/photo-1523482580672-f109ba8cb9be.webp',
                'route_slugs' => [
                    'london-lhr-to-sydney-syd',
                ],
                'seo' => [
                    'title' => 'Travel to Australia | Flights, Things to Do & Life in Australia | Nurud Travels',
                    'description' => 'Sydney Harbour, the Great Barrier Reef and the outback — what to see and do, everyday life in Australia, and cheap flights from London to Sydney with Nurud Travels.',
                ],
                'h1' => 'Travelling to Australia',
                'short' => 'Australia is a continent of contrasts — glittering harbour cities, world-famous reefs, vast red deserts and a relaxed outdoor lifestyle that Britons find instantly welcoming.',
                'why' => [
                    'Iconic sights — the Opera House, the Great Barrier Reef and Uluru in one trip.',
                    'Outdoor-first lifestyle — beaches, barbecue culture and endless blue skies.',
                    'Welcoming for Britons — no language barrier, familiar culture and a shared sense of humour.',
                    'Epic road trips — the Great Ocean Road and coastal drives rank among the world\'s best.',
                ],
                'where_to_go' => [
                    ['name' => 'Sydney', 'note' => 'Opera House, Harbour Bridge, Bondi Beach and the ferry ride across the harbour.'],
                    ['name' => 'Melbourne', 'note' => 'Laneway cafés, street art, sport culture and the Great Ocean Road gateway.'],
                    ['name' => 'Great Barrier Reef', 'note' => 'Snorkel and dive the world\'s largest reef system from Cairns or the Whitsundays.'],
                    ['name' => 'Uluru & the Outback', 'note' => 'The sacred red monolith and desert landscapes best seen at sunrise and sunset.'],
                    ['name' => 'Brisbane & the Gold Coast', 'note' => 'Sunshine state beaches, theme parks and family-friendly national parks.'],
                ],
                'life' => [
                    'The pace is relaxed with a strong work-life balance — the weekend begins on Friday at the beach.',
                    'The cost of living is generally higher than the UK, especially for housing in Sydney and Melbourne.',
                    'Distances are huge; domestic flights and overnight coach trips are part of everyday travel.',
                    'Australians are famously friendly and direct — tipping is less expected than in the US, and service is warm.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Australia?', 'answer' => 'Yes. British citizens need an Australian visa before travel. Most tourists apply for the eVisitor visa (subclass 651), which is free and processed online, allowing stays up to three months.'],
                    ['question' => 'How long is the flight from London to Sydney?', 'answer' => 'Flights from London to Sydney take around 22–24 hours. There are no direct services; most itineraries connect through Doha, Dubai or Singapore, and a stopover helps break up the journey.'],
                    ['question' => 'When is the best time to visit Australia?', 'answer' => 'September–November and March–May offer mild weather across the south and are ideal all-round. December–February is hot and holiday-busy; winter (June–August) is great year for the Reef and the tropics.'],
                    ['question' => 'What currency is used in Australia?', 'answer' => 'The Australian dollar (AUD). Card and contactless payments are used almost everywhere, and cash is rarely essential.'],
                ],
            ],
            'nigeria' => [
                'slug' => 'nigeria',
                'name' => 'Nigeria',
                'gateway_code' => 'LOS',
                'hero_image' => 'images/unsplash/photo-1618828665011-0abd973f7bb8.webp',
                'route_slugs' => [
                    'london-lhr-to-lagos-los',
                    'london-lhr-to-abuja-abv',
                ],
                'seo' => [
                    'title' => 'Travel to Nigeria | Flights, Things to Do & Life in Nigeria | Nurud Travels',
                    'description' => 'Lagos energy, Abuja\'s calm, and the culture, food and warmth of Nigeria — what to see and do, everyday life there, and flights from London with Nurud Travels.',
                ],
                'h1' => 'Travelling to Nigeria',
                'short' => 'Nigeria is West Africa\'s powerhouse — a country of electric cities, world-class music and film, warm hospitality and a diaspora the UK calls home. Whether visiting family or exploring, there\'s nowhere quite like it.',
                'why' => [
                    'Vibrant culture and music — afrobeats, Nollywood and fashion that lead the continent.',
                    'Incredible food — jollof rice, suya, pounded yam and fresh seafood.',
                    'Warm, family-first hospitality — the giant welcome is part of every visit.',
                    'A deep UK–Nigeria connection — sharp fares, frequent flights and a growing route network.',
                ],
                'where_to_go' => [
                    ['name' => 'Lagos', 'note' => 'The beating heart — Lagos Island, Victoria Island beaches, arts in Ikoyi and non-stop nightlife.'],
                    ['name' => 'Abuja', 'note' => 'The planned federal capital — Aso Rock, the National Mosque and a calmer pace.'],
                    ['name' => 'Ibadan', 'note' => 'The largest city by area in West Africa, famed for cocoa history and the old city walls.'],
                    ['name' => 'Calabar', 'note' => 'The tourism capital of the south with a relaxed riverfront and the famous carnival.'],
                    ['name' => 'Jos & the North', 'note' => 'Cool highland plateau, rock formations and national parks for nature lovers.'],
                ],
                'life' => [
                    'Life is dynamic and entrepreneurial — energy, hustle and warmth coexist on every street.',
                    'Nigerians are famously friendly; family ties and community matter above almost everything.',
                    'The climate is tropical — a dry season (November–March) and a rainy season that peaks mid-year.',
                    'The Naira is the currency; carry cash for smaller traders, while cards and transfers work in big cities.',
                ],
                'faqs' => [
                    ['question' => 'Do UK citizens need a visa for Nigeria?', 'answer' => 'Yes. British citizens need a Nigerian visa before travel. Business and visa-on-arrival options exist, but most visitors apply through the Nigeria Immigration Service online portal with an invitation or accommodation proof.'],
                    ['question' => 'How long is the flight from London to Lagos?', 'answer' => 'Direct flights from London Heathrow to Murtala Muhammed International Airport (LOS) take around 6 hours 50 minutes. Air Peace, British Airways and Virgin Atlantic operate direct services.'],
                    ['question' => 'When is the best time to visit Nigeria?', 'answer' => 'November to March is the dry season, with milder temperatures and the least rain — the best window for travel. Expect higher fares over December and Easter when the diaspora travels home.'],
                    ['question' => 'What currency is used in Nigeria?', 'answer' => 'The Nigerian naira (NGN). Cards work in Lagos and major hotels, but cash is still essential for markets, taxis and smaller vendors throughout the country.'],
                ],
            ],
        ];
    }

    public static function find(string $slug): ?array
    {
        $slug = strtolower($slug);

        return self::all()[$slug] ?? null;
    }
}