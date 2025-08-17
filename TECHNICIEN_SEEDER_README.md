# TechnicienSeeder - Development Data Generator

## Overview
The `TechnicienSeeder` class generates realistic fake data for technician users in development mode, creating a comprehensive set of technicians with various specializations that CasaMaintenance might employ.

## Features

### **Realistic Technician Data**
- **15 Specialized Technicians**: Each with unique expertise areas
- **Moroccan Names**: Authentic Moroccan names and surnames
- **Professional Emails**: Company email format (@casamaintenance.ma)
- **Valid Phone Numbers**: Moroccan mobile number format
- **Technical Specializations**: Realistic areas of expertise

### **Complete User Setup**
- **Role Assignment**: All users automatically assigned 'Technicien' role
- **Password Management**: Both hashed and clear passwords stored
- **Account Status**: All accounts set as active
- **Email Verification**: All emails marked as verified

### **Specialization Coverage**
The seeder creates technicians covering all major areas of industrial maintenance:

1. **PLCs et Automatisation** - Karim Bennani
2. **Variateurs de Vitesse** - Fatima Alaoui
3. **Interfaces HMI** - Mohammed Tazi
4. **Cartes Électroniques** - Sara Chraibi
5. **PCs Industriels** - Youssef Mansouri
6. **Alimentations et Électrique** - Amina Rachidi
7. **Systèmes de Communication** - Hassan El Fassi
8. **Capteurs et Instrumentation** - Nadia Bouazza
9. **Moteurs et Entraînements** - Omar Khalil
10. **Systèmes de Sécurité** - Leila Zeroual
11. **CNC et Machines-Outils** - Rachid Benjelloun
12. **Robots Industriels** - Samira Lahlou
13. **Systèmes Hydrauliques** - Adil Mekouar
14. **Systèmes Pneumatiques** - Khadija Touimi
15. **Systèmes de Vision** - Abdelkader Boukhari

## Usage

### **Run Complete Seeder**
```bash
php artisan db:seed --class=TechnicienSeeder
```

### **Run with DatabaseSeeder**
```bash
php artisan db:seed
```

### **Development Command**
```bash
php artisan seed:techniciens
```

## Data Structure

### **Default Technician**
- **Name**: Ahmed ELouahabi
- **Email**: technicien@gmail.com
- **Phone**: 062222222222222
- **Password**: 123456789@
- **Status**: Always created if doesn't exist

### **Additional Technicians (15)**
Each technician includes:
- **Full Name**: First and last name
- **Email**: Professional company email
- **Phone**: Unique Moroccan mobile number
- **Password**: Tech2024! (for all new technicians)
- **Specialization**: Technical area of expertise
- **Status**: Active account

## Technical Details

### **User Model Fields Populated**
- `nom` - Last name
- `prenom` - First name
- `telephone` - Phone number (unique)
- `email` - Email address (unique)
- `password` - Hashed password
- `clear_password` - Plain text password
- `public_password` - Public password field
- `active` - Account status (true)
- `email_verified_at` - Email verification timestamp
- `remember_token` - Random token

### **Role Assignment**
- All users automatically assigned 'Technicien' role
- Uses Spatie Laravel Permission package
- Role assignment handled via `assignRole()` method

### **Duplicate Prevention**
- Checks for existing users by email
- Skips creation if user already exists
- Assigns role to existing users if needed
- Provides detailed logging of created vs existing users

## Business Logic

### **Specialization Distribution**
The technicians cover the full spectrum of industrial maintenance:

#### **Automation & Control**
- PLCs and automation systems
- Variable speed drives
- HMI interfaces
- Communication systems

#### **Electronics & Computing**
- Electronic cards
- Industrial PCs
- Power supplies
- Vision systems

#### **Mechanical Systems**
- Motors and drives
- Hydraulic systems
- Pneumatic systems
- CNC and machine tools

#### **Safety & Instrumentation**
- Safety systems
- Sensors and instrumentation
- Industrial robots

### **Realistic Scenarios**
- **Workload Distribution**: Multiple technicians for different specializations
- **Expertise Matching**: Specialists for specific equipment types
- **Team Collaboration**: Various skill sets for complex projects
- **Backup Coverage**: Multiple technicians per specialization area

## Development Benefits

### **Testing Scenarios**
- Test ticket assignment to different technicians
- Validate reassignment functionality
- Test workload distribution
- Verify role-based permissions

### **UI Development**
- Realistic data for technician selection dropdowns
- Various names for interface testing
- Professional email formats
- Complete user profiles

### **Business Logic Testing**
- Role assignment validation
- User authentication testing
- Permission-based access control
- Ticket-technician relationships

## Customization

### **Adding More Technicians**
```php
// In TechnicienSeeder.php
private function createTechniciens()
{
    $techniciens = [
        [
            'nom' => 'YourLastName',
            'prenom' => 'YourFirstName',
            'telephone' => '06123456789',
            'email' => 'your.email@casamaintenance.ma',
            'password' => 'YourPassword',
            'specialization' => 'Your Specialization',
        ],
        // Add more technicians...
    ];
    
    // Rest of the method...
}
```

### **Modifying Specializations**
Update the `specialization` field in the `$techniciens` array to match your business needs.

### **Changing Password Policy**
Modify the password generation logic in the seeder to match your security requirements.

### **Adjusting Contact Information**
Update email domains, phone number formats, or naming conventions as needed.

## Integration with Other Seeders

### **TicketSeeder Integration**
The TechnicienSeeder works perfectly with the TicketSeeder:
- Tickets can be assigned to any of the created technicians
- Realistic technician names appear in ticket listings
- Proper relationships between tickets and technicians

### **Reassignment Feature Testing**
Perfect for testing the new ticket reassignment feature:
- Multiple technicians available for reassignment
- Realistic names in reassignment dropdowns
- Proper role validation

## Security Considerations

### **Development Only**
- **Environment**: Designed for development/testing only
- **Passwords**: Simple passwords for easy testing
- **Data**: Fictional data, not real personnel information

### **Production Deployment**
- **Remove or Disable**: Should not be run in production
- **Real Data**: Use real technician data in production
- **Secure Passwords**: Implement proper password policies

## Notes

- **Environment**: Designed for development/testing only
- **Dependencies**: Requires roles to be seeded first
- **Realism**: Based on actual industrial maintenance scenarios
- **Flexibility**: Easy to modify for specific testing needs

This seeder provides a solid foundation for testing all aspects of technician management, ticket assignment, and the reassignment feature with realistic, business-relevant data.
