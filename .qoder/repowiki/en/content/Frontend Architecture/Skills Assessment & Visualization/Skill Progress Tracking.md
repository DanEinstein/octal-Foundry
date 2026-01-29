# Skill Progress Tracking

<cite>
**Referenced Files in This Document**
- [skills.php](file://frontend-php/skills.php)
- [style.css](file://frontend-php/css/style.css)
- [header.php](file://frontend-php/includes/header.php)
- [sidebar.php](file://frontend-php/includes/sidebar.php)
- [dashboard.php](file://frontend-php/dashboard.php)
- [index.php](file://frontend-php/index.php)
- [main.py](file://backend-ai/main.py)
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

## Introduction
This document explains the skill progress tracking system implemented in the frontend PHP application. It focuses on:
- The Top Skills Breakdown component with individual skill percentages and progress bars
- The skill categorization system reflected in the Skills Radar visualization
- Visual indicators for different skill levels and color-coded representations
- Bootstrap-based progress bar implementation and percentage calculations
- Skill ranking and threshold-based level indicators
- Responsive design patterns for skill cards
- Examples of skill data structure and growth visualization with trend indicators
- Integration points with user skill assessments and the AI coach

## Project Structure
The skill tracking UI spans several pages and shared assets:
- Skills page: Top Skills Breakdown, Skills Radar, AI Career Prediction, Skill Growth Trend
- Dashboard page: Skills being tracked widget with progress bars
- Shared styles: Bootstrap 5 CSS and custom CSS for theming and progress bar tweaks
- Shared layout: Header and sidebar for navigation and responsive behavior

```mermaid
graph TB
subgraph "Shared Assets"
H["Header (includes/header.php)"]
S["Sidebar (includes/sidebar.php)"]
C["Styles (css/style.css)"]
end
subgraph "Pages"
SK["Skills (frontend-php/skills.php)"]
DB["Dashboard (frontend-php/dashboard.php)"]
IDX["Index (frontend-php/index.php)"]
BA["Backend API (backend-ai/main.py)"]
end
H --> SK
H --> DB
H --> IDX
S --> SK
S --> DB
C --> SK
C --> DB
C --> IDX
SK -. "links to" .-> IDX
DB -. "links to" .-> SK
BA -. "coaches hints" .-> DB
```

**Diagram sources**
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [sidebar.php](file://frontend-php/includes/sidebar.php#L1-L81)
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [dashboard.php](file://frontend-php/dashboard.php#L1-L279)
- [index.php](file://frontend-php/index.php#L1-L174)
- [main.py](file://backend-ai/main.py#L1-L30)

**Section sources**
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [sidebar.php](file://frontend-php/includes/sidebar.php#L1-L81)
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [dashboard.php](file://frontend-php/dashboard.php#L1-L279)
- [index.php](file://frontend-php/index.php#L1-L174)
- [main.py](file://backend-ai/main.py#L1-L30)

## Core Components
- Skills Radar: Hexagonal visualization with six axes representing categories such as Technical Proficiency, Soft Skills, Practical, Theory Knowledge, Problem Solving, and Analytical.
- Top Skills Breakdown: A vertical list of skills with percentage labels and Bootstrap progress bars.
- Skills Being Tracked (Dashboard): Compact progress bars and percentages for skills under active learning.
- Skill Growth Trend: A horizontal bar chart showing skill improvement over time with visual markers.
- AI Career Prediction: A contextual recommendation panel based on proficiency clusters.
- Color-coded skill representations: Primary blue, primary orange, and secondary colors for different skill levels and targets.

Key implementation references:
- Skills Radar and Top Skills Breakdown: [skills.php](file://frontend-php/skills.php#L18-L153)
- Skills Being Tracked (Dashboard): [dashboard.php](file://frontend-php/dashboard.php#L168-L200)
- Progress bar styling: [style.css](file://frontend-php/css/style.css#L281-L288)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L18-L153)
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)
- [style.css](file://frontend-php/css/style.css#L281-L288)

## Architecture Overview
The skill tracking UI is composed of static HTML/CSS/Bootstrap markup with PHP includes for layout and navigation. The backend exposes a minimal API for AI coaching hints, which complements the dashboard’s performance guidance.

```mermaid
graph TB
UI["Skills UI Markup<br/>skills.php, dashboard.php"]
LYT["Layout Includes<br/>header.php, sidebar.php"]
CSS["Styling<br/>style.css"]
API["AI Coach API<br/>backend-ai/main.py"]
LYT --> UI
CSS --> UI
API --> UI
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [dashboard.php](file://frontend-php/dashboard.php#L1-L279)
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [sidebar.php](file://frontend-php/includes/sidebar.php#L1-L81)
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [main.py](file://backend-ai/main.py#L1-L30)

## Detailed Component Analysis

### Skills Radar Visualization
The Skills Radar presents a hexagonal grid with six labeled axes and concentric rings. It includes:
- Axis labels for Technical Proficiency, Soft Skills, Practical, Theory Knowledge, Problem Solving, and Analytical
- A translucent polygon overlay representing current mastery
- A target benchmark indicator
- Glowing effects and gradient backgrounds for depth

Implementation highlights:
- Uses custom clip-path polygons for the hexagonal grid and overlay
- Applies opacity layers to create concentric rings
- Utilizes theme colors for axis labels and borders

```mermaid
flowchart TD
Start(["Render Radar"]) --> Grid["Draw Hexagonal Grid<br/>clip-path polygons"]
Grid --> Rings["Add Opacity Layers<br/>for concentric rings"]
Rings --> Overlay["Overlay Current Mastery<br/>translucent polygon"]
Overlay --> Labels["Place Axis Labels<br/>6 directional labels"]
Labels --> Legend["Add Legend<br/>Current Mastery / Target"]
Legend --> End(["Complete"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L18-L56)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L18-L56)

### Top Skills Breakdown
The Top Skills Breakdown displays a ranked list of skills with:
- Skill name
- Percentage value
- Bootstrap progress bar with rounded pill ends
- Color-coded bars (blue/orange/secondary) aligned with theme colors

Progress bar implementation:
- Outer container with light background and reduced height
- Inner progress-bar with rounded-pill and width set to the skill percentage
- Percentage shown adjacent to the bar

```mermaid
sequenceDiagram
participant U as "User"
participant P as "Skills Page"
participant DOM as "DOM Elements"
U->>P : Navigate to Skills
P->>DOM : Render skill items
DOM-->>U : Display skill name, percentage, progress bar
Note over DOM : Progress bar width equals percentage value
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L58-L96)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L58-L96)

### Skills Being Tracked (Dashboard)
The Skills Being Tracked widget shows three skills currently under focus:
- CNN Architecture: 65%
- Python/PyTorch: 78%
- Problem Solving: 52%

Each entry includes:
- A compact progress bar (small height)
- A numeric percentage aligned to the bar

```mermaid
flowchart TD
DStart(["Dashboard Load"]) --> Fetch["Fetch skill data"]
Fetch --> Render["Render skill rows"]
Render --> Bars["Render small progress bars"]
Bars --> Percent["Show percentages"]
Percent --> DEnd(["Ready"])
```

**Diagram sources**
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)

**Section sources**
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)

### Skill Growth Trend
The Skill Growth Trend section visualizes improvement over time:
- Six bars representing monthly progress
- Increasing heights indicate improvement
- A highlighted marker on the peak bar indicates recent progress
- Horizontal grid lines for readability

```mermaid
flowchart TD
GStart(["Render Trend"]) --> Bars["Create 6 bars with varying heights"]
Bars --> Marker["Add highlight marker on peak bar"]
Marker --> Grid["Overlay horizontal grid lines"]
Grid --> Labels["Add month labels below"]
Labels --> GEnd(["Complete"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L124-L153)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L124-L153)

### AI Career Prediction
The AI Career Prediction panel suggests roles based on proficiency clusters:
- Displays role badges with confidence percentages
- Uses gradient backgrounds and backdrop blur for depth
- Integrates with the radar’s category insights

```mermaid
flowchart TD
CPStart(["Compute Prediction"]) --> Categories["Analyze radar categories"]
Categories --> Roles["Select matching roles"]
Roles --> Scores["Assign confidence scores"]
Scores --> Display["Render role badges with scores"]
Display --> CPEnd(["Complete"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L98-L122)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L98-L122)

### Progress Bar Implementation and Percentage Calculation
Progress bars are implemented using Bootstrap utilities:
- Container div with class for background and reduced height
- Inner progress-bar with width equal to the percentage value
- Rounded pill ends via Bootstrap classes

Percentage calculation method:
- Percentage values are embedded directly in the markup
- Example: width set to 88% for Python Development, 72% for Algorithm Design, 45% for Cloud Infrastructure

```mermaid
flowchart TD
CalcStart(["Calculate Percentage"]) --> SetWidth["Set progress-bar width to percentage"]
SetWidth --> RenderBar["Render progress container and bar"]
RenderBar --> ShowPercent["Display percentage label"]
ShowPercent --> Done(["Done"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L66-L94)
- [dashboard.php](file://frontend-php/dashboard.php#L171-L199)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L66-L94)
- [dashboard.php](file://frontend-php/dashboard.php#L171-L199)
- [style.css](file://frontend-php/css/style.css#L281-L288)

### Color-Coded Skill Representations
Color coding is applied consistently:
- Primary blue for current mastery and primary skill emphasis
- Primary orange for target benchmarks and highlights
- Secondary for neutral or baseline skill levels
- Theme variables define brand-safe colors used across components

```mermaid
classDiagram
class ThemeColors {
+string primary_blue
+string primary_orange
+string bg_dark
+string card_dark
}
class SkillItem {
+string name
+number percentage
+string color_scheme
}
ThemeColors <.. SkillItem : "defines color palette"
```

**Diagram sources**
- [style.css](file://frontend-php/css/style.css#L1-L11)
- [skills.php](file://frontend-php/skills.php#L66-L94)

**Section sources**
- [style.css](file://frontend-php/css/style.css#L1-L11)
- [skills.php](file://frontend-php/skills.php#L66-L94)

### Skill Ranking and Threshold-Based Indicators
Ranking:
- Top Skills Breakdown lists skills in descending order of percentage
- Dashboard’s Skills Being Tracked shows current focus skills

Threshold-based indicators:
- Color intensity and bar prominence can imply proficiency tiers
- The trend chart’s peak marker highlights significant improvement

```mermaid
flowchart TD
RankStart(["Rank Skills"]) --> Sort["Sort by percentage desc"]
Sort --> Tiers["Apply thresholds for levels"]
Tiers --> Visuals["Map to color and prominence"]
Visuals --> RankEnd(["Ranked List Ready"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L58-L96)
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L58-L96)
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)

### Responsive Design Patterns for Skill Cards
Responsive patterns observed:
- Flexbox containers for alignment and spacing
- Aspect-ratio utilities for the radar container
- Gap utilities for consistent spacing between items
- Small and compact progress bars for constrained widths
- Mobile-first layout with sidebar toggle for dashboard pages

```mermaid
flowchart TD
RStart(["Responsive Setup"]) --> Flex["Use flex utilities for alignment"]
Flex --> Aspect["Maintain aspect ratios for radar"]
Aspect --> Gaps["Apply gap utilities for spacing"]
Gaps --> Compact["Use compact progress bars on small screens"]
Compact --> REnd(["Consistent UX Across Devices"])
```

**Diagram sources**
- [skills.php](file://frontend-php/skills.php#L18-L96)
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)
- [header.php](file://frontend-php/includes/header.php#L22-L26)

**Section sources**
- [skills.php](file://frontend-php/skills.php#L18-L96)
- [dashboard.php](file://frontend-php/dashboard.php#L168-L200)
- [header.php](file://frontend-php/includes/header.php#L22-L26)

### Integration with User Skill Assessments and AI Coach
Integration points:
- Dashboard’s Skills Being Tracked pulls in current skill percentages during learning
- AI Coach API provides hints and tips that complement skill development
- Skills Radar and Growth Trend offer visual feedback loops for assessments

```mermaid
sequenceDiagram
participant User as "User"
participant Dash as "Dashboard"
participant API as "AI Coach API"
participant Skills as "Skills UI"
User->>Dash : View current unit and skills
Dash->>API : Request performance hint
API-->>Dash : Return hint payload
Dash->>Skills : Render skills and trends
Skills-->>User : Display progress and predictions
```

**Diagram sources**
- [dashboard.php](file://frontend-php/dashboard.php#L104-L213)
- [main.py](file://backend-ai/main.py#L23-L29)
- [skills.php](file://frontend-php/skills.php#L98-L153)

**Section sources**
- [dashboard.php](file://frontend-php/dashboard.php#L104-L213)
- [main.py](file://backend-ai/main.py#L23-L29)
- [skills.php](file://frontend-php/skills.php#L98-L153)

## Dependency Analysis
- Layout dependencies: The skills and dashboard pages rely on shared header and sidebar includes for navigation and responsive behavior.
- Styling dependencies: Custom CSS defines theme variables and progress bar tweaks; Bootstrap 5 CSS provides base utilities.
- Data dependencies: Skill percentages are embedded in the UI markup; future enhancements could fetch data from backend APIs.

```mermaid
graph LR
Header["Header (header.php)"] --> Skills["Skills (skills.php)"]
Header --> Dashboard["Dashboard (dashboard.php)"]
Sidebar["Sidebar (sidebar.php)"] --> Skills
Sidebar --> Dashboard
Styles["Styles (style.css)"] --> Skills
Styles --> Dashboard
```

**Diagram sources**
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [sidebar.php](file://frontend-php/includes/sidebar.php#L1-L81)
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [dashboard.php](file://frontend-php/dashboard.php#L1-L279)

**Section sources**
- [header.php](file://frontend-php/includes/header.php#L1-L71)
- [sidebar.php](file://frontend-php/includes/sidebar.php#L1-L81)
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [skills.php](file://frontend-php/skills.php#L1-L189)
- [dashboard.php](file://frontend-php/dashboard.php#L1-L279)

## Performance Considerations
- Keep progress bar widths simple percentages to minimize reflows.
- Prefer CSS transforms for animations; the existing UI relies on static widths.
- Consolidate repeated color classes to reduce CSS bloat.
- Lazy-load images in trend visuals if content grows.

## Troubleshooting Guide
Common issues and resolutions:
- Progress bars not rendering: Verify Bootstrap classes are loaded and progress container has sufficient height.
- Color inconsistencies: Ensure theme variables are defined and used consistently across components.
- Radar layout distortion: Confirm clip-path polygons and aspect-ratio utilities are applied correctly.
- Sidebar overlap on mobile: Check sidebar toggle logic and overlay visibility classes.

**Section sources**
- [style.css](file://frontend-php/css/style.css#L1-L289)
- [skills.php](file://frontend-php/skills.php#L18-L153)
- [header.php](file://frontend-php/includes/header.php#L22-L26)

## Conclusion
The skill progress tracking system combines a visually rich Skills Radar, a concise Top Skills Breakdown, and a growth trend visualization to present user proficiency clearly. Bootstrap utilities power the progress bars, while theme-driven color coding communicates mastery levels. The dashboard integrates AI coach hints to guide learning, and the responsive layout ensures usability across devices. Future enhancements can include dynamic data binding and backend APIs for real-time skill updates.