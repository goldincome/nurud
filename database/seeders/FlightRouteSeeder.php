<?php

namespace Database\Seeders;

use App\Models\FlightRoute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FlightRouteSeeder extends Seeder
{
    /**
     * Launch routes for the programmatic SEO flight-route landing pages.
     *
     * Airport city/name data is resolved from database/data/airports.json at
     * seed time so the routes always match the autocomplete feed. The `airports`
     * DB table is deliberately never used.
     */
    public function run(): void
    {
        $airports = $this->loadAirports();

        $routes = [
            [
                'origin' => 'LHR',
                'destination' => 'LOS',
                'distance_miles' => 3124,
                'avg_duration_minutes' => 410,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 463,
                'primary_airline' => 'Air Peace',
                'airlines_operating' => [
                    ['name' => 'Air Peace', 'flight_type' => 'Direct', 'min_price' => 463],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 512],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 548],
                    ['name' => 'RwandAir', 'flight_type' => '1 stop', 'min_price' => 399],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London Heathrow to Lagos?', 'answer' => 'A direct flight from London Heathrow (LHR) to Murtala Muhammed International Airport (LOS) takes roughly 6 hours 50 minutes eastbound. Connecting itineraries via Kigali, Addis Ababa or Casablanca typically take 11 to 16 hours depending on the layover.'],
                    ['question' => 'Which airlines fly direct from London to Lagos?', 'answer' => 'Air Peace, British Airways and Virgin Atlantic all operate direct flights between London Heathrow and Lagos. Air Peace is often the most affordable direct option, while British Airways offers multiple weekly frequencies.'],
                    ['question' => 'What is the cheapest time of year to fly to Lagos?', 'answer' => 'Fares tend to drop outside the peak Christmas, Easter and summer school-holiday windows. Early November and late January are regularly the cheapest periods, with savings compared to December peaks.'],
                    ['question' => 'How much luggage is included on London to Lagos flights?', 'answer' => 'Most direct carriers on this route include at least one checked bag of 23kg in the fare. Low-cost and connecting options may charge extra — always verify baggage allowances before confirming your booking.'],
                    ['question' => 'Do I need a visa to visit Nigeria from the UK?', 'answer' => 'Yes. British citizens need a Nigerian visa before travel. Nurud Travels can help you plan the visa and travel requirements around your flight booking.'],
                ],
                'seasonal_tips' => [
                    'Book at least 8-10 weeks ahead for the lowest fares on the LHR-LOS route.',
                    'Avoid travelling in the final three weeks of December if you want to control your budget.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'ABV',
                'distance_miles' => 2795,
                'avg_duration_minutes' => 375,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 535,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 535],
                    ['name' => 'Air Peace', 'flight_type' => 'Direct', 'min_price' => 569],
                    ['name' => 'Ethiopian Airlines', 'flight_type' => '1 stop', 'min_price' => 462],
                    ['name' => 'Lufthansa', 'flight_type' => '1 stop', 'min_price' => 511],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Abuja?', 'answer' => 'Direct flights from London Heathrow to Nnamdi Azikiwe International Airport (ABV) take around 6 hours 15 minutes. One-stop itineraries through Addis Ababa or Frankfurt usually take 10 to 14 hours.'],
                    ['question' => 'Which airlines operate direct flights from London to Abuja?', 'answer' => 'British Airways and Air Peace operate direct services between London Heathrow and Abuja. British Airways tends to offer the most consistent schedule on this route.'],
                    ['question' => 'Is Abuja more expensive to fly to than Lagos?', 'answer' => 'Abuja fares are usually slightly higher than Lagos because there are fewer direct frequencies. Connecting options via Ethiopian Airlines or Lufthansa are typically the cheapest way to reach Abuja.'],
                    ['question' => 'When is the best time to book flights to Abuja?', 'answer' => 'Book 6-10 weeks before departure for the best value. Prices rise sharply in the two weeks before departure and across the December holiday period.'],
                ],
                'seasonal_tips' => [
                    'Abuja has a dry season from November to March — the most comfortable time to travel.',
                    'Consider flying into Lagos and taking the domestic leg to Abuja if direct fares spike.',
                ],
            ],
            [
                'origin' => 'MAN',
                'destination' => 'LOS',
                'distance_miles' => 3011,
                'avg_duration_minutes' => 545,
                'has_direct_flights' => false,
                'lowest_fare_gbp' => 520,
                'primary_airline' => 'Ethiopian Airlines',
                'airlines_operating' => [
                    ['name' => 'Ethiopian Airlines', 'flight_type' => '1 stop', 'min_price' => 520],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 585],
                    ['name' => 'Turkish Airlines', 'flight_type' => '1 stop', 'min_price' => 552],
                    ['name' => 'Air France', 'flight_type' => '1 stop', 'min_price' => 594],
                ],
                'faqs' => [
                    ['question' => 'Are there direct flights from Manchester to Lagos?', 'answer' => 'No. There are currently no direct flights from Manchester Airport (MAN) to Lagos. All itineraries connect through hubs such as Addis Ababa, Doha, Istanbul or Paris, with total journey times from around 9 to 14 hours.'],
                    ['question' => 'Which airlines fly from Manchester to Lagos?', 'answer' => 'Ethiopian Airlines, Qatar Airways, Turkish Airlines and Air France connect Manchester to Lagos with a single stop. Ethiopian Airlines is frequently the most affordable option.'],
                    ['question' => 'Do I need to change airports when connecting to Lagos from Manchester?', 'answer' => 'No. All four airlines keep your connection in the same hub airport, so you do not need to switch airports. Our live search tool shows exact layover cities and durations before you book.'],
                    ['question' => 'How much does a Manchester to Lagos flight cost on average?', 'answer' => 'Fares typically range from £520 to £700 depending on the season. Booking 6-10 weeks ahead and travelling midweek usually secures the lowest prices.'],
                ],
                'seasonal_tips' => [
                    'Manchester departures peak during school holidays — book early for summer and Christmas.',
                    'Overnight connections through Doha or Istanbul often carry the lowest fares.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'ACC',
                'distance_miles' => 3099,
                'avg_duration_minutes' => 380,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 510,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 510],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 545],
                    ['name' => 'KLM', 'flight_type' => '1 stop', 'min_price' => 468],
                    ['name' => 'Air France', 'flight_type' => '1 stop', 'min_price' => 483],
                ],
                'faqs' => [
                    ['question' => 'How long is a direct flight from London to Accra?', 'answer' => 'Direct flights from London Heathrow to Kotoka International Airport (ACC) take about 6 hours 20 minutes. One-stop flights through Amsterdam, Paris or Brussels add roughly 2-4 hours in transit.'],
                    ['question' => 'Which airlines fly direct from London to Accra?', 'answer' => 'British Airways and Virgin Atlantic operate direct Heathrow to Accra services. KLM and Air France connect through Amsterdam and Paris respectively and are usually the cheapest options.'],
                    ['question' => 'What is the best time to fly to Accra?', 'answer' => 'Accra is warm year-round. The Harmattan months of December to February offer clear, dry weather. March to June and September to November are greener with occasional showers.'],
                    ['question' => 'Do Ghanaian citizens travelling home need a visa to re-enter?', 'answer' => 'Ghanaian citizens can travel to Ghana on a valid Ghanaian passport without a visa. British citizens need an e-visa or visa on arrival for eligible nationalities.'],
                ],
                'seasonal_tips' => [
                    'Ghana holiday travel peaks heavily around Christmas — book 3-4 months ahead.',
                    'Avoid the short rains in May-June if you prefer guaranteed sunshine.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'DXB',
                'distance_miles' => 3409,
                'avg_duration_minutes' => 420,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 350,
                'primary_airline' => 'Emirates',
                'airlines_operating' => [
                    ['name' => 'Emirates', 'flight_type' => 'Direct', 'min_price' => 350],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 385],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 330],
                    ['name' => 'Air Arabia', 'flight_type' => '1 stop', 'min_price' => 295],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London Heathrow to Dubai?', 'answer' => 'A direct flight from London Heathrow to Dubai International Airport (DXB) takes around 7 hours. One-stop itineraries via Doha or Sharjah typically take 9 to 12 hours.'],
                    ['question' => 'Which airlines fly direct from London to Dubai?', 'answer' => 'Emirates and British Airways operate direct flights between London Heathrow and Dubai. Emirates is the dominant carrier, operating multiple daily departures including the famous A380 service.'],
                    ['question' => 'What is the cheapest time to fly to Dubai?', 'answer' => 'Summer months (June to September) see the lowest fares due to extreme heat. January to March and during the Dubai Shopping Festival, flights cost more — book early if travelling then.'],
                    ['question' => 'Do UK citizens need a visa for Dubai?', 'answer' => 'No. British passport holders receive a visa on arrival valid for 30 days, so travelling to Dubai is straightforward.'],
                ],
                'seasonal_tips' => [
                    'Fly between June and September for the most aggressive discounts.',
                    'Book Emirates well in advance to secure the A380 experience at economy fares.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'JNB',
                'distance_miles' => 5619,
                'avg_duration_minutes' => 660,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 620,
                'primary_airline' => 'Virgin Atlantic',
                'airlines_operating' => [
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 620],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 655],
                    ['name' => 'Ethiopian Airlines', 'flight_type' => '1 stop', 'min_price' => 540],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 588],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Johannesburg?', 'answer' => 'Direct flights from London Heathrow to O.R. Tambo International Airport (JNB) take about 11 hours. One-stop itineraries via Addis Ababa or Doha take 13 to 17 hours.'],
                    ['question' => 'Which airlines fly direct from London to Johannesburg?', 'answer' => 'Virgin Atlantic and British Airways operate direct flights from London Heathrow to Johannesburg. Ethiopian Airlines and Qatar Airways offer cheaper one-stop connections.'],
                    ['question' => 'When is the best time to fly to Johannesburg?', 'answer' => 'The best weather is from October to April (South African summer). Game viewing peaks in the dry winter months of May to September when fares are also lower.'],
                    ['question' => 'Is Johannesburg safe for UK travellers?', 'answer' => 'Johannesburg is safe for travellers who use licensed transport and stay in well-reviewed areas. Nurud Travels can recommend safe neighbourhoods, transfers and guided experiences.'],
                ],
                'seasonal_tips' => [
                    'May to September delivers lower fares and the best wildlife viewing.',
                    'The A380 and Boeing 787 services both offer four-across premium economy — worth the upgrade on this long sector.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'JFK',
                'destination_city' => 'New York',
                'distance_miles' => 3451,
                'avg_duration_minutes' => 455,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 429,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 429],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 455],
                    ['name' => 'American Airlines', 'flight_type' => 'Direct', 'min_price' => 470],
                    ['name' => 'United Airlines', 'flight_type' => 'Direct', 'min_price' => 489],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London Heathrow to New York?', 'answer' => 'A direct flight from London Heathrow (LHR) to John F. Kennedy International Airport (JFK) takes around 7 hours 35 minutes eastbound. Return flights to London usually run slightly longer at roughly 7 hours 50 minutes due to prevailing winds.'],
                    ['question' => 'Which airlines fly direct from London to New York?', 'answer' => 'British Airways, Virgin Atlantic, American Airlines and United Airlines all operate direct services between London Heathrow and New York JFK. This is one of the busiest transatlantic routes, with multiple daily departures.'],
                    ['question' => 'What is the cheapest time of year to fly to New York?', 'answer' => 'Fares drop in late January, February and after the Thanksgiving holiday. Early November and the shoulder months of September and May also offer strong value compared to the summer and pre-Christmas peaks.'],
                    ['question' => 'How much luggage is included on London to New York flights?', 'answer' => 'Most direct transatlantic carriers include at least one checked bag of 23kg in the economy fare. Confirm baggage allowances before booking, as some budget tickets carry additional fees.'],
                    ['question' => 'Do UK citizens need a visa to visit New York?', 'answer' => 'British citizens do not need a visa for tourism, but require an approved ESTA before travel. Nurud Travels can help you confirm the current requirements around your booking.'],
                ],
                'seasonal_tips' => [
                    'Book 6-8 weeks ahead for the best balance of price and seat choice on the LHR-JFK route.',
                    'Fly on Tuesdays and Wednesdays — transatlantic business travel keeps weekend fares higher.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'LAX',
                'destination_city' => 'Los Angeles',
                'distance_miles' => 5440,
                'avg_duration_minutes' => 660,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 525,
                'primary_airline' => 'Virgin Atlantic',
                'airlines_operating' => [
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 525],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 545],
                    ['name' => 'American Airlines', 'flight_type' => 'Direct', 'min_price' => 560],
                    ['name' => 'United Airlines', 'flight_type' => 'Direct', 'min_price' => 578],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Los Angeles?', 'answer' => 'A direct flight from London Heathrow to Los Angeles International Airport (LAX) takes roughly 11 hours. Connecting itineraries via the East Coast or the Gulf typically take 13 to 15 hours.'],
                    ['question' => 'Which airlines fly direct from London to Los Angeles?', 'answer' => 'Virgin Atlantic, British Airways, American Airlines and United Airlines operate direct flights between London Heathrow and Los Angeles. Virgin Atlantic often features the A350 on this route.'],
                    ['question' => 'When is the best time to fly to Los Angeles?', 'answer' => 'September to November offers pleasant weather and lower fares before the December rush. Late January to March is another cost-effective window with mild temperatures.'],
                    ['question' => 'Is Los Angeles an easy place for British travellers?', 'answer' => 'Yes. Los Angeles has a laid-back, car-friendly layout and a large British expat community. Ensure your ESTA is valid and allow extra time for airport security at LAX.'],
                ],
                'seasonal_tips' => [
                    'Book around 7-10 weeks out — the LAX route prices up sharply inside the final month.',
                    'Consider a late-evening departure so you arrive in Los Angeles in the early afternoon.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'ORD',
                'destination_city' => 'Chicago',
                'distance_miles' => 3955,
                'avg_duration_minutes' => 510,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 479,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 479],
                    ['name' => 'American Airlines', 'flight_type' => 'Direct', 'min_price' => 498],
                    ['name' => 'United Airlines', 'flight_type' => 'Direct', 'min_price' => 512],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London Heathrow to Chicago?', 'answer' => 'A direct flight from London Heathrow to Chicago O\'Hare International Airport (ORD) takes about 8 hours 30 minutes. One-stop itineraries via Dublin, New York or Toronto add roughly 2-4 hours.'],
                    ['question' => 'Which airlines fly direct from London to Chicago?', 'answer' => 'British Airways, American Airlines and United Airlines operate direct flights from London Heathrow to Chicago O\'Hare. British Airways runs a daily service with late-afternoon departures.'],
                    ['question' => 'What is the cheapest time to fly to Chicago?', 'answer' => 'Mid-January to February is the cheapest window, followed by the shoulder months of May and October. Summer fares peak with holiday and conference demand.'],
                    ['question' => 'What should I know about arriving at O\'Hare?', 'answer' => 'O\'Hare is a major international gateway with efficient transfers. Give yourself at least two hours to clear immigration and connect if you are travelling onward within the US.'],
                ],
                'seasonal_tips' => [
                    'Chicago winters run December to March — pack layers and book a winter fare for the best savings.',
                    'Tuesday departures consistently undercut Monday and Friday prices on this route.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'MIA',
                'destination_city' => 'Miami',
                'distance_miles' => 4408,
                'avg_duration_minutes' => 540,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 489,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 489],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 505],
                    ['name' => 'American Airlines', 'flight_type' => 'Direct', 'min_price' => 520],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Miami?', 'answer' => 'A direct flight from London Heathrow to Miami International Airport (MIA) takes around 9 hours. One-stop options via New York, Charlotte or the Caribbean take 11 to 13 hours.'],
                    ['question' => 'Which airlines fly direct from London to Miami?', 'answer' => 'British Airways, Virgin Atlantic and American Airlines operate direct services from London Heathrow to Miami. British Airways offers an outgoing overnight service that lands mid-morning.'],
                    ['question' => 'When is the best time to fly to Miami?', 'answer' => 'December to April is the peak season with the best weather but highest fares. May to June and September to October offer cheaper prices, though you should watch hurricane season forecasts.'],
                    ['question' => 'Do I need an ESTA to fly to Miami from the UK?', 'answer' => 'Yes, British citizens need an approved ESTA for tourism and must have a passport valid for at least six months from entry. Our team can confirm requirements when you book.'],
                ],
                'seasonal_tips' => [
                    'Book outside the December-April peak to cut fares by up to a third.',
                    'Miami is a popular cruise port — add a buffer day between your flight and any cruise sailing.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'SFO',
                'destination_city' => 'San Francisco',
                'distance_miles' => 5368,
                'avg_duration_minutes' => 660,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 549,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 549],
                    ['name' => 'United Airlines', 'flight_type' => 'Direct', 'min_price' => 565],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 575],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London Heathrow to San Francisco?', 'answer' => 'A direct flight from London Heathrow to San Francisco International Airport (SFO) takes about 11 hours. Connecting itineraries commonly add 2-3 hours depending on the hub.'],
                    ['question' => 'Which airlines fly direct from London to San Francisco?', 'answer' => 'British Airways, United Airlines and Virgin Atlantic operate direct flights between London Heathrow and San Francisco. All offer premium economy and business cabins on this long sector.'],
                    ['question' => 'What is the cheapest time to fly to San Francisco?', 'answer' => 'January, February and early December tend to offer the lowest fares. The summer months of July and August are the most expensive due to peak tourist demand.'],
                    ['question' => 'What sets San Francisco arrivals apart?', 'answer' => 'SFO has one of the smoothest US immigration processes, with a Global Entry kiosk network and well-organised baggage reclaim. Expect cool, foggy weather even in summer — pack layers.'],
                ],
                'seasonal_tips' => [
                    'Autumn (September-October) is San Francisco\'s warmest and sunniest season — a great value window.',
                    'Red-eye departures from London let you land refreshed in the mid-morning.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'YYZ',
                'destination_city' => 'Toronto',
                'distance_miles' => 3540,
                'avg_duration_minutes' => 450,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 439,
                'primary_airline' => 'Air Canada',
                'airlines_operating' => [
                    ['name' => 'Air Canada', 'flight_type' => 'Direct', 'min_price' => 439],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 465],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Toronto?', 'answer' => 'A direct flight from London Heathrow to Toronto Pearson International Airport (YYZ) takes about 7 hours 30 minutes. Toronto is one of the closest major North American destinations to the UK.'],
                    ['question' => 'Which airlines fly direct from London to Toronto?', 'answer' => 'Air Canada and British Airways operate direct flights from London Heathrow to Toronto. Air Canada runs multiple daily frequencies with premium economy available throughout.'],
                    ['question' => 'When is the best time to fly to Toronto?', 'answer' => 'Spring (April-May) and early autumn (September-October) offer mild weather and lower fares. December and the summer months are peak periods with higher prices.'],
                    ['question' => 'Is London to Toronto a good route for families?', 'answer' => 'Yes. The sector is relatively short, direct flights are frequent, and Toronto Pearson serves a large British and Caribbean expat community. Check your eTA or visa before travelling.'],
                ],
                'seasonal_tips' => [
                    'Book late winter for the lowest spring fares — Toronto prices peak quickly in May.',
                    'Consider an early-morning landing to clear YYZ immigration before the transatlantic afternoon rush.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'CDG',
                'destination_city' => 'Paris',
                'distance_miles' => 214,
                'avg_duration_minutes' => 80,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 89,
                'primary_airline' => 'Air France',
                'airlines_operating' => [
                    ['name' => 'Air France', 'flight_type' => 'Direct', 'min_price' => 89],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 95],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London to Paris?', 'answer' => 'A direct flight from London Heathrow to Paris Charles de Gaulle (CDG) takes about 1 hour 20 minutes. It is one of the shortest international routes flown from the UK capital.'],
                    ['question' => 'Which airlines fly direct from London to Paris?', 'answer' => 'Air France and British Airways operate frequent direct services between London Heathrow and Paris CDG. Between city centres, Eurostar remains a fast rail alternative.'],
                    ['question' => 'What is the cheapest time to fly to Paris?', 'answer' => 'Midweek departures from Tuesday to Thursday are consistently cheaper, and fares drop outside the summer and pre-Christmas peaks. November and late January are low-cost windows.'],
                    ['question' => 'Which Paris airport should I choose?', 'answer' => 'CDG is the main hub and the one served direct from Heathrow. Orly is used more by budget airlines from other UK airports — choose CDG for the most direct and reliable schedule.'],
                ],
                'seasonal_tips' => [
                    'For a city break, fly out Tuesday and back Thursday — the cheapest and quietest combination.',
                    'Allow a minimum of 90 minutes at CDG for intra-Schengen connections.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'AMS',
                'destination_city' => 'Amsterdam',
                'distance_miles' => 230,
                'avg_duration_minutes' => 80,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 85,
                'primary_airline' => 'KLM',
                'airlines_operating' => [
                    ['name' => 'KLM', 'flight_type' => 'Direct', 'min_price' => 85],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 92],
                    ['name' => 'easyJet', 'flight_type' => 'Direct', 'min_price' => 78],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Amsterdam?', 'answer' => 'A direct flight from London Heathrow to Amsterdam Schiphol (AMS) takes about 1 hour 20 minutes. The short sector makes it a popular weekday city break.'],
                    ['question' => 'Which airlines fly direct from London to Amsterdam?', 'answer' => 'KLM, British Airways and easyJet all operate direct services between London and Amsterdam. Schiphol is also a major gateway for onward connections to Asia and the Americas.'],
                    ['question' => 'When is the best time to fly to Amsterdam?', 'answer' => 'January to March offers the lowest fares, with tulip season (April) and summer weekends commanding premiums. Midweek travel keeps costs down year-round.'],
                    ['question' => 'What should I know about Schiphol?', 'answer' => 'Schiphol is compact and well signed, with fast transfer links. Arrive 2-3 hours before international departures and note that Schengen exit is quick for intra-EU travellers.'],
                ],
                'seasonal_tips' => [
                    'Amsterdam is a year-round destination — late autumn and winter fares are the lowest.',
                    'If connecting onward, allow 60-90 minutes at Schiphol; the terminal is efficient but busy at midday.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'BCN',
                'destination_city' => 'Barcelona',
                'distance_miles' => 713,
                'avg_duration_minutes' => 135,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 99,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 99],
                    ['name' => 'Vueling', 'flight_type' => 'Direct', 'min_price' => 105],
                    ['name' => 'Iberia', 'flight_type' => 'Direct', 'min_price' => 125],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London to Barcelona?', 'answer' => 'A direct flight from London Heathrow to Barcelona El Prat (BCN) takes about 2 hours 15 minutes. It is one of the most popular leisure routes out of the UK.'],
                    ['question' => 'Which airlines fly direct from London to Barcelona?', 'answer' => 'British Airways, Vueling and Iberia operate direct services between London and Barcelona. Frequencies are daily, with multiple departures in the summer schedule.'],
                    ['question' => 'What is the cheapest time to fly to Barcelona?', 'answer' => 'Late January to mid-March and November offer the lowest fares. Summer and the mobile trade fair period (late February) see prices rise sharply.'],
                    ['question' => 'Do I need a visa to visit Barcelona from the UK?', 'answer' => 'British citizens can visit Spain visa-free for up to 90 days in any 180-day period. Ensure your passport has at least three months of validity beyond your stay.'],
                ],
                'seasonal_tips' => [
                    'Book midweek and avoid February\'s Mobile World Congress week for big savings.',
                    'Barcelona\'s shoulder seasons (April-May, September-October) pair great weather with lower fares.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'SXF',
                'destination_city' => 'Berlin',
                'distance_miles' => 589,
                'avg_duration_minutes' => 115,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 109,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 109],
                    ['name' => 'Lufthansa', 'flight_type' => '1 stop', 'min_price' => 142],
                    ['name' => 'KLM', 'flight_type' => '1 stop', 'min_price' => 151],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Berlin?', 'answer' => 'A direct flight from London to Berlin takes around 1 hour 55 minutes. British Airways serves Berlin from Heathrow via the city\'s Brandenburg Airport (BER) complex.'],
                    ['question' => 'Which airlines fly from London to Berlin?', 'answer' => 'British Airways operates the main direct Heathrow service to Berlin, while Lufthansa and KLM offer convenient one-stop connections via their Frankfurt and Amsterdam hubs.'],
                    ['question' => 'When is the best time to fly to Berlin?', 'answer' => 'Winter months (November to March) deliver the lowest fares outside the Christmas rush. Summer offers the best weather at peak prices, and the autumn trade-fair season is busy.'],
                    ['question' => 'What should I know about Berlin\'s airport?', 'answer' => 'Berlin\'s single international airport, Brandenburg (BER), now handles all commercial traffic. It is well connected to central Berlin by train in about 30 minutes.'],
                ],
                'seasonal_tips' => [
                    'Berlin is at its cheapest and quietest in January and February — ideal for a city break.',
                    'Check the trade-fair calendar; BER prices spike during the IFA electronics show in early September.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'FCO',
                'destination_city' => 'Rome',
                'distance_miles' => 898,
                'avg_duration_minutes' => 150,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 119,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 119],
                    ['name' => 'ITA Airways', 'flight_type' => 'Direct', 'min_price' => 129],
                    ['name' => 'KLM', 'flight_type' => '1 stop', 'min_price' => 138],
                ],
                'faqs' => [
                    ['question' => 'How long is the flight from London to Rome?', 'answer' => 'A direct flight from London Heathrow to Rome Fiumicino (FCO) takes about 2 hours 30 minutes. One-stop itineraries via Amsterdam or Frankfurt take 4-6 hours.'],
                    ['question' => 'Which airlines fly direct from London to Rome?', 'answer' => 'British Airways and ITA Airways operate direct services from London Heathrow to Rome Fiumicino. FCO is Rome\'s main international gateway and well connected to the centre by train.'],
                    ['question' => 'What is the cheapest time to fly to Rome?', 'answer' => 'November and the weeks after the new year offer the lowest fares. Spring and autumn see the best weather with moderate prices, while summer and the Vatican Holy Year events push fares up.'],
                    ['question' => 'Do I need a visa to visit Rome from the UK?', 'answer' => 'British citizens can enter Italy visa-free for up to 90 days in 180 days under Schengen rules. Bring proof of travel insurance and a passport valid for your stay.'],
                ],
                'seasonal_tips' => [
                    'Rome in spring (April-May) is both beautiful and affordable if you book 6-8 weeks ahead.',
                    'Sundays and public holidays see quieter airports — an easy, cheaper window for a city break.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'BKK',
                'destination_city' => 'Bangkok',
                'distance_miles' => 5933,
                'avg_duration_minutes' => 690,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 499,
                'primary_airline' => 'Thai Airways',
                'airlines_operating' => [
                    ['name' => 'Thai Airways', 'flight_type' => 'Direct', 'min_price' => 499],
                    ['name' => 'EVA Air', 'flight_type' => '1 stop', 'min_price' => 460],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 465],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 470],
                    ['name' => 'Etihad Airways', 'flight_type' => '1 stop', 'min_price' => 458],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Bangkok?', 'answer' => 'A direct flight from London Heathrow to Bangkok Suvarnabhumi (BKK) takes about 11 hours 30 minutes. One-stop itineraries via the Gulf or East Asia typically take 13-16 hours.'],
                    ['question' => 'Which airlines fly direct from London to Bangkok?', 'answer' => 'Thai Airways operates the direct Heathrow to Bangkok service. Emirates, Qatar Airways, Etihad Airways and EVA Air offer convenient one-stop connections with strong comfort and meals.'],
                    ['question' => 'When is the best time to fly to Bangkok?', 'answer' => 'November to February is the cool, dry season with the best weather but peak prices. May to September brings rain and the lowest fares — still perfectly manageable for most trips.'],
                    ['question' => 'Do UK citizens need a visa for Thailand?', 'answer' => 'British citizens can enter Thailand visa-free for tourism for up to 30 days. Longer stays and extensions have specific requirements — check the rules before your trip.'],
                ],
                'seasonal_tips' => [
                    'Book 8-10 weeks ahead — long-haul fares to Bangkok climb in the final month.',
                    'Travelling in the rainy season (June-September) can cut fares by a quarter with mostly short afternoon showers.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'HND',
                'destination_city' => 'Tokyo',
                'distance_miles' => 5960,
                'avg_duration_minutes' => 720,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 599,
                'primary_airline' => 'All Nippon Airways',
                'airlines_operating' => [
                    ['name' => 'All Nippon Airways', 'flight_type' => 'Direct', 'min_price' => 599],
                    ['name' => 'Japan Airlines', 'flight_type' => 'Direct', 'min_price' => 605],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 620],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 560],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Tokyo?', 'answer' => 'A direct flight from London Heathrow to Tokyo Haneda (HND) takes about 12 hours. Haneda is the city airport, saving up to an hour of transfer time versus Narita on the outskirts.'],
                    ['question' => 'Which airlines fly direct from London to Tokyo?', 'answer' => 'All Nippon Airways, Japan Airlines and British Airways operate direct flights from London to Tokyo. ANA and JAL consistently top long-haul economy and premium-economy rankings.'],
                    ['question' => 'When is the best time to fly to Tokyo?', 'answer' => 'Spring (cherry-blossom season, late March-April) and autumn (October-November) are the most popular. January and early December offer lower fares with crisp, clear winter weather.'],
                    ['question' => 'Do UK citizens need a visa for Japan?', 'answer' => 'British citizens can visit Japan visa-free as tourists for up to 90 days. No ESTA or e-visa is required, making last-minute trips straightforward.'],
                ],
                'seasonal_tips' => [
                    'Booking 10-12 weeks ahead is key — Tokyo fares are among the most volatile long-haul prices.',
                    'Fly into Haneda (HND), not Narita, for the fastest access to central Tokyo.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'SIN',
                'destination_city' => 'Singapore',
                'distance_miles' => 6757,
                'avg_duration_minutes' => 780,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 545,
                'primary_airline' => 'Singapore Airlines',
                'airlines_operating' => [
                    ['name' => 'Singapore Airlines', 'flight_type' => 'Direct', 'min_price' => 545],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 570],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 525],
                    ['name' => 'Etihad Airways', 'flight_type' => '1 stop', 'min_price' => 528],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Singapore?', 'answer' => 'A direct flight from London Heathrow to Singapore Changi (SIN) takes about 13 hours. One-stop routes via Doha, Abu Dhabi or Dubai take between 15 and 18 hours.'],
                    ['question' => 'Which airlines fly direct from London to Singapore?', 'answer' => 'Singapore Airlines and British Airways operate direct flights from London Heathrow to Singapore. Singapore Airlines is renowned for its A380 service and inflight experience.'],
                    ['question' => 'When is the best time to fly to Singapore?', 'answer' => 'Singapore is warm year-round. February to April and October to November offer the driest conditions, while the December peak and Chinese New Year window are the most expensive.'],
                    ['question' => 'How does Singapore suit stopover travellers?', 'answer' => 'Singapore Changi is the world\'s best-connected hub — Singapore Airlines stopover packages can add a day or two in Singapore on the way to Australia, Bali or Malaysia.'],
                ],
                'seasonal_tips' => [
                    'Look for Singapore Airlines stopover deals — they can reduce the effective fare for a longer trip.',
                    'Book 8-12 weeks ahead; mid-year fares (May-June) are the most competitive.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'DPS',
                'destination_city' => 'Bali',
                'distance_miles' => 7658,
                'avg_duration_minutes' => 1030,
                'has_direct_flights' => false,
                'lowest_fare_gbp' => 615,
                'primary_airline' => 'Qatar Airways',
                'airlines_operating' => [
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 615],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 620],
                    ['name' => 'Singapore Airlines', 'flight_type' => '1 stop', 'min_price' => 630],
                    ['name' => 'Etihad Airways', 'flight_type' => '1 stop', 'min_price' => 625],
                ],
                'faqs' => [
                    ['question' => 'Are there direct flights from London to Bali?', 'answer' => 'No. There are currently no direct flights from London to Bali. All itineraries connect once, typically via Doha, Dubai, Abu Dhabi or Singapore, with total journey times of 16 to 20 hours.'],
                    ['question' => 'Which airlines fly from London to Bali?', 'answer' => 'Qatar Airways, Emirates, Singapore Airlines and Etihad Airways all connect London to Ngurah Rai International Airport (DPS) in Bali with a single stop.'],
                    ['question' => 'When is the best time to fly to Bali?', 'answer' => 'April to October is Bali\'s dry season with the most reliable sunshine. The shoulder months of April-May and September-October offer great weather with fares below the summer peak.'],
                    ['question' => 'Do UK citizens need a visa for Bali?', 'answer' => 'British citizens can enter Indonesia visa-free for tourism for up to 30 days. Entry requirements can change, so confirm the latest rules with Nurud Travels before you fly.'],
                ],
                'seasonal_tips' => [
                    'Book 3-4 months ahead — Bali long-haul fares jump once the dry-season calendar fills.',
                    'Choose an itinerary with a manageable layover in Doha, Dubai or Singapore to break up the 17-hour journey.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'BOM',
                'destination_city' => 'Mumbai',
                'distance_miles' => 4520,
                'avg_duration_minutes' => 540,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 449,
                'primary_airline' => 'Air India',
                'airlines_operating' => [
                    ['name' => 'Air India', 'flight_type' => 'Direct', 'min_price' => 449],
                    ['name' => 'Virgin Atlantic', 'flight_type' => 'Direct', 'min_price' => 465],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 470],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 440],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 448],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Mumbai?', 'answer' => 'A direct flight from London Heathrow to Mumbai Chhatrapati Shivaji Maharaj International (BOM) takes about 9 hours. One-stop Gulf itineraries take 11 to 13 hours.'],
                    ['question' => 'Which airlines fly direct from London to Mumbai?', 'answer' => 'Air India, Virgin Atlantic and British Airways operate direct services from London Heathrow to Mumbai. BOM is India\'s busiest airport for international arrivals.'],
                    ['question' => 'When is the best time to fly to Mumbai?', 'answer' => 'November to February is the cool, dry season with the best weather. The post-monsoon shoulder (September-October) offers good value before the peak festive travel period.'],
                    ['question' => 'Do UK citizens need a visa for India?', 'answer' => 'Yes, British citizens need an Indian e-visa before travel. Apply early and carry the approved visa printout plus a passport valid for at least six months from arrival.'],
                ],
                'seasonal_tips' => [
                    'Mumbai\'s festive season (Diwali, October-November) fills fast — book 2-3 months ahead.',
                    'Monsoon months (June-September) bring the lowest fares of the year despite the heavy rain.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'NBO',
                'destination_city' => 'Nairobi',
                'distance_miles' => 4172,
                'avg_duration_minutes' => 540,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 529,
                'primary_airline' => 'Kenya Airways',
                'airlines_operating' => [
                    ['name' => 'Kenya Airways', 'flight_type' => 'Direct', 'min_price' => 529],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 560],
                    ['name' => 'Ethiopian Airlines', 'flight_type' => '1 stop', 'min_price' => 505],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 515],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Nairobi?', 'answer' => 'A direct flight from London Heathrow to Nairobi Jomo Kenyatta International (NBO) takes about 9 hours. One-stop itineraries via Addis Ababa or Doha take 11 to 14 hours.'],
                    ['question' => 'Which airlines fly direct from London to Nairobi?', 'answer' => 'Kenya Airways and British Airways operate direct services from London Heathrow to Nairobi. Ethiopian Airlines, Qatar Airways and Emirates offer convenient one-stop connections.'],
                    ['question' => 'When is the best time to fly to Nairobi?', 'answer' => 'June to October and December to February cover the dry seasons with the best game-viewing and weather. April to May is the wettest, quietest and cheapest window.'],
                    ['question' => 'Do UK citizens need a visa for Kenya?', 'answer' => 'Yes, British citizens need an e-visa before arrival in Kenya. Apply online at least a few days ahead and carry a printed copy plus a passport valid for at least six months.'],
                ],
                'seasonal_tips' => [
                    'For safari, book 3-4 months ahead — dry-season departures sell out and prices rise.',
                    'Fly on a Tuesday or Wednesday for the softest fares on this route.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'CAI',
                'destination_city' => 'Cairo',
                'distance_miles' => 2186,
                'avg_duration_minutes' => 300,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 189,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 189],
                    ['name' => 'EgyptAir', 'flight_type' => 'Direct', 'min_price' => 195],
                    ['name' => 'easyJet', 'flight_type' => 'Direct', 'min_price' => 175],
                    ['name' => 'Turkish Airlines', 'flight_type' => '1 stop', 'min_price' => 220],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 235],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Cairo?', 'answer' => 'A direct flight from London Heathrow to Cairo International (CAI) takes about 5 hours. One-stop itineraries via Istanbul, Dubai or Doha take 7 to 9 hours.'],
                    ['question' => 'Which airlines fly direct from London to Cairo?', 'answer' => 'British Airways, EgyptAir and easyJet operate direct services from London to Cairo. easyJet flies from Gatwick and is often the most budget-friendly option.'],
                    ['question' => 'When is the best time to fly to Cairo?', 'answer' => 'October to April is the mild, comfortable season and the best time for sightseeing. June to August is fiercely hot with the lowest fares of the year.'],
                    ['question' => 'Do UK citizens need a visa for Egypt?', 'answer' => 'British citizens need a tourist e-visa for Egypt, available online before travel. Some arrivals are eligible for visa-on-arrival — confirm the current rules and carry proof of return travel.'],
                ],
                'seasonal_tips' => [
                    'Book 6-8 weeks ahead — Cairo is a strong value short-haul-plus route outside major holidays.',
                    'Avoid travelling during Ramadan peak breaks and Christmas for the best prices.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'ADD',
                'destination_city' => 'Addis Ababa',
                'distance_miles' => 3799,
                'avg_duration_minutes' => 480,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 479,
                'primary_airline' => 'Ethiopian Airlines',
                'airlines_operating' => [
                    ['name' => 'Ethiopian Airlines', 'flight_type' => 'Direct', 'min_price' => 479],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 485],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 495],
                    ['name' => 'Turkish Airlines', 'flight_type' => '1 stop', 'min_price' => 505],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Addis Ababa?', 'answer' => 'A direct flight from London Heathrow to Addis Ababa Bole International (ADD) takes about 8 hours. One-stop itineraries via Doha, Dubai or Istanbul take 10 to 13 hours.'],
                    ['question' => 'Which airlines fly direct from London to Addis Ababa?', 'answer' => 'Ethiopian Airlines is the only direct carrier on the route, flying from London Heathrow to Bole International. Qatar Airways, Emirates and Turkish Airlines connect with a single stop.'],
                    ['question' => 'When is the best time to fly to Addis Ababa?', 'answer' => 'The dry seasons of October to February and late June to September offer the best weather and travel conditions. April to early June is the short wet season with softer fares.'],
                    ['question' => 'Do UK citizens need a visa for Ethiopia?', 'answer' => 'Yes, British citizens need an e-visa for Ethiopia, applied for online before arrival at Bole International. Carry a printed approval and a passport valid for at least six months.'],
                ],
                'seasonal_tips' => [
                    'Ethiopian Airlines hub connections onward to East Africa make ADD a great value gateway.',
                    'Book 8-10 weeks ahead; late January to February and September shoulder windows are cheapest.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'NCE',
                'destination_city' => 'Nice',
                'distance_miles' => 645,
                'avg_duration_minutes' => 130,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 89,
                'primary_airline' => 'British Airways',
                'airlines_operating' => [
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 89],
                    ['name' => 'Air France', 'flight_type' => '1 stop', 'min_price' => 120],
                    ['name' => 'easyJet', 'flight_type' => 'Direct', 'min_price' => 45],
                    ['name' => 'Lufthansa', 'flight_type' => '1 stop', 'min_price' => 135],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Nice?', 'answer' => 'A direct flight from London Heathrow to Nice Côte d\'Azur (NCE) takes about 2 hours 10 minutes. easyJet flies directly from Gatwick, making the Riviera an easy weekend trip.'],
                    ['question' => 'Which airlines fly direct from London to Nice?', 'answer' => 'British Airways flies from Heathrow while easyJet serves the route from Gatwick. Air France and Lufthansa offer one-stop connections via Paris or Frankfurt.'],
                    ['question' => 'When is the best time to fly to Nice?', 'answer' => 'May to September is the Riviera season with warm days and the Cannes Film Festival and Grand Prix drawing crowds. October and the low season are the cheapest while still pleasant.'],
                    ['question' => 'Do UK citizens need a visa to visit Nice?', 'answer' => 'British citizens can enter France visa-free for up to 90 days in 180 days under Schengen rules. Bring proof of travel insurance and a passport valid for your stay.'],
                ],
                'seasonal_tips' => [
                    'Book 6-8 weeks ahead for an autumn or spring Riviera break.',
                    'Mid-week departures and travelling outside the Cannes/Grand Prix weekends keep fares lowest.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'SYD',
                'destination_city' => 'Sydney',
                'distance_miles' => 10562,
                'avg_duration_minutes' => 1330,
                'has_direct_flights' => false,
                'lowest_fare_gbp' => 699,
                'primary_airline' => 'Singapore Airlines',
                'airlines_operating' => [
                    ['name' => 'Singapore Airlines', 'flight_type' => '1 stop', 'min_price' => 699],
                    ['name' => 'Qantas', 'flight_type' => '1 stop', 'min_price' => 720],
                    ['name' => 'Emirates', 'flight_type' => '1 stop', 'min_price' => 710],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 715],
                    ['name' => 'Etihad Airways', 'flight_type' => '1 stop', 'min_price' => 725],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Sydney?', 'answer' => 'There are no direct flights from London to Sydney. All itineraries connect once, typically via Singapore, Doha, Dubai or Abu Dhabi, with total journey times of 21 to 24 hours.'],
                    ['question' => 'Which airlines fly from London to Sydney?', 'answer' => 'Qantas, Singapore Airlines, Emirates, Qatar Airways and Etihad Airways connect London to Sydney Kingsford Smith (SYD) with a single stop.'],
                    ['question' => 'When is the best time to fly to Sydney?', 'answer' => 'Australian summer (December to February) is peak and most expensive. Autumn (March-May) and spring (September-November) offer great weather with more competitive fares.'],
                    ['question' => 'Do UK citizens need a visa for Australia?', 'answer' => 'Yes, British citizens need an Australian ETA (Electronic Travel Authority) via the official Australian ETA app before travel. Apply ahead and carry your passport valid for the whole stay.'],
                ],
                'seasonal_tips' => [
                    'Book 3-4 months ahead — Sydney fares are among the most expensive long-haul routes from London.',
                    'Consider a layover in Singapore or Doha to break up the 22-hour journey.',
                ],
            ],
            [
                'origin' => 'LHR',
                'destination' => 'IST',
                'destination_city' => 'Istanbul',
                'distance_miles' => 1810,
                'avg_duration_minutes' => 250,
                'has_direct_flights' => true,
                'lowest_fare_gbp' => 149,
                'primary_airline' => 'Turkish Airlines',
                'airlines_operating' => [
                    ['name' => 'Turkish Airlines', 'flight_type' => 'Direct', 'min_price' => 149],
                    ['name' => 'British Airways', 'flight_type' => 'Direct', 'min_price' => 165],
                    ['name' => 'Pegasus Airlines', 'flight_type' => '1 stop', 'min_price' => 130],
                    ['name' => 'Qatar Airways', 'flight_type' => '1 stop', 'min_price' => 185],
                ],
                'faqs' => [
                    ['question' => 'How long does it take to fly from London to Istanbul?', 'answer' => 'A direct flight from London Heathrow to Istanbul Airport (IST) takes about 4 hours 10 minutes. Pegasus routes via Sabiha Gökçen take slightly longer with a transfer.'],
                    ['question' => 'Which airlines fly direct from London to Istanbul?', 'answer' => 'Turkish Airlines and British Airways operate direct flights from London to Istanbul. Turkish Airlines serves both Istanbul Airport and Sabiha Gökçen (SAW) for more schedule choice.'],
                    ['question' => 'When is the best time to fly to Istanbul?', 'answer' => 'April to June and September to October offer mild weather and manageable crowds. June to August is warm and lively, while winter is the quietest and cheapest season.'],
                    ['question' => 'Do UK citizens need a visa for Turkey?', 'answer' => 'Tourist visits of up to 90 days in 180 days are visa-free for British citizens. Carry a passport valid for at least 150 days beyond your departure from Turkey.'],
                ],
                'seasonal_tips' => [
                    'Book 6-8 weeks ahead for spring or autumn; flights from Istanbul onward are excellent value.',
                    'Fly midweek and avoid school half-terms for the softest fares on this short hop.',
                ],
            ],
        ];

        foreach ($routes as $data) {
            $origin = $airports[$data['origin']] ?? null;
            $destination = $airports[$data['destination']] ?? null;

            if (!$origin || !$destination) {
                $this->command->warn("Skipping route {$data['origin']}-{$data['destination']}: airport not found in airports.json");
                continue;
            }

            $originCity = $this->cityFrom($origin['state_name'] ?? $origin['code']);
            $destinationCity = $data['destination_city'] ?? $this->cityFrom($destination['state_name'] ?? $destination['code']);

            $slug = strtolower(Str::slug($originCity) . '-' . $data['origin'] . '-to-' . Str::slug($destinationCity) . '-' . $data['destination']);

            FlightRoute::updateOrCreate(
                ['slug' => $slug],
                [
                    'origin_code' => $data['origin'],
                    'destination_code' => $data['destination'],
                    'distance_miles' => $data['distance_miles'],
                    'avg_duration_minutes' => $data['avg_duration_minutes'],
                    'has_direct_flights' => $data['has_direct_flights'],
                    'lowest_fare_gbp' => $data['lowest_fare_gbp'],
                    'primary_airline' => $data['primary_airline'],
                    'is_indexable' => true,
                    'search_intent_metadata' => [
                        'origin' => $data['origin'],
                        'destination' => $data['destination'],
                        'destination_city' => $destinationCity,
                        'airlines_operating' => $data['airlines_operating'],
                        'faqs' => $data['faqs'],
                        'seasonal_tips' => $data['seasonal_tips'],
                    ],
                ]
            );

            $this->command->info("Seeded route: {$slug}");
        }

        Cache::forget('airports_lookup_code_map');

        $this->command->info('Flight routes seeded successfully!');
    }

    /**
     * Load airports keyed by uppercase IATA code from airports.json.
     */
    private function loadAirports(): array
    {
        $path = database_path('data/airports.json');

        if (!File::exists($path)) {
            $this->command->error("airports.json not found at: $path");
            return [];
        }

        $raw = json_decode(File::get($path), true);

        $keyed = [];
        foreach ($raw as $airport) {
            $keyed[strtoupper((string) ($airport['code'] ?? ''))] = $airport;
        }

        return $keyed;
    }

    /**
     * Derive a clean city name from an airport state/city value.
     * e.g. "London, England" -> "London", "Johannesburg, Gauteng" -> "Johannesburg".
     */
    private function cityFrom(string $value): string
    {
        return trim(explode(',', $value)[0]);
    }
}