# Architecture Overview

<cite>
**Referenced Files in This Document**
- [index.php](file://frontend-php/index.php)
- [learning.php](file://frontend-php/learning.php)
- [skills.php](file://frontend-php/skills.php)
- [login.php](file://frontend-php/login.php)
- [header.php](file://frontend-php/includes/header.php)
- [footer.php](file://frontend-php/includes/footer.php)
- [style.css](file://frontend-php/css/style.css)
- [main.py](file://backend-ai/main.py)
- [requirements.txt](file://backend-ai/requirements.txt)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Analysis](#dependency-analysis)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)
10. [Appendices](#appendices)

## Introduction
This document describes the hybrid architecture of the Octal Foundry platform, which combines a PHP-based frontend (using an MVC-like structure with template includes) and a Python FastAPI backend. The frontend renders pages and UI components, while the backend exposes RESTful APIs for AI coaching, portfolio management, and skills assessment. Cross-cutting concerns such as CORS, session management, theme consistency, and responsive design are addressed to maintain a cohesive user experience across technologies.

## Project Structure
The repository is organized into two primary areas:
- frontend-php: PHP templates and assets implementing the UI and interactive widgets
- backend-ai: Python FastAPI server exposing REST endpoints

```mermaid
graph TB
subgraph "Frontend (PHP)"
FP_Index["frontend-php/index.php"]
FP_Learning["frontend-php/learning.php"]
FP_Skills["frontend-php/skills.php"]
FP_Login["frontend-php/login.php"]
FP_Header["frontend-php/includes/header.php"]
FP_Footer["frontend-php/includes/footer.php"]
FP_CSS["frontend-php/css/style.css"]
end
subgraph "Backend (FastAPI)"
BA_Main["backend-ai/main.py"]
BA_Req["backend-ai/requirements.txt"]
end
FP_Index --> FP_Header
FP_Index --> FP_Footer
FP_Learning --> FP_Header
FP_Learning --> FP_Footer
FP_Skills --> FP_Header
FP_Skills --> FP_Footer
FP_Login --> FP_Header
FP_Login --> FP_Footer
FP_Learning --> BA_Main
FP_Skills --> BA_Main
FP_Index --> BA_Main
```

**Diagram sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)
- [main.py](file://backend-ai/main.py#L1-L30)
- [requirements.txt](file://backend-ai/requirements.txt#L1-L3)

**Section sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)
- [main.py](file://backend-ai/main.py#L1-L30)
- [requirements.txt](file://backend-ai/requirements.txt#L1-L3)

## Core Components
- PHP Frontend Templates
  - index.php: Portfolio and project showcase page with navigation and tabs
  - learning.php: Interactive learning page with media player, transcript, and AI coach widget
  - skills.php: Skills radar visualization and career prediction panel
  - login.php: Authentication entry point with social login options
  - includes/header.php: Shared layout, responsive top bar, and sidebar toggle logic
  - includes/footer.php: Shared layout wrapper and JavaScript initialization
  - css/style.css: Theme variables, typography, and utility classes for consistent UI

- FastAPI Backend
  - main.py: CORS-enabled FastAPI app with endpoints for AI coaching
  - requirements.txt: Python dependencies (FastAPI, Uvicorn)

Key integration points:
- Frontend pages include header.php and footer.php to share layout and scripts
- learning.php embeds an AI coach widget that calls http://localhost:8000/api/coach/hint via fetch
- backend-ai/main.py configures CORS to allow cross-origin requests from development origins

**Section sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)
- [main.py](file://backend-ai/main.py#L1-L30)
- [requirements.txt](file://backend-ai/requirements.txt#L1-L3)

## Architecture Overview
The system follows a hybrid architecture:
- Presentation layer: PHP templates render HTML and manage UI state
- Interaction layer: JavaScript in pages initiates REST calls to the backend
- Business logic layer: FastAPI endpoints provide AI coaching suggestions and status checks

```mermaid
graph TB
Browser["Web Browser<br/>PHP Templates + JS"]
PHP_Index["index.php"]
PHP_Learning["learning.php"]
PHP_Skills["skills.php"]
PHP_Login["login.php"]
PHP_Header["includes/header.php"]
PHP_Footer["includes/footer.php"]
CSS["css/style.css"]
subgraph "Backend Services"
FA_App["FastAPI App<br/>backend-ai/main.py"]
FA_CORS["CORS Middleware"]
FA_Hint["/api/coach/hint"]
end
Browser --> PHP_Index
Browser --> PHP_Learning
Browser --> PHP_Skills
Browser --> PHP_Login
PHP_Index --> PHP_Header
PHP_Index --> PHP_Footer
PHP_Learning --> PHP_Header
PHP_Learning --> PHP_Footer
PHP_Skills --> PHP_Header
PHP_Skills --> PHP_Footer
PHP_Login --> PHP_Header
PHP_Login --> PHP_Footer
CSS --> Browser
PHP_Learning --> FA_Hint
FA_App --> FA_CORS
FA_CORS --> FA_Hint
```

**Diagram sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)
- [main.py](file://backend-ai/main.py#L1-L30)

## Detailed Component Analysis

### PHP Frontend Template System
The frontend uses a minimal MVC-like structure:
- Controllers: PHP files act as page controllers (index.php, learning.php, skills.php, login.php)
- Views: Each page composes reusable header and footer includes
- Layout: includes/header.php centralizes responsive navigation and sidebar toggle logic
- Styling: css/style.css defines theme tokens and utility classes applied across views

```mermaid
classDiagram
class IndexPage {
+header_include
+footer_include
+profile_section
+project_cards
+tabs_navigation
}
class LearningPage {
+header_include
+footer_include
+media_player
+transcript
+ide_area
+ai_coach_widget
}
class SkillsPage {
+header_include
+footer_include
+radar_chart
+career_prediction
+skill_progress
}
class LoginPage {
+header_include
+hero_section
+login_form
+social_login
}
class Header {
+responsive_topbar
+sidebar_toggle_logic
+theme_context
}
class Footer {
+bootstrap_bundle
+sidebar_toggle_script
}
class Styles {
+theme_variables
+utilities
+typography
}
IndexPage --> Header
IndexPage --> Footer
LearningPage --> Header
LearningPage --> Footer
SkillsPage --> Header
SkillsPage --> Footer
LoginPage --> Header
LoginPage --> Footer
Header --> Styles
Footer --> Styles
```

**Diagram sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)

**Section sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [style.css](file://frontend-php/css/style.css#L1-L114)

### AI Coaching Integration Pattern
The AI coaching widget in learning.php demonstrates a RESTful integration:
- On open, the widget triggers a fetch to http://localhost:8000/api/coach/hint
- The backend responds with a JSON payload containing a coaching hint
- The UI updates dynamically to display the suggestion

```mermaid
sequenceDiagram
participant User as "User"
participant Page as "learning.php"
participant Widget as "AI Coach Widget"
participant API as "FastAPI /api/coach/hint"
User->>Page : Open learning page
Page->>Widget : Render floating action button
User->>Widget : Click smart_toy icon
Widget->>API : GET /api/coach/hint
API-->>Widget : {message, layer, position}
Widget-->>Page : Update tooltip text
Page-->>User : Display coaching suggestion
```

**Diagram sources**
- [learning.php](file://frontend-php/learning.php#L177-L212)
- [main.py](file://backend-ai/main.py#L23-L29)

**Section sources**
- [learning.php](file://frontend-php/learning.php#L177-L212)
- [main.py](file://backend-ai/main.py#L23-L29)

### CORS Middleware and Cross-Origin Requests
The backend enables cross-origin requests to support local development:
- Origins configured to accept "*" for development convenience
- Credentials, methods, and headers allowed broadly during development

```mermaid
flowchart TD
Start(["Browser Request"]) --> OriginCheck["CORS Middleware Check"]
OriginCheck --> Allowed{"Origin Allowed?"}
Allowed --> |Yes| Proceed["Proceed to Handler"]
Allowed --> |No| Block["Block Request"]
Proceed --> Handler["Route Handler"]
Handler --> Response["JSON Response"]
Block --> ErrorResponse["CORS Error Response"]
```

**Diagram sources**
- [main.py](file://backend-ai/main.py#L6-L17)

**Section sources**
- [main.py](file://backend-ai/main.py#L6-L17)

### Theme Consistency and Responsive Design
Theme consistency is achieved through:
- CSS custom properties in style.css defining primary colors and backgrounds
- Utility classes for glass effects, rounded corners, and typography
- Bootstrap integration for responsive grid and component behavior

Responsive design elements:
- Sticky headers and bottom navigation bars
- Flexible grids for project cards and skill progress bars
- Sidebar toggle logic in header.php for mobile-friendly navigation

**Section sources**
- [style.css](file://frontend-php/css/style.css#L1-L114)
- [header.php](file://frontend-php/includes/header.php#L22-L26)
- [index.php](file://frontend-php/index.php#L23-L102)
- [skills.php](file://frontend-php/skills.php#L18-L166)

## Dependency Analysis
High-level dependencies:
- Frontend depends on shared includes and CSS for consistent rendering
- learning.php depends on backend endpoint for AI coaching
- Backend depends on FastAPI and CORS middleware for cross-origin support

```mermaid
graph LR
PHP_Index["index.php"] --> Header["includes/header.php"]
PHP_Index --> Footer["includes/footer.php"]
PHP_Learning["learning.php"] --> Header
PHP_Learning --> Footer
PHP_Skills["skills.php"] --> Header
PHP_Skills --> Footer
PHP_Login["login.php"] --> Header
PHP_Login --> Footer
PHP_Learning --> FA_Hint["/api/coach/hint"]
FA_App["backend-ai/main.py"] --> FA_Hint
```

**Diagram sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [main.py](file://backend-ai/main.py#L23-L29)

**Section sources**
- [index.php](file://frontend-php/index.php#L1-L174)
- [learning.php](file://frontend-php/learning.php#L1-L215)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [login.php](file://frontend-php/login.php#L1-L94)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [footer.php](file://frontend-php/includes/footer.php#L1-L31)
- [main.py](file://backend-ai/main.py#L1-L30)

## Performance Considerations
- Minimize frontend JavaScript fetch calls; batch or debounce as needed
- Use lazy loading for images and media players to reduce initial load
- Keep CORS configuration restrictive in production to avoid unnecessary overhead
- Cache static assets and leverage browser caching headers

## Troubleshooting Guide
Common issues and resolutions:
- AI coach widget shows offline or connection failed
  - Verify backend is running on http://localhost:8000
  - Confirm CORS allows the origin in development
  - Check network tab for blocked requests due to CORS policy

- Theme inconsistencies across pages
  - Ensure header.php and footer.php are included on each page
  - Verify style.css is linked and loaded before interactive scripts

- Sidebar toggle not working on mobile
  - Confirm sidebar and overlay elements exist in the DOM
  - Check that sidebar toggle script runs after DOMContentLoaded

**Section sources**
- [learning.php](file://frontend-php/learning.php#L177-L212)
- [main.py](file://backend-ai/main.py#L6-L17)
- [header.php](file://frontend-php/includes/header.php#L8-L18)
- [footer.php](file://frontend-php/includes/footer.php#L7-L28)

## Conclusion
The Octal Foundry platform employs a pragmatic hybrid architecture: PHP templates deliver a responsive, theme-consistent UI, while FastAPI provides lightweight REST endpoints for AI coaching. CORS middleware enables seamless cross-origin communication during development. By maintaining shared layouts and CSS utilities, the system achieves cohesion across technologies and supports future extensions for portfolio management and skills assessment.

## Appendices
- Development setup
  - Backend: Install dependencies from requirements.txt and run the FastAPI app
  - Frontend: Serve PHP files via a local web server; ensure CORS is configured for development origins

**Section sources**
- [requirements.txt](file://backend-ai/requirements.txt#L1-L3)
- [main.py](file://backend-ai/main.py#L1-L30)