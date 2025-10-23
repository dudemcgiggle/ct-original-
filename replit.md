# Cape Canaveral Lighthouse & Spaceflight Tours

## Overview

This is a static website for a tour company offering lighthouse and spaceflight tours at Cape Canaveral. The site provides information about tours, displays customer reviews, and facilitates bookings. Currently, the site displays a shutdown notice indicating tours are temporarily canceled due to government closure of the military station.

The application is a simple, static frontend-only website built with vanilla HTML, CSS, and JavaScript. There is no backend server, database, or external API integrations at this time.

## Recent Changes

**October 23, 2025:**
- Initial website implementation based on reference site https://canaveral.tours/
- Created complete HTML structure with navigation, tour information, and booking sections
- Implemented responsive CSS design with custom properties for theming
- Added notice banner for government shutdown message
- Configured Python HTTP server workflow on port 5000
- Connected to GitHub repository: https://github.com/dudemcgiggle/ct-original-.git
- Set up GitHub authentication using Personal Access Token stored in Replit Secrets

## User Preferences

Preferred communication style: Simple, everyday language.

## GitHub Integration

**Repository:** https://github.com/dudemcgiggle/ct-original-.git
**Authentication:** GitHub Personal Access Token stored in Replit Secret `GITHUB_PERSONAL_ACCESS_TOKEN`
**Push Command:** `git push https://dudemcgiggle:$GITHUB_PERSONAL_ACCESS_TOKEN@github.com/dudemcgiggle/ct-original-.git main`

## System Architecture

### Frontend Architecture

**Technology Stack:** Vanilla HTML5, CSS3, and JavaScript (no frameworks)

**Design Pattern:** Single-page application with section-based navigation using anchor links

**Rationale:** A lightweight, static approach was chosen for simplicity and fast loading times. Since this is an informational website with limited interactivity requirements, a framework-free solution reduces complexity and improves performance.

**Key Components:**
- Navigation header with sticky positioning for persistent access to site sections
- Notice banner system for communicating service interruptions
- Review/rating display section powered by Google reviews
- Responsive layout using CSS custom properties (CSS variables) for theming

**Styling Architecture:**
- CSS custom properties (`:root` variables) for centralized theming
- Color scheme focused on trust (blue primary) with accent colors for calls-to-action
- Mobile-first responsive design approach
- Utility-first component structure with `.container` wrapper for consistent spacing

**JavaScript Implementation:** Currently minimal (empty `script.js` file), indicating either future functionality is planned or the site operates entirely through CSS-driven interactions

### Content Structure

The site is organized into logical sections accessible via anchor navigation:
- About section with tour information
- Tours listing section
- Reviews section with Google-powered ratings
- Booking call-to-action

**Advantages:**
- Zero build process or dependencies
- Easy to deploy on any static hosting platform
- Minimal maintenance overhead
- Fast page load times

**Limitations:**
- No dynamic content updates without manual HTML editing
- No booking functionality implementation (would require backend integration)
- Limited interactivity without additional JavaScript development

## External Dependencies

**Google Reviews Integration:** The site displays a rating (4.8 stars from 190 reviews) powered by Google, though the integration mechanism is not yet implemented in the current codebase. This would typically require either the Google Places API or embedded Google review widgets.

**No Current Technical Dependencies:**
- No package managers (npm, yarn)
- No build tools (webpack, vite)
- No CSS preprocessors (Sass, Less)
- No JavaScript frameworks or libraries
- No backend services
- No database systems

**Future Integration Opportunities:**
- Booking system integration for reservation management
- Payment processing gateway for tour bookings
- Email notification service for booking confirmations
- Content management system for easier updates to tour schedules and pricing