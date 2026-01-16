<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define the path to your CSV file
        $csvPath = database_path('data/airports.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("File not found at: $csvPath");
            return;
        }

        // 2. Read the CSV using LazyCollection to maintain memory efficiency
        LazyCollection::make(function () use ($csvPath) {
            $handle = fopen($csvPath, 'r');
            
            // Skip the header row
            fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                // Map CSV columns to variables for clarity
                // CSV Structure: code(0), icao(1), name(2), ..., country(9), city(10), ...
                
                $code = $row[0] ?? null;
                $name = $row[2] ?? null;
                $country = $row[9] ?? null; // 'country' column
                $city = $row[10] ?? null;   // 'city' column

                // 3. Validation: Only process valid 3-letter IATA codes
                if ($code && strlen($code) === 3) {
                    yield [
                        'code'       => $code,
                        'name'       => mb_substr($name ?? 'Unknown Airport', 0, 255),
                        // Use "Unknown" or empty string because DB columns are not nullable
                        'city'       => $city ?: 'Unknown', 
                        'country'    => $country ?: 'Unknown',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            fclose($handle);
        })
        // 4. Chunk the data to insert 1000 records at a time
        ->chunk(1000)
        ->each(function (LazyCollection $chunk) {
            // 5. Upsert prevents duplicates if run multiple times (matches on 'code')
            DB::table('airports')->upsert(
                $chunk->all(), 
                ['code'], // Unique column to check
                ['name', 'city', 'country', 'updated_at'] // Columns to update if exists
            );
        });

        $this->command->info('Airports table seeded successfully!');
    }
}