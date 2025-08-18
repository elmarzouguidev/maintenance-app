# Date Range Filter for Tickets

## Overview
This implementation adds a date range filter to the Ticket index page, allowing users to filter tickets by creation date range.

## Features
- **Start Date Filter**: Filter tickets created from a specific date
- **End Date Filter**: Filter tickets created up to a specific date
- **Date Range Filter**: Filter tickets within a specific date range
- **Individual Date Filters**: Use start date or end date independently
- **French Date Format**: Uses dd-mm-yyyy format
- **Error Handling**: Graceful handling of invalid date formats

## Implementation Details

### 1. Frontend Changes

#### Filter Form (`resources/views/theme/pages/Ticket/__datatable/__filters.blade.php`)
- Added two date input fields: "Date de début" and "Date de fin"
- Uses Bootstrap Datepicker with French localization
- Maintains existing filter state on page reload

#### JavaScript (`resources/views/theme/pages/Ticket/__datatable/__js_filters.blade.php`)
- Added `getStartDateFilter()` and `getEndDateFilter()` functions
- Updated `filterResults()` to include date range parameters
- Enhanced error handling and logging

### 2. Backend Changes

#### Model Scopes (`app/Models/Scopes/TicketScopes.php`)
- `scopeFiltersStartDate()`: Filters tickets created from start date
- `scopeFiltersEndDate()`: Filters tickets created up to end date
- `scopeFiltersDateRange()`: Filters tickets within date range
- Added error handling for invalid date formats

#### Controller (`app/Http/Controllers/Administration/Ticket/TicketController.php`)
- Updated `index()` method to use new date range filters
- Updated `oldTow()` method to use new date range filters
- Replaced old single date filter with start/end date filters

## Usage

### Filter by Date Range
1. Select a start date in "Date de début" field
2. Select an end date in "Date de fin" field
3. Click "filter" button
4. Results will show tickets created between the selected dates

### Filter by Start Date Only
1. Select only a start date
2. Leave end date empty
3. Results will show tickets created from the start date onwards

### Filter by End Date Only
1. Select only an end date
2. Leave start date empty
3. Results will show tickets created up to the end date

### Combine with Other Filters
- Date range filters work with all existing filters (Client, Status, Etat, Retour)
- Multiple filters can be applied simultaneously

## Technical Specifications

### Date Format
- **Input Format**: dd-mm-yyyy (e.g., 25-12-2024)
- **Database Query**: Uses Carbon for date parsing and comparison
- **Timezone**: Uses application's configured timezone

### Database Queries
- **Start Date**: `WHERE created_at >= start_date 00:00:00`
- **End Date**: `WHERE created_at <= end_date 23:59:59`
- **Date Range**: `WHERE created_at BETWEEN start_date 00:00:00 AND end_date 23:59:59`

### Error Handling
- Invalid date formats are gracefully ignored
- Empty date fields are not included in the filter
- Database queries continue to work even with malformed dates

## Browser Compatibility
- Works with all modern browsers
- Requires JavaScript enabled
- Uses Bootstrap Datepicker for consistent UI

## Future Enhancements
- Add date validation on frontend
- Add "Clear Filters" button
- Add date range presets (Last 7 days, Last 30 days, etc.)
- Add export functionality for filtered results
