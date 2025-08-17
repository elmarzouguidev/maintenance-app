<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TechnicienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create default technician if it doesn't exist
        $this->createDefaultTechnicien();

        // Create additional technicians with realistic data
        $this->createTechniciens();
    }

    /**
     * Create the default technician
     */
    private function createDefaultTechnicien()
    {
        $user = [
            'nom' => 'ELouahabi',
            'prenom' => 'Ahmed',
            'telephone' => '062222222222222',
            'email' => 'technicien@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456789@'),
            'clear_password' => '123456789@',
            'public_password' => '123456789@',
            'active' => true,
            'remember_token' => Str::random(10),
        ];

        $technicien = User::whereEmail('technicien@gmail.com')->first();

        if (!$technicien) {
            $newTechnicien = User::create($user);
            $newTechnicien->assignRole('Technicien');
            $this->command->info('Default technician created: Ahmed ELouahabi');
        } else {
            $technicien->assignRole('Technicien');
            $this->command->info('Default technician already exists: Ahmed ELouahabi');
        }
    }

    /**
     * Generate a unique phone number
     */
    private function generateUniquePhone()
    {
        $prefixes = ['06', '07'];
        $prefix = $prefixes[array_rand($prefixes)];
        
        do {
            $suffix = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $phone = $prefix . $suffix;
            
            $exists = User::where('telephone', $phone)->exists();
        } while ($exists);
        
        return $phone;
    }

    /**
     * Create multiple technicians with realistic data
     */
    private function createTechniciens()
    {
        $techniciens = [
            [
                'nom' => 'Bennani',
                'prenom' => 'Karim',
                'email' => 'karim.bennani@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'PLCs et Automatisation',
            ],
            [
                'nom' => 'Alaoui',
                'prenom' => 'Fatima',
                'email' => 'fatima.alaoui@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Variateurs de Vitesse',
            ],
            [
                'nom' => 'Tazi',
                'prenom' => 'Mohammed',
                'email' => 'mohammed.tazi@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Interfaces HMI',
            ],
            [
                'nom' => 'Chraibi',
                'prenom' => 'Sara',
                'email' => 'sara.chraibi@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Cartes Électroniques',
            ],
            [
                'nom' => 'Mansouri',
                'prenom' => 'Youssef',
                'email' => 'youssef.mansouri@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'PCs Industriels',
            ],
            [
                'nom' => 'Rachidi',
                'prenom' => 'Amina',
                'email' => 'amina.rachidi@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Alimentations et Électrique',
            ],
            [
                'nom' => 'El Fassi',
                'prenom' => 'Hassan',
                'email' => 'hassan.elfassi@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Systèmes de Communication',
            ],
            [
                'nom' => 'Bouazza',
                'prenom' => 'Nadia',
                'email' => 'nadia.bouazza@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Capteurs et Instrumentation',
            ],
            [
                'nom' => 'Khalil',
                'prenom' => 'Omar',
                'email' => 'omar.khalil@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Moteurs et Entraînements',
            ],
            [
                'nom' => 'Zeroual',
                'prenom' => 'Leila',
                'email' => 'leila.zeroual@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Systèmes de Sécurité',
            ],
            [
                'nom' => 'Benjelloun',
                'prenom' => 'Rachid',
                'email' => 'rachid.benjelloun@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'CNC et Machines-Outils',
            ],
            [
                'nom' => 'Lahlou',
                'prenom' => 'Samira',
                'email' => 'samira.lahlou@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Robots Industriels',
            ],
            [
                'nom' => 'Mekouar',
                'prenom' => 'Adil',
                'email' => 'adil.mekouar@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Systèmes Hydrauliques',
            ],
            [
                'nom' => 'Touimi',
                'prenom' => 'Khadija',
                'email' => 'khadija.touimi@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Systèmes Pneumatiques',
            ],
            [
                'nom' => 'Boukhari',
                'prenom' => 'Abdelkader',
                'email' => 'abdelkader.boukhari@casamaintenance.ma',
                'password' => 'Tech2024!',
                'specialization' => 'Systèmes de Vision',
            ],
        ];

        $createdCount = 0;
        $existingCount = 0;
        $skippedCount = 0;

        foreach ($techniciens as $technicienData) {
            // Check for existing user by email
            $existingUser = User::where('email', $technicienData['email'])->first();

            if (!$existingUser) {
                try {
                    // Generate unique phone number
                    $uniquePhone = $this->generateUniquePhone();
                    
                    $user = [
                        'nom' => $technicienData['nom'],
                        'prenom' => $technicienData['prenom'],
                        'telephone' => $uniquePhone,
                        'email' => $technicienData['email'],
                        'email_verified_at' => now(),
                        'password' => Hash::make($technicienData['password']),
                        'clear_password' => $technicienData['password'],
                        'public_password' => $technicienData['password'],
                        'active' => true,
                        'remember_token' => Str::random(10),
                    ];

                    $newTechnicien = User::create($user);
                    $newTechnicien->assignRole('Technicien');
                    $createdCount++;

                    $this->command->info("Technician created: {$technicienData['prenom']} {$technicienData['nom']} - {$technicienData['specialization']} (Phone: {$uniquePhone})");
                } catch (\Exception $e) {
                    $skippedCount++;
                    $this->command->warn("Failed to create technician {$technicienData['prenom']} {$technicienData['nom']}: " . $e->getMessage());
                }
            } else {
                // Check if user already has Technicien role
                if (!$existingUser->hasRole('Technicien')) {
                    $existingUser->assignRole('Technicien');
                    $this->command->info("Role assigned to existing user: {$technicienData['prenom']} {$technicienData['nom']}");
                }
                $existingCount++;
            }
        }

        $this->command->info("TechnicienSeeder completed!");
        $this->command->info("Created: {$createdCount} new technicians");
        $this->command->info("Existing: {$existingCount} technicians already existed");
        $this->command->info("Skipped: {$skippedCount} technicians due to errors");
        $this->command->info("Total technicians: " . ($createdCount + $existingCount + 1)); // +1 for default technician
    }
}
