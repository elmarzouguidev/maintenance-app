# TicketSeeder - Development Data Generator

## Overview
The `TicketSeeder` class generates realistic fake data for tickets in development mode, creating a comprehensive set of tickets across all possible states and scenarios that CasaMaintenance might encounter.

## Features

### **Realistic Equipment Data**
- **PLCs**: Siemens S7-1200, Schneider Modicon M221
- **Variable Speed Drives**: Schneider Altivar, ABB ACS880, Mitsubishi MR-JE
- **HMI Interfaces**: Pro-Face, Weintek, Delta
- **Industrial PCs**: Advantech UNO series
- **Power Supplies**: Mean Well, various voltage/current ratings
- **Electronic Cards**: Communication, CPU, I/O modules

### **Complete Workflow Coverage**
The seeder creates tickets representing every stage of the maintenance workflow:

1. **New Tickets** (Non diagnostiqué, Non traité)
2. **Diagnostic Phase** (En cours de diagnostic)
3. **Repair Phase** (En cours de réparation)
4. **Waiting for Estimate** (En attente de devis)
5. **Waiting for Purchase Order** (En attente de bon de commande)
6. **Ready to Deliver** (Prêt à être livré)
7. **Delivered** (Livré)
8. **Non-Repairable** (Non réparable)
9. **Return Tickets** (Retours)
10. **Invoiceable** (Prêt à être facturé)

### **Realistic Scenarios**
- Equipment failures with technical descriptions
- Diagnostic processes
- Repair procedures
- Client communication scenarios
- Return and modification requests

## Usage

### **Run Complete Seeder**
```bash
php artisan db:seed --class=TicketSeeder
```

### **Run with DatabaseSeeder**
```bash
php artisan db:seed
```

### **Development Command**
```bash
php artisan seed:tickets
```

## Data Structure

### **Ticket Categories Created**

#### **New Tickets (5 tickets)**
- Status: `NON_DIAGNOSTIQUER` / `NON_TRAITE`
- No assigned technician
- Recent creation dates (1-30 days ago)
- Realistic equipment failures

#### **Diagnostic Tickets (2 tickets)**
- Status: `NON_DIAGNOSTIQUER` / `EN_COURS_DE_DIAGNOSTIC`
- Assigned technicians
- Started dates set
- Can generate reports

#### **Repair Tickets (2 tickets)**
- Status: `REPARABLE` / `EN_COURS_DE_REPARATION`
- Assigned technicians
- Started dates
- Repair descriptions

#### **Waiting Estimate Tickets (2 tickets)**
- Status: `REPARABLE` / `EN_ATTENTE_DE_DEVIS`
- Diagnostic completed
- Waiting for client approval

#### **Waiting Order Tickets (1 ticket)**
- Status: `REPARABLE` / `EN_ATTENTE_DE_BON_DE_COMMAND`
- Estimate accepted
- Waiting for purchase order

#### **Ready to Deliver Tickets (2 tickets)**
- Status: `REPARABLE` / `PRET_A_ETRE_LIVRE`
- Repair completed
- Ready for delivery
- `livrable = true`

#### **Delivered Tickets (2 tickets)**
- Status: `REPARABLE` / `LIVRE`
- Completed and delivered
- Can be invoiced
- Finished dates set

#### **Non-Repairable Tickets (2 tickets)**
- Status: `NON_REPARABLE` / `RETOUR_NON_REPARABLE`
- Equipment beyond repair
- Can be invoiced for diagnostic

#### **Return Tickets (1 ticket)**
- Status: `REPARABLE` / `EN_COURS_DE_REPARATION`
- `is_retour = true`
- Return numbers assigned
- Modification requests

#### **Invoiceable Tickets (2 tickets)**
- Status: `REPARABLE` / `PRET_A_ETRE_FACTURE`
- Delivered and validated
- Ready for billing

## Technical Details

### **Dependencies**
- Requires existing `Client` records
- Requires existing `User` records (technicians)
- Uses `Etat` and `Status` constants

### **Data Relationships**
- Random client assignment
- Random technician assignment
- Realistic date ranges
- Proper status transitions

### **Field Coverage**
- All required fields populated
- Realistic article references
- Technical descriptions
- Proper boolean flags
- Date ranges for workflow

## Business Logic

### **Status Progression**
The seeder creates tickets that follow realistic business workflows:

```
New → Diagnostic → Repair → Estimate → Order → Ready → Delivered → Invoiceable
```

### **Date Logic**
- **Created dates**: Spread across last 70 days
- **Started dates**: Based on workflow stage
- **Finished dates**: Only for completed tickets
- **Realistic timeframes**: Matches real business processes

### **Financial Flags**
- `can_invoiced`: Set appropriately for each stage
- `livrable`: Only for ready/delivered tickets
- `can_make_report`: Based on workflow stage

## Development Benefits

### **Testing Scenarios**
- Test all filter combinations
- Validate date range filtering
- Test status-based workflows
- Verify financial calculations

### **UI Development**
- Realistic data for interface testing
- Various ticket states for display testing
- Date ranges for calendar features
- Client/technician relationships

### **Business Logic Testing**
- Workflow validation
- Status transition testing
- Financial flag verification
- Report generation testing

## Customization

### **Adding More Tickets**
```php
// In TicketSeeder.php
private function createCustomTickets($clients, $users)
{
    $ticketData = [
        [
            'article' => 'Your Equipment',
            'article_reference' => 'REF-001',
            'description' => 'Your description',
        ],
    ];
    
    foreach ($ticketData as $data) {
        Ticket::create(array_merge($data, [
            'client_id' => $clients->random()->id,
            'user_id' => $users->random()->id,
            'etat' => Etat::REPARABLE,
            'status' => Status::EN_COURS_DE_REPARATION,
            // ... other fields
        ]));
    }
}
```

### **Modifying Equipment Types**
Update the `$ticketData` arrays in each method to include different equipment types relevant to your business.

### **Adjusting Quantities**
Modify the number of tickets created in each category by adding more entries to the `$ticketData` arrays.

## Notes

- **Environment**: Designed for development/testing only
- **Dependencies**: Requires clients and users to exist first
- **Realism**: Based on actual industrial electronics maintenance scenarios
- **Flexibility**: Easy to modify for specific testing needs

This seeder provides a comprehensive foundation for testing all aspects of the ticket management system with realistic, business-relevant data.
