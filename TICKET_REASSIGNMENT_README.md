# Ticket Reassignment Feature - Super Admin

## Overview
This feature allows Super Admin users to reassign tickets to different technicians during or after the diagnostic operation. This addresses the business need for flexible ticket management when technicians are unavailable or when workload needs to be redistributed.

## Current System Behavior
- **Reception users** create tickets (no technician assigned initially)
- **Technicien users** can automatically claim tickets by starting diagnosis
- Once a technician touches a ticket, they become automatically assigned

## New Feature: Super Admin Reassignment

### **Who Can Use It**
- Only users with **SuperAdmin** role
- Available for tickets that have already been assigned to a technician

### **When Can It Be Used**
- **During diagnostic phase**: When a ticket is being diagnosed
- **After diagnostic phase**: When a ticket is in repair, waiting for estimate, etc.
- **Any time**: As long as the ticket has a technician assigned

### **How It Works**

#### **1. Access Points**
- **Ticket Edit Page**: Full reassignment interface in the sidebar
- **Ticket List**: Quick reassignment icon (🔄) in the actions column

#### **2. Reassignment Process**
1. Super Admin selects a new technician from the dropdown
2. Provides a mandatory reason for the reassignment
3. System validates the new technician has the "Technicien" role
4. Ticket is reassigned to the new technician
5. Action is logged in the ticket history

#### **3. Validation Rules**
- New technician must exist in the system
- New technician must have the "Technicien" role
- Reassignment reason is mandatory (max 500 characters)
- Cannot reassign to the same technician

### **Technical Implementation**

#### **Files Modified/Created**
1. **Policy**: `app/Policies/TicketPolicy.php`
   - Added `canReassign()` method

2. **Controller**: `app/Http/Controllers/Administration/Ticket/TicketController.php`
   - Added `reassign()` method
   - Updated `edit()` method to pass technicians data

3. **Routes**: `routes/app-routes/routes.php`
   - Added `POST /ticket/reassign/{ticket}` route

4. **Views**: 
   - `resources/views/theme/pages/Ticket/__edit/__reassignment.blade.php` (new)
   - `resources/views/theme/pages/Ticket/__edit/__ticket_actions.blade.php` (updated)
   - `resources/views/theme/pages/Ticket/__datatable/__with_options.blade.php` (updated)

#### **Database Changes**
- No new database changes required
- Uses existing `user_id` field in `tickets` table
- Uses existing `ticket_status` pivot table for history logging

### **User Interface**

#### **Reassignment Form**
- **Current Technician**: Read-only display
- **New Technician**: Dropdown with all available technicians
- **Reason Field**: Required textarea for explanation
- **Submit Button**: "Réassigner le Ticket" with warning styling

#### **Visual Indicators**
- Warning color scheme (yellow/orange) to indicate admin action
- Info alert explaining the action will be logged
- Disabled options for currently assigned technician

#### **Quick Access**
- Reassignment icon (🔄) in ticket list for Super Admin
- Direct link to reassignment section on edit page

### **History Tracking**
Every reassignment is logged in the ticket status history with:
- **Action**: "Réassignation du ticket par [Super Admin Name]"
- **Details**: "de [Old Technician] vers [New Technician]"
- **Reason**: The provided reassignment reason
- **Timestamp**: When the reassignment occurred

### **Security & Permissions**
- **Role-based access**: Only SuperAdmin can access
- **Policy enforcement**: Uses Laravel policies for authorization
- **Validation**: Server-side validation of all inputs
- **Audit trail**: Complete history of all reassignments

### **Business Benefits**
1. **Workload Management**: Redistribute tickets when technicians are overloaded
2. **Emergency Reassignments**: Handle urgent cases or technician unavailability
3. **Quality Control**: Move tickets to more experienced technicians when needed
4. **Flexibility**: Adapt to changing business needs and priorities

### **Usage Examples**

#### **Scenario 1: Technician Unavailable**
- Technician calls in sick
- Super Admin reassigns their active tickets to available technicians
- Reason: "Technicien indisponible - réassignation d'urgence"

#### **Scenario 2: Workload Redistribution**
- One technician has too many tickets
- Super Admin moves some tickets to less busy technicians
- Reason: "Rééquilibrage de la charge de travail"

#### **Scenario 3: Specialized Expertise**
- Ticket requires specific technical expertise
- Super Admin reassigns to technician with relevant experience
- Reason: "Réassignation pour expertise technique spécialisée"

### **Future Enhancements**
- **Bulk Reassignment**: Reassign multiple tickets at once
- **Automated Suggestions**: Suggest optimal technician based on workload
- **Notification System**: Notify technicians of reassignments
- **Performance Metrics**: Track reassignment frequency and reasons

This feature provides the flexibility needed for effective ticket management while maintaining proper oversight and accountability through Super Admin control and comprehensive logging.
