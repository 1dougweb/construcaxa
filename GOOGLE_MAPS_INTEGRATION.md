# Google Maps Integration

## Overview

This integration adds Google Maps functionality to the stock management system, allowing visualization of project locations on an interactive map in the dashboard.

## Features

- **Interactive Map**: Display projects with status-based colored pins
- **Automatic Geocoding**: Convert addresses to coordinates automatically
- **Admin Configuration**: Manage Google Maps API key through admin interface
- **Project Information**: Click pins to view project details and navigate to project pages
- **Responsive Design**: Map adapts to different screen sizes

## Setup Instructions

### 1. Get Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the following APIs:
   - Maps JavaScript API
   - Geocoding API
4. Create credentials (API Key)
5. Restrict the API key to your domain for security

### 2. Configure in Application

1. Login as admin/manager
2. Navigate to **Configurações** in the main menu
3. Enter your Google Maps API key
4. Save the configuration

### 3. Add Project Locations

When creating or editing projects:
- Enter the project address
- Coordinates will be automatically geocoded and saved
- Projects with coordinates will appear on the dashboard map

## Technical Details

### Database Changes

- Added `latitude` and `longitude` fields to `projects` table
- Created `settings` table for system configuration

### New Components

- **GoogleMapsService**: Handles geocoding and reverse geocoding
- **GoogleMapsComponent**: Livewire component for map display
- **AdminController**: Manages system settings
- **Setting Model**: Handles configuration storage

### Files Modified

- `app/Models/Project.php` - Added coordinate fields
- `app/Http/Controllers/ProjectController.php` - Added geocoding on create/update
- `resources/views/dashboard.blade.php` - Integrated map component
- `resources/views/navigation-menu.blade.php` - Added settings link

## Map Features

### Pin Colors by Status
- **Green**: In Progress
- **Yellow**: Planned
- **Red**: Paused
- **Gray**: Completed/Cancelled

### Info Windows
Each pin shows:
- Project name and code
- Client name
- Progress percentage
- Address
- Link to project details

## Troubleshooting

### Map Not Loading
- Check if Google Maps API key is configured
- Verify API key has proper permissions
- Check browser console for JavaScript errors

### Geocoding Not Working
- Ensure Geocoding API is enabled in Google Cloud Console
- Check API key quotas and billing
- Verify address format is correct

### No Projects on Map
- Projects need both latitude and longitude coordinates
- Only projects with status "in_progress" are shown by default
- Check if projects have valid addresses for geocoding

## Security Considerations

- Restrict API key to specific domains
- Monitor API usage to avoid unexpected charges
- Keep API key secure and don't commit to version control
- Consider implementing rate limiting for geocoding requests





