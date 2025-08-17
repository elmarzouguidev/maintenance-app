<?php

namespace Database\Seeders;

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get existing clients and users for realistic relationships
        $clients = Client::all();
        $users = User::all();
        
        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Creating tickets without client relationships.');
            return;
        }

        // Create tickets with different states and scenarios
        $this->createNewTickets($clients, $users);
        $this->createDiagnosticTickets($clients, $users);
        $this->createRepairTickets($clients, $users);
        $this->createWaitingEstimateTickets($clients, $users);
        $this->createWaitingOrderTickets($clients, $users);
        $this->createReadyToDeliverTickets($clients, $users);
        $this->createDeliveredTickets($clients, $users);
        $this->createNonRepairableTickets($clients, $users);
        $this->createReturnTickets($clients, $users);
        $this->createInvoiceableTickets($clients, $users);

        $this->command->info('TicketSeeder completed successfully!');
    }

    /**
     * Create new tickets (non diagnostiqué, non traité)
     */
    private function createNewTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'PLC Siemens S7-1200 CPU 1214C',
                'article_reference' => '6ES7214-1AE40-0XB0',
                'description' => 'Le PLC ne démarre pas après une coupure de courant. Affichage d\'erreur E001 sur l\'écran LCD.',
            ],
            [
                'article' => 'Variateur de vitesse Schneider Altivar 71',
                'article_reference' => 'ATV71HD15N4',
                'description' => 'Le variateur affiche une erreur de surcharge moteur. Le moteur ne tourne plus malgré les commandes.',
            ],
            [
                'article' => 'Interface HMI Pro-Face GP-4501TW',
                'article_reference' => 'GP-4501TW-41',
                'description' => 'L\'écran tactile ne répond plus aux commandes. Affichage déformé et clignotant.',
            ],
            [
                'article' => 'Carte électronique de puissance',
                'article_reference' => 'PWR-24V-10A',
                'description' => 'La carte de puissance ne délivre plus la tension 24V. Fuse grillé et composants endommagés.',
            ],
            [
                'article' => 'PC Industriel Advantech UNO-2184G',
                'article_reference' => 'UNO-2184G-2E1E',
                'description' => 'Le PC industriel ne démarre plus. Ventilateur bruyant et écran bleu au démarrage.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'etat' => Etat::NON_DIAGNOSTIQUER,
                'status' => Status::NON_TRAITE,
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => false,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]));
        }
    }

    /**
     * Create tickets in diagnostic phase
     */
    private function createDiagnosticTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Servo variateur Mitsubishi MR-JE-20A',
                'article_reference' => 'MR-JE-20A',
                'description' => 'Diagnostic en cours pour déterminer la cause de la perte de couple moteur.',
            ],
            [
                'article' => 'Commande CNC Fanuc Series 0i-MD',
                'article_reference' => 'A02B-0309-B501',
                'description' => 'Analyse des erreurs de communication entre la commande et les axes.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::NON_DIAGNOSTIQUER,
                'status' => Status::EN_COURS_DE_DIAGNOSTIC,
                'started_at' => Carbon::now()->subDays(rand(1, 5)),
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(5, 15)),
            ]));
        }
    }

    /**
     * Create tickets in repair phase
     */
    private function createRepairTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Alimentation 24V 20A Mean Well',
                'article_reference' => 'SP-240-20',
                'description' => 'Réparation en cours : remplacement des condensateurs et test de charge.',
            ],
            [
                'article' => 'Carte CPU Siemens S7-300',
                'article_reference' => '6ES7315-2AH14-0AB0',
                'description' => 'Réparation de la carte CPU : nettoyage et remplacement des composants défaillants.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::EN_COURS_DE_REPARATION,
                'started_at' => Carbon::now()->subDays(rand(2, 10)),
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(10, 25)),
            ]));
        }
    }

    /**
     * Create tickets waiting for estimate
     */
    private function createWaitingEstimateTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Panneau de commande tactile Weintek',
                'article_reference' => 'MT8071iE',
                'description' => 'Diagnostic terminé. En attente de devis pour remplacement de l\'écran tactile.',
            ],
            [
                'article' => 'Module d\'entrée/sortie Siemens',
                'article_reference' => '6ES7321-1BH02-0AA0',
                'description' => 'Module endommagé par surtension. Devis en cours pour remplacement.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::EN_ATTENTE_DE_DEVIS,
                'started_at' => Carbon::now()->subDays(rand(5, 15)),
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(15, 30)),
            ]));
        }
    }

    /**
     * Create tickets waiting for purchase order
     */
    private function createWaitingOrderTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Variateur de vitesse ABB ACS880',
                'article_reference' => 'ACS880-01-025A-3',
                'description' => 'Devis accepté. En attente du bon de commande pour commander les pièces.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::EN_ATTENTE_DE_BON_DE_COMMAND,
                'started_at' => Carbon::now()->subDays(rand(10, 20)),
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(20, 35)),
            ]));
        }
    }

    /**
     * Create tickets ready to be delivered
     */
    private function createReadyToDeliverTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'PLC Schneider Modicon M221',
                'article_reference' => 'TM221CE16R',
                'description' => 'Réparation terminée avec succès. Tests validés. Prêt pour livraison.',
            ],
            [
                'article' => 'Interface HMI Delta DOP-B07S411',
                'article_reference' => 'DOP-B07S411',
                'description' => 'Écran remplacé et programmé. Fonctionnel et prêt à livrer.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::PRET_A_ETRE_LIVRE,
                'started_at' => Carbon::now()->subDays(rand(15, 30)),
                'finished_at' => Carbon::now()->subDays(rand(1, 5)),
                'can_invoiced' => false,
                'livrable' => true,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(25, 40)),
            ]));
        }
    }

    /**
     * Create delivered tickets
     */
    private function createDeliveredTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Carte électronique de communication',
                'article_reference' => 'COM-ETH-01',
                'description' => 'Carte réparée et livrée au client. Fonctionne parfaitement.',
            ],
            [
                'article' => 'Alimentation 12V 5A',
                'article_reference' => 'PSU-12V-5A',
                'description' => 'Alimentation remplacée et livrée. Installation validée par le client.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::LIVRE,
                'started_at' => Carbon::now()->subDays(rand(20, 45)),
                'finished_at' => Carbon::now()->subDays(rand(5, 15)),
                'can_invoiced' => true,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(30, 60)),
            ]));
        }
    }

    /**
     * Create non-repairable tickets
     */
    private function createNonRepairableTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Ancien PLC obsolète',
                'article_reference' => 'PLC-OLD-001',
                'description' => 'Équipement trop ancien, pièces non disponibles. Recommandation de remplacement.',
            ],
            [
                'article' => 'Carte électronique endommagée',
                'article_reference' => 'CARD-DAMAGED-01',
                'description' => 'Dégâts irréversibles causés par l\'eau. Réparation impossible.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::NON_REPARABLE,
                'status' => Status::RETOUR_NON_REPARABLE,
                'started_at' => Carbon::now()->subDays(rand(10, 25)),
                'finished_at' => Carbon::now()->subDays(rand(1, 10)),
                'can_invoiced' => true,
                'livrable' => true,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(20, 40)),
            ]));
        }
    }

    /**
     * Create return tickets
     */
    private function createReturnTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'PLC retourné pour modification',
                'article_reference' => 'PLC-RETURN-01',
                'description' => 'Retour du PLC pour modification du programme selon nouvelles spécifications.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::EN_COURS_DE_REPARATION,
                'started_at' => Carbon::now()->subDays(rand(1, 5)),
                'can_invoiced' => false,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => true,
                'retour_number' => rand(1, 3),
                'created_at' => Carbon::now()->subDays(rand(5, 15)),
            ]));
        }
    }

    /**
     * Create invoiceable tickets
     */
    private function createInvoiceableTickets($clients, $users)
    {
        $ticketData = [
            [
                'article' => 'Variateur de vitesse réparé',
                'article_reference' => 'VFD-REPAIRED-01',
                'description' => 'Variateur réparé et livré. Prêt pour facturation.',
            ],
            [
                'article' => 'Interface HMI reprogrammée',
                'article_reference' => 'HMI-PROG-01',
                'description' => 'Interface reprogrammée selon nouvelles exigences. Livrée et validée.',
            ],
        ];

        foreach ($ticketData as $data) {
            Ticket::create(array_merge($data, [
                'client_id' => $clients->random()->id,
                'user_id' => $users->random()->id,
                'etat' => Etat::REPARABLE,
                'status' => Status::PRET_A_ETRE_FACTURE,
                'started_at' => Carbon::now()->subDays(rand(25, 50)),
                'finished_at' => Carbon::now()->subDays(rand(10, 20)),
                'can_invoiced' => true,
                'livrable' => false,
                'can_make_report' => true,
                'is_retour' => false,
                'created_at' => Carbon::now()->subDays(rand(35, 70)),
            ]));
        }
    }
}
